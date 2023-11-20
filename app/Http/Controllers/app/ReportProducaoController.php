<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Custo;
use App\Models\Servico;
use App\Models\User;
use App\Models\ValorServico;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportProducaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function reportProducao(Request $request){

        $this->authorize('controle_supervisor_apoio');

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

        $dif_dias = date_diff($dt_ini, $dt_fim);
        
        $mes = $dt_ini->format('m');                            
        $ano = $dt_ini->format('Y');

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

        //############### ordens por status Grafico1
        $ordensByStatus = [];
        foreach ($ordens as $ordem) {
            
            !isset(${'qtdServ'.$ordem['status']}) ? ${'qtdServ'.$ordem['status']} = 0 : '';
            ${'qtdServ'.$ordem['status']} = ${'qtdServ'.$ordem['status']} + 1;
            
            $ordensByStatusTmp = [
                'qtdServ'.$ordem['status'] => ${'qtdServ'.$ordem['status']},
            ];

            $ordensByStatus = array_merge($ordensByStatus, $ordensByStatusTmp);

        }

        // Grafico valor por servico
        $ordensByStatusLegenda = "[";
        $ordensByStatusValores = "[{data: [";    
        foreach ($ordensByStatus as $ordensByStatusKey => $ordensByStatusValue) {

            $legenda =  strtoupper(substr($ordensByStatusKey, 7));

            $valor = number_format($ordensByStatusValue,0,",",".");

            $ordensByStatusLegenda .= "'".$legenda." (".$valor.")',";
            $ordensByStatusValores .= $valor.",";
        }

        $ordensByStatusLegenda .= "]";
        $ordensByStatusValores .= "],backgroundColor : ['#c91919', '#00a65a', '#3c8dbc', '#00c0ef', '#f39c12', '#f56954', '#f58054', '#f91954'],}]";

        //############### FIM ordens por status Grafico1

        //############### tblTecnicos
        $ordensFinalizadasPorTecnicos = [];
        foreach ($ordensFinalizadas as $ordemFinalizada) {

            $tecnico = User::find($ordemFinalizada['id_user_tec']);

            !isset(${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']}) ? ${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']} = 0 : '';
            ${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']} = ${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']} + 1;

            !isset(${'tec'.$ordemFinalizada['id_user_tec'].'total'}) ? ${'tec'.$ordemFinalizada['id_user_tec'].'total'} = 0 : '';
            ${'tec'.$ordemFinalizada['id_user_tec'].'total'} = ${'tec'.$ordemFinalizada['id_user_tec'].'total'} + 1;

            !isset(${'tec'.$ordemFinalizada['id_user_tec'].'media'} ) ? ${'tec'.$ordemFinalizada['id_user_tec'].'media'} = 0 : '';
            ${'tec'.$ordemFinalizada['id_user_tec'].'media'} = ${'tec'.$ordemFinalizada['id_user_tec'].'total'} / $dif_dias->days;

            $ordensFinalizadasPorTecnicosTmp[$tecnico->name] = [
                'id_tec' => $ordemFinalizada['id_user_tec'],
                'total' => ${'tec'.$ordemFinalizada['id_user_tec'].'total'},
                'media' => ${'tec'.$ordemFinalizada['id_user_tec'].'media'},
                'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo'] => ${'tec'.$ordemFinalizada['id_user_tec'].'serv'.$ordemFinalizada['id_servico_tipo']},
            ];

            $ordensFinalizadasPorTecnicos = array_merge_recursive_distinct($ordensFinalizadasPorTecnicos, $ordensFinalizadasPorTecnicosTmp);

        }


        $tblTecnicos = [];
        foreach ($ordensFinalizadasPorTecnicos as $keyOrdensFinalizadasPorTecnicos => $ordensFinalizadasPorTecnico) {
            
            
            $tblTecnicosTmp[$keyOrdensFinalizadasPorTecnicos] = [
                'id_tec' => $ordensFinalizadasPorTecnico['id_tec'],
                'QTD TOTAL' => $ordensFinalizadasPorTecnico['total'],
                'MED. SERVICOS' => number_format($ordensFinalizadasPorTecnico['media'], 2, '.', ' '),
            ];

            $tblTecnicos = array_merge_recursive_distinct($tblTecnicos, $tblTecnicosTmp);
            foreach ($ordensFinalizadasPorTecnico as $key => $value) {
                
                //pega o numero do id do servico
                $id_servico = substr($key, -2);
                is_numeric($id_servico) ? '' : $id_servico = substr($key, -1);   

                if (is_numeric($id_servico)){    
                    
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
        }
        //############### FIM tblTecnicos

        return view('app.report.reportProducao')->with([    'msg'       => $msg,
                                                        'dataIni'   => $dataIni,
                                                        'dataFim'   => $dataFim,
                                                        'dt_ini'    => $dt_ini->format('Y-m-d'),
                                                        'dt_fim'    => $dt_fim->format('Y-m-d'),
                                                        'tecnicos'  => $tecnicos,
                                                        'tblTecnicos' => $tblTecnicos,
                                                        'ordensByStatusValores' => $ordensByStatusValores,
                                                        'ordensByStatusLegenda' => $ordensByStatusLegenda,
                                                    ]);
                                                
    }

}
