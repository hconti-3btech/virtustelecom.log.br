<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Custo;
use App\Models\Indicador;
use App\Models\Meta;
use App\Models\Ordem;
use App\Models\Pontos;
use App\Models\Regra;
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

        if (!isset($dataForm['tecnico'])){
            $dataForm['tecnico'] = 1;
            $nomeTec = 'NOME DO TÉCNICO';
            $idTec = 1;
        }else{
            $tec = User::find($dataForm['tecnico']);
            $nomeTec = $tec->name;
            $idTec = $tec->id;
        }    

        !isset($dataForm['ano']) ? $dataForm['ano'] = DATE('Y') : '';

        if (!isset($dataForm['mes'])){
            $dataIni = DATE($dataForm['ano'].'-m-01').' 00:00:00';
            $dataFim = DATE($dataForm['ano'].'-m-t').' 23:59:00';
            $mes = DATE('m');
            $ano = DATE('Y');
            $dataFim = date("Y-m-t", strtotime($ano."-".$mes."-01")).' 23:59:00';
        }else{
            $dataIni = DATE($dataForm['ano'].'-'.$dataForm['mes'].'-01').' 00:00:00';
            //$dataFim = DATE($dataForm['ano'].'-'.$dataForm['mes'].'-t').' 23:00:00';
            $dataFim = date("Y-m-t", strtotime($dataForm['ano']."-".$dataForm['mes']."-01")).' 23:59:00';
        }

        //datas em formato DateTime
        $dt_ini = DateTime::createFromFormat('Y-m-d H:i:s',$dataIni);
        $dt_fim = DateTime::createFromFormat('Y-m-d H:i:s',$dataFim);

        //pega a tabela pontos
        $pontos = Pontos::get();
        foreach ($pontos as $key => $ponto) {

            $x = Servico::find($ponto->id_servico);
            $ponto->servico = $x->nome;
        }

        //pega a tabela regras
        $regras = Regra::orderBy('qtd_pontos')->get();

        //pega todos servicos e add 0 a qtdServico
        $servicos = Servico::get();
        foreach ($servicos as $key => $servico) {
            $servico->qtdServico = 0;
        }

        //seta total de pontos como 0 inicialmente
        $totalPontos = 0;

        //seta total de pontos com 0 inicialmente
        $totalReceber = 0.00;

        //defino que o tecnico so pode ver sua propria producao
        if($user->nv_acesso == 'tecnico'){
            $dataForm['tecnico'] = $user->id;
            $tecnicos = User::where('id',$user->id)->where('status','ativo')->orderBy('name','asc')->get();
        }else{
            $tecnicos = User::where('nv_acesso','tecnico')->where('status','ativo')->orderBy('name','asc')->get();
        }

        if (isset($dataForm['tecnico'])){

            $ordensTecnico = Ordem::where('id_user_tec',$dataForm['tecnico'])->where('created_at','>=',$dt_ini->format('Y-m-d H:i:s'))->where('created_at','<=',$dt_fim->format('Y-m-d H:i:s'))->where('status','finalizada')->get();

            

            

            foreach ($ordensTecnico as $key => $ordenTecnico) {
                
                //contabiliza qtd total de servicos 
                foreach ($servicos as $key => $servico) {

                    if ($servico->id ==  $ordenTecnico->id_servico_tipo){

                        $servico->qtdServico = $servico->qtdServico + 1;

                    }

                }
                
            }
        }

        //pega informacoes dos indicadores
        $somaIndicadores = 0;
        $indicadores = Indicador::where('ano',$dt_ini->format('Y'))->where('mes',$dt_ini->format('m'))->where('id_user_tec',$dataForm['tecnico'])->get();
        foreach ($indicadores as $indicador){
            $pontosIndicado = Pontos::where('id_servico',$indicador->id_servico_tipo)->first();
            $indicador->qtdPontos = $pontosIndicado->pontos * $indicador->qtd;
            
            $somaIndicadores = $somaIndicadores + $indicador->qtdPontos;

            $servicoIndicado = Servico::find($indicador->id_servico_tipo);
            $indicador->servico = $servicoIndicado->nome;
        }

        //remover servico com qtdServico = 0 e multiplicar pela quatidade de pontos 
        foreach ($servicos as $key => $servico) {
            if ($servico->qtdServico == 0){
                unset($servicos[$key]);
            }else{

                foreach ($pontos as $key => $ponto) {
                    
                    if ($ponto->id_servico == $servico->id){
                        $servico->qtdPontos = $servico->qtdServico * $ponto->pontos;
                    
                        $totalPontos = $totalPontos + $servico->qtdPontos;
                    }
                    
                }
                
            }
        }

        //subtrai os valores de indicadores 
        $totalPontos = $totalPontos + $somaIndicadores;

        foreach ($regras as $key => $regra) {
            
            if ($regra->qtd_pontos <= $totalPontos){
                $totalReceber = $totalPontos * $regra->valor;
            }
        }

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $dataExtenso = strftime('%A, %d de %B de %Y', strtotime('today'));


        return view('app.report.reportComissao')->with([   
            'msg'       => $msg,
            'dt_ini'   => $dt_ini,
            'dt_fim'   => $dt_fim,
            'tecnicos'  => $tecnicos,
            'servicos'  => $servicos,
            'pontos'    => $pontos,
            'regras'    => $regras,
            'totalPontos' => $totalPontos,
            'totalReceber'   => $totalReceber,
            'nomeTec'   => $nomeTec,
            'idTec'     => $idTec,
            'dataExtenso'   => $dataExtenso,
            'indicadores' => $indicadores,
        ]);

        

    }
}
