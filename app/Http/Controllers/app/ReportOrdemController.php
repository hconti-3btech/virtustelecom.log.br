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

class ReportOrdemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function reportOrdem(Request $request){   

        //################################# Constantes INICIO

        $this->authorize('controle_supervisor_apoio');

        $dataForm = $request->all();

        $url = $_SERVER["REQUEST_URI"];

        $msg = isset($dataForm['msg']) ? $dataForm['msg'] : NULL;

        !isset($dataForm['data_ini']) ? $dataForm['data_ini'] = DATE('Y-m-d').' 00:00:00' : $dataForm['data_ini'] = $dataForm['data_ini'].' 00:00:00';
        !isset($dataForm['data_fim']) ? $dataForm['data_fim'] = DATE('Y-m-d').' 23:59:00' : $dataForm['data_fim'] = $dataForm['data_fim'].' 23:59:00';

        $dataIni =  date( 'd/m/Y', strtotime($dataForm['data_ini']) );
        $dataFim =  date( 'd/m/Y', strtotime($dataForm['data_fim']) );

        //datas em formato DateTime
        $dt_ini = DateTime::createFromFormat('d/m/Y',$dataIni);
        $dt_fim = DateTime::createFromFormat('d/m/Y',$dataFim);


        //Condicoes SQL
        $condicoes = [
            isset($dataForm['data_ini']) ? "dt_abertura >= '" .$dataForm['data_ini']. "'"  : NULL,
            isset($dataForm['data_fim']) ? "dt_abertura <= '" .$dataForm['data_fim']. "'"  : NULL,
        ];

        $condicoes = array_filter($condicoes);

        //Clausulas
        $where = implode (' AND ',$condicoes);

        //Pega todas hordens da condicao
        $sql = "SELECT * FROM `ordems` WHERE $where ";
        $ordens = DB::select($sql); //retorna Obj
        $ordens = json_decode(json_encode($ordens), true);//Converte para array


        //pega so as finalizadas
        $ordensFinalizadas = DB::select($sql." AND status = 'finalizada' "); //retorna Obj
        $ordensFinalizadas = json_decode(json_encode($ordensFinalizadas), true);//Converte para array

        $servicos = Servico::all();
        
        //################################# Constantes FIM

        //Cria array com os tipos de servico contidos no array
        $tiposServicoArrayOrdens = [];
        foreach ($ordens as $ordem) {

            
            $tiposServicoArrayOrdensTmp = [
                'Serv'.$ordem['id_servico_tipo'] => $ordem['id_servico_tipo'],
            ];
            
            $tiposServicoArrayOrdens = array_merge($tiposServicoArrayOrdens, $tiposServicoArrayOrdensTmp);
        }

        //Grafico de qtd de servicos ------------------------------------------ INICIO
        $sql = "SELECT id_servico_tipo, COUNT(*) as qtd FROM `ordems` WHERE $where GROUP BY id_servico_tipo;";

        //retorna Obj
        $ordensByServico = DB::select($sql);

        //Converte para array
        $ordensByServico = json_decode(json_encode($ordensByServico), true);

        
        //Trata para apresentar na tabela
        $tabelaItens = [];

        //Cria tabelaItens
        $i = 0;
        foreach ($ordensByServico as $ordemByServico) {

            $servico = Servico::where('id',$ordemByServico['id_servico_tipo'])->first();

            $tabelaItens[$i] = [
                'servico'            =>  $servico['nome'],
                'qtd'   =>  $ordemByServico['qtd'],
            ];

            $i++;
        }

        //ordens por qtd servico
        $ordensByQtdServico = $tabelaItens;

        // Grafico qtd por servico
        $legendaQtdServicos = "[";
        $valoresQtdServicos = "[{data: [";
        
        for ($i=0; $i < count($ordensByQtdServico); $i++) { 
            $legendaQtdServicos .= "'".$ordensByQtdServico[$i]['servico']." (".$ordensByQtdServico[$i]['qtd'].")',";
            $valoresQtdServicos .= $ordensByQtdServico[$i]['qtd'].",";
        }

