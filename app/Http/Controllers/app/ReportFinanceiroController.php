<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Custo;
use App\Models\ItenRequisicao;
use App\Models\Meta;
use App\Models\Produto;
use App\Models\Requisicao;
use App\Models\Servico;
use App\Models\User;
use App\Models\ValorServico;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Matrix\Operators\Subtraction;

class ReportFinanceiroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function reportFinanceiro(Request $request){   

        $this->authorize('admin');

        $dataForm = $request->all();

        $tecnicos = User::where('nv_acesso','tecnico')->where('status','ativo')->orderBy('name','ASC')->get();

        $msg = isset($dataForm['msg']) ? $dataForm['msg'] : NULL;

        !isset($dataForm['data_ini']) ? $dataForm['data_ini'] = DATE('Y-m-d').' 00:00:00' : $dataForm['data_ini'] = $dataForm['data_ini'].' 00:00:00';
        !isset($dataForm['data_fim']) ? $dataForm['data_fim'] = DATE('Y-m-d').' 23:59:00' : $dataForm['data_fim'] = $dataForm['data_fim'].' 23:59:00';

        $dataIni =  date( 'd/m/Y', strtotime($dataForm['data_ini']) );
        $dataFim =  date( 'd/m/Y', strtotime($dataForm['data_fim']) );

        //datas em formato DateTime
        $dt_ini = DateTime::createFromFormat('d/m/Y',$dataIni);
        $dt_fim = DateTime::createFromFormat('d/m/Y',$dataFim);
        
        $mes = $dt_ini->format('m');                            
        $ano = $dt_ini->format('Y');

        $difDataIniFim = date_diff($dt_ini,$dt_fim);

        $hoje = Date('d/m/Y');
        $hoje = DateTime::createFromFormat('d/m/Y',$hoje);
        
        if ($hoje < $dt_fim){
            $difDataHojeFim = date_diff($dt_ini,$dt_fim);
            $difDataHojeFim = $difDataHojeFim->days;
        }else{
            $difDataHojeFim = 0;
        }

        $custo = Custo::where('mes',$mes)->where('ano',$ano)->first();

        if (empty($custo)){
            return redirect()
            ->route('home')
            ->with(['msg'   =>  'Valores de Custo não Cadastrados Mês:'.$mes.' Ano:'.$ano,
                    'type'  =>  'warning']);
        }


        $metas = Meta::where('mes',$mes)->where('ano',$ano)->orderBy('meta','asc')->get();

        $qtdTecnicos =  User::where('status','ativo')->where('nv_acesso','tecnico')->distinct('name')->count('name');

        $servicos = Servico::all(); 

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

        //retorna Obj
        $ordens = DB::select($sql);

        //Converte para array
        $ordens = json_decode(json_encode($ordens), true);

        $ordensFinalizadas = DB::select($sql.' AND status = "finalizada" ');

        //Converte para array
        $ordensFinalizadas = json_decode(json_encode($ordensFinalizadas), true);

        //grafico Ordens

        $ordensByServicos = [];
        foreach ($ordensFinalizadas as $ordem) {

            $mes = date( 'm', strtotime($ordem['dt_abertura']) );
            $ano = date( 'Y', strtotime($ordem['dt_abertura']) );

            $valorServico = ValorServico::where('id_servico',$ordem['id_servico_tipo'])->where('mes',$mes)->where('ano',$ano)->first();

            if (empty($valorServico)){
                return redirect()
                ->route('home')
                ->with(['msg'   =>  'Valores de Servicos('.$ordem['id_servico_tipo'].') não Cadastrados Mês:'.$mes.' Ano:'.$ano,
                        'type'  =>  'warning']);
            }

            !isset(${'totalValorServ'.$ordem['id_servico_tipo']}) ? ${'totalValorServ'.$ordem['id_servico_tipo']} = 0 : '';
            ${'totalValorServ'.$ordem['id_servico_tipo']} = ${'totalValorServ'.$ordem['id_servico_tipo']} + $valorServico['valor'];

            
            !isset(${'qtdServ'.$ordem['id_servico_tipo']}) ? ${'qtdServ'.$ordem['id_servico_tipo']} = 0 : '';
            ${'qtdServ'.$ordem['id_servico_tipo']} = ${'qtdServ'.$ordem['id_servico_tipo']} + 1;
            
            $ordensByServicosTmp = [
                'qtdServ'.$ordem['id_servico_tipo'] => [
                    'id_servico_tipo' => $ordem['id_servico_tipo'],
                    'qtd' => ${'qtdServ'.$ordem['id_servico_tipo']},
                    'valor' => ${'totalValorServ'.$ordem['id_servico_tipo']},
                ]
            ];
            
            $ordensByServicos = array_merge($ordensByServicos, $ordensByServicosTmp);

        }

