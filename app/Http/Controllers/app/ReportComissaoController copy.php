<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Custo;
use App\Models\Meta;
use App\Models\Servico;
use App\Models\User;
use App\Models\ValorServico;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportComissaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function reportComissao(Request $request){

        $this->authorize('ativo');

        $user = Auth()->user();

        $dataForm = $request->all();

        $msg = isset($dataForm['msg']) ? $dataForm['msg'] : NULL;

        !isset($dataForm['data_ini']) ? $dataForm['data_ini'] = DATE('Y-m-d').' 00:00:00' : $dataForm['data_ini'] = $dataForm['data_ini'].' 00:00:00';
        !isset($dataForm['data_fim']) ? $dataForm['data_fim'] = DATE('Y-m-d').' 23:59:00' : $dataForm['data_fim'] = $dataForm['data_fim'].' 23:59:00';

        $dataIni =  date( 'd/m/Y', strtotime($dataForm['data_ini']) );
        $dataFim =  date( 'd/m/Y', strtotime($dataForm['data_fim']) );

        //datas em formato DateTime
        $dt_ini = DateTime::createFromFormat('d/m/Y',$dataIni);
        $dt_fim = DateTime::createFromFormat('d/m/Y',$dataFim);

        $hoje = DateTime::createFromFormat('Y-m-d', DATE('Y-m-d'));

        $dif_dias = date_diff($dt_ini, $dt_fim);
        
        $mes = $dt_ini->format('m');                            
        $ano = $dt_ini->format('Y');

        $servicos = Servico::all();

        $custo = Custo::where('mes',$mes)->where('ano',$ano)->first();

        if (empty($custo)){
            return redirect()
            ->route('home')
            ->with(['msg'   =>  'Valores de Custo não Cadastrados Mês:'.$mes.' Ano:'.$ano,
                    'type'  =>  'warning']);
        }

        $valUmPonto = $custo->valor / 1000;

        if (empty($custo)){
            return redirect()
            ->route('home')
            ->with(['msg'   =>  'Valores do Custo ( Mês '.$mes.', Ano '.$ano.') não Cadastrados',
                    'type'  =>  'warning']);
        }

        $metas = Meta::where('mes',$mes)->where('ano',$ano)->orderBy('meta','asc')->get();        

        //defino que o tecnico so pode ver sua propria producao
        if($user->nv_acesso == 'tecnico'){
            $dataForm['tecnico'] = $user->id;
            $tecnicos = User::where('id',$user->id)->where('status','ativo')->orderBy('name','asc')->get();
        }else{
            $tecnicos = User::where('nv_acesso','tecnico')->where('status','ativo')->orderBy('name','asc')->get();
        }

        !isset($dataForm['tecnico']) ? $tecnico = $user : $tecnico = User::find($dataForm['tecnico']);

        //Condicoes SQL
        $condicoes = [
            isset($dataForm['data_ini']) ? " dt_abertura >= '" .$dataForm['data_ini']. "'"  : NULL,
            isset($dataForm['data_fim']) ? " dt_abertura <= '" .$dataForm['data_fim']. "'"  : NULL,
            isset($dataForm['tecnico']) ? " id_user_tec = " .$dataForm['tecnico']. " "  : NULL,
        ];

        $condicoes = array_filter($condicoes);

        //Clausulas
        $where = implode (' AND ',$condicoes);

        $sql = "SELECT * FROM `ordems` WHERE $where ";

        $ordensFinalizadas = DB::select($sql.' AND status = "finalizada" ');

        //Converte para array
        $ordensFinalizadas = json_decode(json_encode($ordensFinalizadas), true);

        //############### tblServicos
        $tblServicos = [];
        $valBruto = 0;
        foreach ($ordensFinalizadas as $ordemFinalizada) {

            $servico = Servico::find($ordemFinalizada['id_servico_tipo']);

            $valorServico = ValorServico::where('id_servico',$ordemFinalizada['id_servico_tipo'])->where('mes',$mes)->where('ano',$ano)->first();

            if (empty($valorServico)){
                return redirect()
                ->route('home')
                ->with(['msg'   =>  'Valores de Servicos ('.$ordemFinalizada['id_servico_tipo'].') não Cadastrados Mês:'.$mes.' Ano:'.$ano,
                        'type'  =>  'warning']);
            }

            !isset(${$servico->nome.'qtdServico'}) ?  ${$servico->nome.'qtdServico'} = 0 : '';
            ${$servico->nome.'qtdServico'} = ${$servico->nome.'qtdServico'} + 1;


            $valorSerPontos = $valorServico->valor / $valUmPonto;

            !isset(${$servico->nome.'qtdPontos'}) ?  ${$servico->nome.'qtdPontos'} = 0 : '';
            ${$servico->nome.'qtdPontos'} = ${$servico->nome.'qtdPontos'} + $valorSerPontos;

            $valBruto = $valBruto + $valorServico->valor;

            $qtdPontoServico = $valorServico->valor / $valUmPonto;

            $tblServicosTmp = [
                $servico->nome => [
                    'qtdServico' => ${$servico->nome.'qtdServico'},
                    'qtdPontoServico' => number_format($qtdPontoServico,2,",","."),
                    'qtdPontos' => number_format(${$servico->nome.'qtdPontos'},0,",","."),

                ]
            ];

            $tblServicos = array_merge_recursive_distinct($tblServicos,$tblServicosTmp);

        }
        //###############  tblServicosPontos FIM

        //################ Dados Gerais Inicio

        // $dadosGerais = [
        //     'TECNICO' => $tecnico->name,
        //     'BRUTO' =>  'R$'.number_format(0,2,",","."),
        //     'LIQUIDO' =>  'R$'.number_format(0,2,",","."),
        //     'PONTOS' => number_format(0,0,",","."),
        //     'COMISSAO' => 'R$'.number_format(0,2,",","."),
        // ];
        
        $liquido = $valBruto - $custo->valor;

        $tecnicoPontos = $valBruto / $valUmPonto;

        $taxaComissao = 0;
        //identifica em qual faixa esta da meta
        foreach ($metas as $keyMetas => $valueMetas) {
            
            $valorMeta = $custo->valor + ($custo->valor * ($valueMetas->meta /100));

            if ($valorMeta < $valBruto){
                $taxaComissao = $valueMetas->comissao / 100;
            }
        }

        $valComissao = $valBruto * $taxaComissao;

        $dadosGerais = [
            'TECNICO' => $tecnico->name,
            'BRUTO' =>  'R$'.number_format($valBruto,2,",","."),
            'LIQUIDO' =>  'R$'.number_format($liquido,2,",","."),
            'PONTOS' => number_format($tecnicoPontos,0,",","."),
            'COMISSAO' => 'R$'.number_format($valComissao,2,",","."),
        ];
        
        //################ Dados Gerais FIM

        //############### Valores dos servicos em pontos Inicio
        $tblMetas = [];
        $i = 0;
        foreach ($metas as $metasKey => $metasValue) {

            $pontosMeta = $custo->valor + ($custo->valor * ($metasValue->meta /100));
            $pontosMeta = $pontosMeta / $valUmPonto;
            
            $valorPonto = $valUmPonto * ($metasValue->comissao / 100);

            $tblMetasTmp[$i] = [
                'descricao' => $metasValue->descricao,
                'pontos' => $pontosMeta,
                'valPonto' => number_format($valorPonto,2,",","."),
            ];

            array_push($tblMetas, $tblMetasTmp[$i]);

            $i++;
        }
        //############### Valores dos servicos em pontos Fim



        return view('app.report.reportComissao')->with([   
            'msg'       => $msg,
            'dataIni'   => $dataIni,
            'dataFim'   => $dataFim,
            'tecnicos' => $tecnicos,
            'tblMetas' => $tblMetas,
            'hoje' => $hoje,
            'tblServicos' => $tblServicos,
            'dadosGerais' => $dadosGerais
        ]);

        

    }
}
