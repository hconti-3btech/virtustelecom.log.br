<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Container;
use App\Models\Produto;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportUltimaPosicaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function reportUltimaPosicao(Request $request){   

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
            isset($dataForm['data_ini']) ? "created_at >= '" .$dataForm['data_ini']. "'"  : NULL,
            isset($dataForm['data_fim']) ? "created_at <= '" .$dataForm['data_fim']. "'"  : NULL,
        ];

        $condicoes = array_filter($condicoes);

        //Clausulas
        $where = implode (' AND ',$condicoes);

        //Pega todas hordens da condicao
        $sql = "SELECT * FROM `movimentos` WHERE $where ";
       
        $sql = "SELECT m1.* ";
        $sql .= "FROM movimentos m1 ";
        $sql .= "INNER JOIN ( ";
            $sql .= "SELECT controle, MAX(created_at) AS ultima_movimentacao ";
            $sql .= "FROM movimentos WHERE $where ";
            $sql .= "GROUP BY controle ";
        $sql .=") m2 ON m1.controle = m2.controle AND m1.created_at = m2.ultima_movimentacao ";

        $movimentos = DB::select($sql); //retorna Obj
        $movimentos = json_decode(json_encode($movimentos), true);//Converte para array

        $i = 0;
        $tabelaItens = [];
        foreach ($movimentos as $movimento) {

            $timeZone = new DateTimeZone('UTC');
            
            $origem = Container::where('id', $movimento['id_origem'])->first();

            $destino = Container::where('id', $movimento['id_destino'])->first();

            $produto = Produto::where('id', $movimento['id_produto'])->first();

            $data = DateTime::createFromFormat('Y-m-d H:i:s', $movimento['created_at'], $timeZone)->format('d/m/Y');

            $tabelaItens[$i] = [
                'origem'       =>  $origem['nome'],
                'destino'   =>  $destino['nome'],
                'produto'  =>  $produto['nome'],
                'qtd'  =>  $movimento['qtd'],
                'controle'  =>  $movimento['controle'],
                'mac'  =>  $movimento['mac'],
                'ordem'  =>  $movimento['ordem'],
                'data'  =>  $data,
            ];

            $i++;

        }
        
        $movimentos = $tabelaItens;


        return view('app.report.reportUltimaPosicao')->with([   
                                                        'msg'       => $msg,
                                                        'dataIni'   => $dataIni,
                                                        'dataFim'   => $dataFim,  
                                                        'movimentos'    => $movimentos,
                                                    ]);
    }
}