        // Grafico valor por servico
        $legendaValorServicos = "[";
        $valoresValorServicos = "[";        
        foreach ($ordensByServicos as $ordensByServico) {
            $servico = Servico::where('id',$ordensByServico['id_servico_tipo'])->first();

            $x = number_format($ordensByServico['valor'],2,",",".");

            $legendaValorServicos .= "'".$servico['nome']." (R$ ".$x.")',";
            $valoresValorServicos .= $ordensByServico['valor'].",";
        }

        $legendaValorServicos .= "]";
        $valoresValorServicos .= "]";

        //Grafico de valores de servicos ------------------------------------------ FIM

        $qtdTotal = 0;
        $valorTotal = 0;
        $valorDia = 0;
        $previsao = 0;

        foreach ($ordensByServicos as $ordensByServico) {

            $valorTotal = $valorTotal + $ordensByServico ['valor'];

            $qtdTotal = $qtdTotal + $ordensByServico ['qtd'];
        }
        
        $difDataIniFim->days > 0 ? $valorDia = $valorTotal / $difDataIniFim->days : $valorDia = $valorTotal;
        
        $previsao = $valorTotal + ($valorDia * $difDataHojeFim);

        $custoTotal = $custo->valor * $qtdTecnicos;

        if ($previsao > $custoTotal){
            $colorGap = 'success';
            $iconGap = 'fa-thumbs-up';
        }else{
            $colorGap = 'danger';
            $iconGap = 'fa-thumbs-down';
        }

        $gap = $previsao - $custoTotal;

        $gap    = number_format($gap,2,",",".");
        $valorTotal = number_format($valorTotal,2,",",".");        
        $previsao = number_format($previsao,2,",",".");
        $custoTotal = number_format($custoTotal,2,",",".");

       
        //############### tblTecnicos
        $ordensFinalizadasPorTecnicos = [];
        foreach ($ordensFinalizadas as $ordemFinalizada) {

            //############## OBS
            // Acertar para pegar valores do mes que ocorreu e nao do pesquisado 
            $valorServico = ValorServico::where('id_servico',$ordemFinalizada['id_servico_tipo'])->where('mes',$mes)->where('ano',$ano)->first();

            if (empty($valorServico)){
                return redirect()
                ->route('home')
                ->with(['msg'   =>  'Valores de Servicos ('.$ordemFinalizada['id_servico_tipo'].') não Cadastrados Mês:'.$mes.' Ano:'.$ano,
                        'type'  =>  'warning']);
            }

            $tecnico = User::find($ordemFinalizada['id_user_tec']);

            !isset(${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']}) ? ${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']} = 0 : '';
            ${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']} = ${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']} + 1;

            !isset(${'tec'.$ordemFinalizada['id_user_tec'].'total'}) ? ${'tec'.$ordemFinalizada['id_user_tec'].'total'} = 0 : '';
            ${'tec'.$ordemFinalizada['id_user_tec'].'total'} = ${'tec'.$ordemFinalizada['id_user_tec'].'total'} + $valorServico->valor;