        $legendaQtdServicos .= "]";
        $valoresQtdServicos .= "],backgroundColor : ['#c91919', '#00a65a', '#3c8dbc', '#00c0ef', '#f39c12', '#f56954', '#f58054', '#f91954'],}]";

        //Grafico de qtd de servicos ------------------------------------------ FIM

        //Grafico de valores de servicos Retorno Financeiro ------------------------------------------ INICIO


        $ordensByServicos = [];
        foreach ($ordens as $ordem) {

            $mes = date( 'm', strtotime($ordem['dt_abertura']) );
            $ano = date( 'Y', strtotime($ordem['dt_abertura']) );

            $valorServico = ValorServico::where('id_servico',$ordem['id_servico_tipo'])->where('mes',$mes)->where('ano',$ano)->first();

            if (empty($valorServico)){
                return redirect()
                ->route('home')
                ->with(['msg'   =>  'Valores de Servicos ('.$ordem['id_servico_tipo'].') não Cadastrados Mês:'.$mes.' Ano:'.$ano,
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
        
        $qtdTotal = 0;
        $valorTotal = 0;

        foreach ($ordensByServicos as $ordensByServico) {
            

            $valorTotal = $valorTotal + $ordensByServico ['valor'];

            $qtdTotal = $qtdTotal + $ordensByServico ['qtd'];
        }


        // Grafico valor por servico
        $legendaValorServicosRetorno = "[";
        $valoresValorServicosRetorno = "[{data: [";
        
        foreach ($ordensByServicos as $ordensByServico) {
            $servico = Servico::where('id',$ordensByServico['id_servico_tipo'])->first();

            $porcentagem = ($ordensByServico['valor'] * 100) / $valorTotal;
            $porcentagem = number_format($porcentagem, 2, '.', ' ');

            $legendaValorServicosRetorno .= "'".$servico['nome']." (".$porcentagem."%)',";
            $valoresValorServicosRetorno .= $porcentagem.",";
        }

        $legendaValorServicosRetorno .= "]";
        $valoresValorServicosRetorno .= "],backgroundColor : ['#c91919', '#00a65a', '#3c8dbc', '#00c0ef', '#f39c12', '#f56954', '#f58054', '#f91954'],}]";
    
        //Grafico de valores de servicos Retorno Financeiro ------------------------------------------ FIM

        //Grafico de Status ------------------------------------------ Inicio

        $ordensByStatus = [];
        foreach ($ordens as $ordem) {

            !isset(${'qtdServ'.$ordem['status']}) ? ${'qtdServ'.$ordem['status']} = 0 : '';
            ${'qtdServ'.$ordem['status']} = ${'qtdServ'.$ordem['status']} + 1;
            
            $ordensByStatusTmp = [
                'qtdServ'.$ordem['status'] => [
                    'status' => strtoupper($ordem['status']),
                    'qtd' => ${'qtdServ'.$ordem['status']},
                ]
            ];
            
            $ordensByStatus = array_merge($ordensByStatus, $ordensByStatusTmp);

        }

        // Grafico valor por servico
        $legendaServicosStatus = "[";
        $valoresServicosStatus = "[{data: [";
        
        foreach ($ordensByStatus as $ordensByStatu) {

            $legendaServicosStatus .= "'".$ordensByStatu['status']." (".$ordensByStatu['qtd'].")',";
            $valoresServicosStatus .= $ordensByStatu['qtd'].",";
        }

        $legendaServicosStatus .= "]";
        $valoresServicosStatus .= "],backgroundColor : ['#c91919', '#00a65a', '#3c8dbc', '#00c0ef', '#f39c12', '#f56954', '#f58054', '#f91954'],}]";

        //Grafico de Status ------------------------------------------ FIM

        //Grafico de Status PORCENTAGEM ------------------------------------------ Inicio
        
        // Grafico valor por servico
        $legendaServicosStatusPorCem = "[";
        $valoresServicosStatusPorCem = "[{data: [";
        
        foreach ($ordensByStatus as $ordensByStatu) {

            $porcentagem = ($ordensByStatu['qtd'] * 100) / $qtdTotal;
            $porcentagem = number_format($porcentagem, 2, '.');

            $legendaServicosStatusPorCem .= "'".$ordensByStatu['status']." (".$porcentagem."%)',";
            $valoresServicosStatusPorCem .= $porcentagem.",";
        }

        $legendaServicosStatusPorCem .= "]";
        $valoresServicosStatusPorCem .= "],backgroundColor : ['#c91919', '#00a65a', '#3c8dbc', '#00c0ef', '#f39c12', '#f56954', '#f58054', '#f91954'],}]";

        //Grafico de Status PORCENTAGEM------------------------------------------ FIM

        // //Tabela de servicos por tecnico ---------------------------------------- Inicio

        // $ordensByUsers = [];
        // foreach ($ordens as $ordem) {

            

        //     foreach ($tiposServicoArrayOrdens as $key => $tiposServicoArrayOrden) {

        //         !isset(${'serv/'.$ordem['id_user_tec'].'/'.$tiposServicoArrayOrden}) ? ${'serv/'.$ordem['id_user_tec'].'/'.$tiposServicoArrayOrden} = 0 : '';

        //         if ($ordem['id_servico_tipo'] == $tiposServicoArrayOrden){
        //             ${'serv/'.$ordem['id_user_tec'].'/'.$tiposServicoArrayOrden} = ${'serv/'.$ordem['id_user_tec'].'/'.$tiposServicoArrayOrden} + 1; 
        //         }   
                
        //         $ordensByUsersTmp = [
        //             'serv/'.$ordem['id_user_tec'].'/'.$tiposServicoArrayOrden =>  ${'serv/'.$ordem['id_user_tec'].'/'.$tiposServicoArrayOrden},
        //         ];

        //         $ordensByUsers = array_merge($ordensByUsers, $ordensByUsersTmp);  
                
        //     }     

        // }
        
        // $tabelaItens = [];
        // $i = 0;
        // foreach ($ordensByUsers as $key => $ordemByUser) {
            
        //     $expKey = explode('/',$key);

        //     $id_user_tec = $expKey[1];
        //     $id_servico_tipo = $expKey[2];

        //     $tecnico = User::where('id',$id_user_tec)->first();

        //     $servico = Servico::where('id',$id_servico_tipo)->first();

        //     $diasCorridosMes = date_diff($dt_ini, $dt_fim);

        //     $qtdOrdem = DB::table("ordems")
        //                 ->select("id")
        //                 ->where("id_user_tec", "=", $id_user_tec)
        //                 ->where("dt_abertura", ">=", $dt_ini)
        //                 ->where("dt_abertura", "<=", $dt_fim)
        //                 ->where("status", "=", 'finalizada')
        //                 ->get();

        //     $media = $ordemByUser / $diasCorridosMes->days ; 

        //     $mediaTodosServ = $qtdOrdem->count() / $diasCorridosMes->days;
        //     $mediaTodosServ = number_format($mediaTodosServ, 2, '.', '');

        //     //Para add um item no array fora do array do tecnico
        //     if (!isset($tabelaItens[$tecnico['name']])){
        //         $tabelaItensTemp[$i] = [
        //             $tecnico['name'] =>  [
        //                 'id_tec' => $id_user_tec,
        //                 'Media Servicos' => $mediaTodosServ,
        //             ]
        //         ];
    
        //         $tabelaItens = array_merge( $tabelaItens, $tabelaItensTemp[$i]); 
        //     }

        //     $tabelaItensTemp[$i] = [
        //         $tecnico['name'] =>  [
        //             $servico['nome'] => $ordemByUser,
        //             'Med. '.$servico['nome'] => $media,
        //         ],
                
        //     ];

        //     $tabelaItens = array_merge_recursive( $tabelaItens, $tabelaItensTemp[$i]); 
            
        //     $i++;
        // }
        // $ordensByUsers = $tabelaItens;

        

        //Tabela tipos de servico status ------------------------------------------ Inicio

        $tabelaTipoServStatus = [];
        foreach ($ordens as $ordem) {

            foreach ($servicos as $servicoKey => $servicoValue) {

                !isset(${$servicoValue->nome.'|Aberta'}) ? ${$servicoValue->nome.'|Aberta'} = 0 : '';
                !isset(${$servicoValue->nome.'|Finalizada'}) ? ${$servicoValue->nome.'|Finalizada'} = 0 : '';
                !isset(${$servicoValue->nome.'|Atribuida'}) ? ${$servicoValue->nome.'|Atribuida'} = 0 : '';
                !isset(${$servicoValue->nome.'|Informada'}) ? ${$servicoValue->nome.'|Informada'} = 0 : '';
                !isset(${$servicoValue->nome.'|Cancelada'}) ? ${$servicoValue->nome.'|Cancelada'} = 0 : '';
                !isset(${$servicoValue->nome.'|ND'}) ? ${$servicoValue->nome.'|ND'} = 0 : '';
                

                if ($servicoValue->id == $ordem['id_servico_tipo']){

                    //'aberta','finalizada','atribuida','informada','cancelada'
                    switch ($ordem['status']) {
                        case 'aberta':
                            ${$servicoValue->nome.'|Aberta'} =  ${$servicoValue->nome.'|Aberta'} + 1; 
                            break;
                        
                        case 'finalizada':
                            ${$servicoValue->nome.'|Finalizada'} = ${$servicoValue->nome.'|Finalizada'} + 1; 
                            break;
                        
                        case 'atribuida':
                            ${$servicoValue->nome.'|Atribuida'} = ${$servicoValue->nome.'|Atribuida'} + 1; 
                            break;
                        
                        case 'informada':
                            ${$servicoValue->nome.'|Informada'} =  ${$servicoValue->nome.'|Informada'} + 1; 
                            break;
                        
                        case 'cancelada':
                            ${$servicoValue->nome.'|Cancelada'} = ${$servicoValue->nome.'|Cancelada'} + 1; 
                            break;
                                        
                        default:
                            ${$servicoValue->nome.'|ND'} = ${$servicoValue->nome.'|ND'} + 1; 
                            break;
                    }
                }   
                
                $tabelaTipoServStatusTmp = [
                    $servicoValue->nome => [
                        'Aberta' =>  ${$servicoValue->nome.'|Aberta'},
                        'Finalizada' => ${$servicoValue->nome.'|Finalizada'},
                        'Atribuida' => ${$servicoValue->nome.'|Atribuida'},
                        'Informada' => ${$servicoValue->nome.'|Informada'},
                        'Cancelada' => ${$servicoValue->nome.'|Cancelada'},
                    ]

                ];

                $tabelaTipoServStatus = array_merge_recursive_distinct($tabelaTipoServStatus, $tabelaTipoServStatusTmp);
            }
        }

        //Tabela tipos de servico status ------------------------------------------ FIM

        $valorTotal = number_format($valorTotal,2,",",".");



        return view('app.report.reportOrdem')->with([   'msg'       => $msg,
                                                        'dataIni'   => $dataIni,
                                                        'dataFim'   => $dataFim,  
                                                        'legendaQtdServicos'    => $legendaQtdServicos,
                                                        'valoresQtdServicos'    => $valoresQtdServicos,
                                                        'legendaValorServicosRetorno'  => $legendaValorServicosRetorno,
                                                        'valoresValorServicosRetorno'  => $valoresValorServicosRetorno,  
                                                        'valorTotal'        => $valorTotal,
                                                        'qtdTotal'      => $qtdTotal,
                                                        'legendaServicosStatus' => $legendaServicosStatus,
                                                        'valoresServicosStatus' => $valoresServicosStatus,
                                                        'legendaServicosStatusPorCem' => $legendaServicosStatusPorCem,
                                                        'valoresServicosStatusPorCem' => $valoresServicosStatusPorCem,
                                                        // 'ordensByUsers' => $ordensByUsers,
                                                        'url' => $url,
                                                        'dt_ini' => $dt_ini->format('Y-m-d'),
                                                        'dt_fim' => $dt_fim->format('Y-m-d'),
                                                        'tabelaTipoServStatus' => $tabelaTipoServStatus,

                                                    ]);
    }
}