            $ordensFinalizadasPorTecnicosTmp[$tecnico->name] = [
                'id_tec' => $ordemFinalizada['id_user_tec'],
                'total' => ${'tec'.$ordemFinalizada['id_user_tec'].'total'},
                'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo'] => ${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']},
            ];

            $ordensFinalizadasPorTecnicos = array_merge_recursive_distinct($ordensFinalizadasPorTecnicos, $ordensFinalizadasPorTecnicosTmp);

        }


        $tblTecnicos = [];
        foreach ($ordensFinalizadasPorTecnicos as $keyOrdensFinalizadasPorTecnicos => $ordensFinalizadasPorTecnico) {
            
            $liquido = $ordensFinalizadasPorTecnico['total'] - $custo->valor;

            $valUmPonto = $custo->valor / 1000;

            $tecnicoPontos = $ordensFinalizadasPorTecnico['total'] / $valUmPonto;

            $taxaComissao = 0;
            //identifica em qual faixa esta
            foreach ($metas as $keyMetas => $valueMetas) {
                
                $valorMeta = $custo->valor + ($custo->valor * ($valueMetas->meta /100));

                if ($valorMeta < $ordensFinalizadasPorTecnico['total']){
                    $taxaComissao = $valueMetas->comissao / 100;
                }
            }

            $valComissao = $ordensFinalizadasPorTecnico['total'] * $taxaComissao;
            
            $tblTecnicosTmp[$keyOrdensFinalizadasPorTecnicos] = [
                'BRUTO' =>  'R$'.number_format($ordensFinalizadasPorTecnico['total'],2,",","."),
                'LIQUIDO' =>  'R$'.number_format($liquido,2,",","."),
                'PONTOS' => number_format($tecnicoPontos,0,",","."),
                'COMISSAO' => 'R$'.number_format($valComissao,2,",","."),
            ];

            $tblTecnicos = array_merge_recursive_distinct($tblTecnicos, $tblTecnicosTmp);
            foreach ($ordensFinalizadasPorTecnico as $key => $value) {
                
                //pega o numero do id do servico
                $id_servico = substr($key, -2);
                is_numeric($id_servico) ? '' : $id_servico = substr($key, -1);   

                //controle para teste
                
                foreach ($servicos as $servico) {

                    // $servico->id == $id_servico ? '' : $value = 11 ;

                    if ($servico->id != $id_servico){
                        $valor = 0;
                    }else{
                        $valor = $value;
                    }

                    !isset($tblTecnicos[$keyOrdensFinalizadasPorTecnicos][$servico->nome])? $tblTecnicos[$keyOrdensFinalizadasPorTecnicos][$servico->nome] = 0 :'';

                    $tblTecnicosTmp[$keyOrdensFinalizadasPorTecnicos] = [
                        $servico->nome => $tblTecnicos[$keyOrdensFinalizadasPorTecnicos][$servico->nome]+$valor,
                    ];

                    $tblTecnicos =array_merge_recursive_distinct($tblTecnicos, $tblTecnicosTmp);
                }
            }
        }
        //############### FIM tblTecnicos


        return view('app.report.reportFinanceiro')->with([  'msg'       => $msg,
                                                            'dataIni'   => $dataIni,
                                                            'dataFim'   => $dataFim,  
                                                            'legendaValorServicos'      => $legendaValorServicos,
                                                            'valoresValorServicos'      => $valoresValorServicos,
                                                            'valorTotal'        => $valorTotal,
                                                            'previsao'  => $previsao,
                                                            'qtdTotal'      => $qtdTotal,
                                                            'tblTecnicos'   => $tblTecnicos,
                                                            'colorGap'  => $colorGap,
                                                            'iconGap' => $iconGap,
                                                            'gap'   => $gap,
                                                            'qtdTecnicos' => $qtdTecnicos,
                                                            'custoTotal' => $custoTotal,
                                                            
                                                        ]);
    }
}
