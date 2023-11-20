<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\ItenContainer;
use App\Models\ItenRequisicao;
use App\Models\Movimento;
use App\Models\Produto;
use App\Models\Requisicao;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Matcher\Contains;

use function Psy\debug;

class MovimentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function viewMovimento(Request $request)
    {

        $this->authorize('almox_controle_supervisor');

        $dataForm = $request->all();

        !isset($dataForm['inpSearch']) ? $dataForm['inpSearch'] = NULL : '';
        !isset($dataForm['selCampo']) ? $dataForm['selCampo'] = NULL : '';


        if ($dataForm['inpSearch'] <> NULL) {
            !isset($dataForm['data_ini']) ? $dataForm['data_ini'] = NULL : $dataForm['data_ini'] = $dataForm['data_ini'] . ' 00:01:00';
            !isset($dataForm['data_fim']) ? $dataForm['data_fim'] = NULL : $dataForm['data_fim'] = $dataForm['data_fim'] . ' 23:59:00';
        } else {
            !isset($dataForm['data_ini']) ? $dataForm['data_ini'] = DATE('Y-m-d') . ' 00:01:00' : $dataForm['data_ini'] = $dataForm['data_ini'] . ' 00:01:00';
            !isset($dataForm['data_fim']) ? $dataForm['data_fim'] = DATE('Y-m-d') . ' 23:59:00' : $dataForm['data_fim'] = $dataForm['data_fim'] . ' 23:59:00';
        }

        //Condicoes SQL
        $condicoes = [
            isset($dataForm['inpSearch']) ? $dataForm['selCampo'] . ' LIKE "%' . str_replace(' ', '%', $dataForm['inpSearch']) . '%"' : NULL,
            isset($dataForm['data_ini']) ? "created_at >= '" . $dataForm['data_ini'] . "'"  : NULL,
            isset($dataForm['data_fim']) ? "created_at <= '" . $dataForm['data_fim'] . "'"  : NULL,
        ];

        $condicoes = array_filter($condicoes);

        //Clausulas
        $where = implode(' AND ', $condicoes);

        $oldDtIni = $dataForm['data_ini'];
        $oldDtFim = $dataForm['data_fim'];
        $oldInpSearch = $dataForm['inpSearch'];
        $oldSelCampo = $dataForm['selCampo'];

        $sql = "select * from movimentos where $where ";

        //retorna Obj
        $movimentos = DB::select($sql);

        //Converte para array
        $movimentos = json_decode(json_encode($movimentos), true);

        //Trata para apresentar na tabela
        $tabelaItens = [];

        //Cria tabelaItens
        $i = 0;
        foreach ($movimentos as $movimento) {

            $timeZone = new DateTimeZone('UTC');

            $origem = Container::where('id', $movimento['id_origem'])->first();

            $destino = Container::where('id', $movimento['id_destino'])->first();

            $produto = Produto::where('id', $movimento['id_produto'])->first();

            $dtCreatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $movimento['created_at'], $timeZone)->format('d/m/Y');

            $tabelaItens[$i] = [
                'id'       =>  $movimento['id'],
                'origem'   =>  $origem['nome'],
                'destino'  =>  $destino['nome'],
                'produto'  =>  $produto['nome'],
                'qtd'      =>  $movimento['qtd'],
                'status'   =>  $movimento['status'],
                'controle' =>  $movimento['controle'],
                'ordem'    =>  $movimento['ordem'],
                'created_at'  => $dtCreatedAt,
                'requisicao' =>$movimento['requisicao'],
            ];

            $i++;
        }

        $movimentos = $tabelaItens;

        $url = $_SERVER["REQUEST_URI"];

        return view('app.movimentos.viewMovimento')->with([
            'movimentos'    =>  $movimentos,
            'msg'           =>  $request->msg,
            'data_ini'    =>  $oldDtIni,
            'data_fim'    =>  $oldDtFim,
            'oldInpSearch' => $oldInpSearch,
            'oldSelCampo' => $oldSelCampo,
            'url'       =>  $url,
        ]);
    }

    public function addEstoque(Request $request)
    {

        $this->authorize('controle');

        $containers = Container::where('status', 'ativo')->get();

        $produtos = Produto::where('status', 'ativo')->get();

        return view('app.movimentos.addEstoque')->with([
            'containers'    =>  $containers,
            'produtos'      =>  $produtos,
            'msg'           =>  $request->msg
        ]);
    }

    public function addEstoqueSave(Request $request)
    {

        $this->authorize('controle');

        $user = Auth::user();

        $request->validate([
            'id_destino' => 'required|integer',
            'produto' => 'required',
        ]);

        if (empty($request->produto[0])) {
            return redirect()
                ->route('viewMovimento')
                ->with([
                    'msg'   =>  'É necessario adicionar um produto almenos',
                    'type'  =>  'warning'
                ]);
        }

        count($request->produto) == 0 ? $qtdProdutos = 1 : $qtdProdutos = count($request->produto);

        for ($i = 0; $i < $qtdProdutos; $i++) {

            $itenContainer = ItenContainer::where('id_container', $request->id_destino)->where('id_produto', $request->produto[$i])->where('controle', $request->controle[$i])->first();

            if (!empty($itenContainer)) {

                if ($request->qtd[$i] < 0) {
                    return redirect()
                        ->route('viewMovimento')
                        ->with([
                            'msg'   =>  'Produto ' . $request->produto[$i] . ' com quantidade 0 ou negativa',
                            'type'  =>  'warning'
                        ]);
                }

                $qtd = $itenContainer->qtd + $request->qtd[$i];

                $itenContainer->update([
                    'qtd' => $qtd,
                ]);
            } else {

                if (empty($request->produto[$i])) {
                    return redirect()
                        ->route('viewMovimento')
                        ->with([
                            'msg'   =>  'Produto vazio',
                            'type'  =>  'warning'
                        ]);
                }

                if (empty($request->qtd[$i]) || $request->qtd[$i] < 1) {
                    return redirect()
                        ->route('viewMovimento')
                        ->with([
                            'msg'   =>  'Produto' . $$request->produto[$i] . ' com quantidade vazia ou menor q 0',
                            'type'  =>  'warning'
                        ]);
                }

                $itenContainer = new ItenContainer();

                $itenContainer->id_container    = $request->id_destino;
                $itenContainer->id_produto      = $request->produto[$i];
                $itenContainer->qtd             = $request->qtd[$i];
                $itenContainer->controle        = $request->controle[$i]  == '' ? NULL : strtoupper($request->controle[$i]);
                $itenContainer->mac             = strtoupper($request->mac[$i]);

                $itenContainer->save();
            }

            if (empty($request->produto[0])) {
                return redirect()
                    ->route('viewMovimento')
                    ->with([
                        'msg'   =>  'É necessario adicionar um produto almenos',
                        'type'  =>  'warning'
                    ]);
            }

            $movimento = new Movimento;

            //cadastra Movimento
            $movimento->id_origem   = 2;
            $movimento->id_destino  = $request->id_destino;
            $movimento->id_produto  = $request->produto[$i];
            $movimento->qtd         = $request->qtd[$i];
            $movimento->controle    = $request->controle[$i]  == '' ? NULL : strtoupper($request->controle[$i]);
            $movimento->mac    = strtoupper($request->mac[$i]);
            $movimento->solicitante = $user->id;
            $movimento->aprovante   = $user->id;
            $movimento->status = 'Confirmado';

            $movimento->save();
        }


        return redirect()
            ->route('viewMovimento')
            ->with([
                'msg'   =>  'Movimento inserido',
                'type'  =>  'success'
            ]);
    }

    public function requisicaoMovimento(Request $request)
    {
        $this->authorize('almox_controle_supervisor');

        $requisicao = Requisicao::where('id', $request->requisicao)->first();

        $msg = explode("|",$request->msg);

        $msg = array_filter($msg);
        
        $itenRequisicoes = ItenRequisicao::where('id_requisicao', $requisicao->id)->get();

        $origem = Container::where('id', $requisicao->id_origem)->first();
        $destino = Container::where('id', $requisicao->id_destino)->first();

        $requisicao = [
            'id' => $requisicao->id,
            'id_origem' => $requisicao->id_origem,
            'id_destino' => $requisicao->id_destino,
            'origem' => $origem->nome,
            'destino' => $destino->nome,
        ];

        $i = 0;
        foreach ($itenRequisicoes as $itenRequisicao) {

            $produto = Produto::where('id', $itenRequisicao->id_produto)->first();

            $tabelaItens[$i] = [
                'id'            =>  $itenRequisicao->id,
                'id_requisicao'   => $itenRequisicao->id_requisicao,
                'id_produto'   => $itenRequisicao->id_produto,
                'produto'  =>  $produto->nome,
                'unico'  =>  $produto->unico,
                'qtd'  =>  $itenRequisicao->qtd,
            ];

            $i++;
        }

        $itenRequisicoes = $tabelaItens;


        return view('app.movimentos.requisicaoMovimento')
            ->with([
                'itenRequisicoes'    =>  $itenRequisicoes,
                'requisicao' => $requisicao,
                'msg' => $msg,
            ]);
    }

    public function requisicaoMovimentoSave(Request $request, $msg = NULL)
    {   

        $this->authorize('almox_controle_supervisor');
        
        $regras = [
            'qtd.*' => 'required|numeric',
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatorio',
            'numeric' => 'O valor de :attribute precisa ser numerico'
        ];

        $request->validate($regras,$feedback);

        $dataForm = $request->all();

        $user = Auth::user();

        $requisicao = Requisicao::where('id', $dataForm['id'])->first();

        if (empty($requisicao)){

            return redirect()
                ->route('viewRequisicao')
                ->with([
                    'msg'   =>  'Requisicao('.$dataForm['id'].') não existe',
                    'type'  =>  'warning'
                ]);
        }


        $origem = Container::find($dataForm['id_origem']);
        if (empty($origem)){

            return redirect()
                ->route('viewRequisicao')
                ->with([
                    'msg'   =>  'Origem('.$dataForm['id_origem'].') não existe',
                    'type'  =>  'warning'
                ]);
        }

        $destino = Container::find($dataForm['id_destino']);
        if (empty($destino)){

            return redirect()
                ->route('viewRequisicao')
                ->with([
                    'msg'   =>  'Origem('.$dataForm['id_destino'].') não existe',
                    'type'  =>  'warning'
                ]);
        }


        $msg = '';
        //loop verifica estoque
        for ($i = 0; $i < count($dataForm['produto']); $i++) {

            if ($dataForm['qtd'][$i] <> 0){
                $produto = Produto::find($dataForm['produto'][$i]); 

                $itenContainerOrigem = ItenContainer::where('id_container',$origem->id)->where('id_produto',$produto->id)->where('controle',$dataForm['controle'][$i])->first();

                if (empty($itenContainerOrigem)){
                    $msg = $msg."|"."Produto (".$produto->nome.") com Controle (".$dataForm['controle'][$i].") nao existe na Origem (".$origem->nome.")";
                    

                }else{

                    $verificaQtd = $itenContainerOrigem->qtd - $dataForm['qtd'][$i];
                    if ($verificaQtd < 0 ){
                        $msg = $msg."|"."Produto (".$produto->nome.") com Controle (".$dataForm['controle'][$i].") com quantidade insuficiente";
                        
                    }

                }           
            }
        }

        //verificar se todos itens existem e tem quantidade na origem
        if (empty($msg)){
            
            for ($i = 0; $i < count($dataForm['produto']); $i++) {

                //verificar se qtd do produto é <> 0 (para desconsiderar caso for 0)
                if ($dataForm['qtd'][$i] <> 0){

                    $produto = Produto::find($dataForm['produto'][$i]); 

                    $itenContainerOrigem = ItenContainer::where('id_container',$origem->id)->where('id_produto',$produto->id)->where('controle',$dataForm['controle'][$i])->first();

                    $itenContainerDestino = ItenContainer::where('id_container',$destino->id)->where('id_produto',$produto->id)->where('controle',$dataForm['controle'][$i])->first();

                    $verificaQtd = $itenContainerOrigem->qtd - $dataForm['qtd'][$i];               

                    //adicionar no destino
                    //verificar se o item ja existe no destino se sim atualizar
                    if (empty($itenContainerDestino)){
                        
                        $itenContainerDestino = new ItenContainer();

                        $itenContainerDestino->id_container = $destino->id;
                        $itenContainerDestino->id_produto = $dataForm['produto'][$i];
                        $itenContainerDestino->qtd = $dataForm['qtd'][$i];
                        $itenContainerDestino->controle = $dataForm['controle'][$i]  == '' ? NULL : strtoupper($dataForm['controle'][$i]);
                        $itenContainerDestino->mac = $itenContainerOrigem->mac;
                        $itenContainerDestino->save();

                    }else{  //caso nao exista no destino adicionar

                        $qtdDestino = $itenContainerDestino->qtd + $dataForm['qtd'][$i];

                        $itenContainerDestino->update([
                            'qtd' => $qtdDestino,
                        ]);

                    }
                    
                    //verificar se origem = 2 (entrada) nao faz nada na origem
                    if ($origem->id <> 2){
                        //remover da origem caso = 0 deletar da origem 
                        if ($verificaQtd == 0){
                            $itenContainerOrigem->delete();
                        }else{ //caso > 0 atualizar 

                            $dataForm['controle'][$i] == '' ? $dataForm['controle'][$i] = NULL : '';

                            $itenContainerOrigem->update([
                                'qtd' => $verificaQtd,
                                'controle' => $dataForm['controle'][$i],
                            ]);
                        }
                    }

                    //cria movimento
                    $movimento = new Movimento();

                    $movimento->id_origem = $dataForm['id_origem'];
                    $movimento->id_destino = $dataForm['id_destino'];
                    $movimento->id_produto = $dataForm['produto'][$i];
                    $movimento->qtd = $dataForm['qtd'][$i];
                    $movimento->controle = $dataForm['controle'][$i];
                    $movimento->mac = empty($itenContainerOrigem->mac) ? '' : $itenContainerOrigem->mac;
                    $movimento->solicitante = $user->id;
                    $movimento->aprovante = $user->id;
                    $movimento->ordem = '';
                    $movimento->requisicao = $requisicao->id;
                    $movimento->status = 'CONFIRMADO';
                    $movimento->save();

                    //gera relatorio
                    $relatorio['materiais'][$i] = [
                        'nome' => $produto->nome,
                        'un' => $produto->un,
                        'qtd' => $dataForm['qtd'][$i],
                        'controle' => $dataForm['controle'][$i],
                    ];
                }
            }
            
        }else{//informa erros
            return redirect()
            ->route('requisicaoMovimento',['requisicao'   =>  $requisicao->id, 'msg' => $msg]);
        }    

        //atualiza status da requisicao            
        $requisicao->update([
            'status' => 'CONFIRMADO'
        ]);



        //gera relatorio
        $origem = Container::find($dataForm['id_origem']);
        $origem = User::find($origem->id_user);

        $destino = Container::find($dataForm['id_destino']);
        $destino = User::find($destino->id_user);

        $relatorio['origem'] = [
            'nome' => $origem->name,
        ];

        $relatorio['destino'] = [
            'nome' => $destino->name,
        ];

        $relatorio['requisicao'] = [
            'numero' => $requisicao->id,
        ];

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $dataExtenso = strftime('%A, %d de %B de %Y', strtotime('today'));

        return view('app.movimentos.comprovante')
            ->with([
                'relatorio' => $relatorio,
                'dataExtenso' => $dataExtenso,
                'msg'   =>  'Movimento Criado com Sucesso',
                'type'  =>  'success'
            ]);

            
        //--------------------OLD

        // $this->authorize('almox_controle_supervisor');

        // $dataForm = $request->all();

        // $user = Auth::user();

        // $requisicao = Requisicao::where('id', $dataForm['id'])->first();

        // $msg = '';
        // //verificar se tem estoque 
        // for ($i = 0; $i < count($dataForm['produto']); $i++) {

        //     //para definor se foi cancelado ou nao
        //     $status = 'CONFIRMADO';

        //     if ($dataForm['id_origem'] != 2) {

        //         $itenContainerOrigem = ItenContainer::where('id_container', $dataForm['id_origem'])->where('id_produto', $dataForm['produto'][$i])->where('controle', $dataForm['controle'][$i])->first();

        //         if (empty($itenContainerOrigem)) {

        //             $status = 'CANCELADO';

        //             $produto = Produto::where('id', $dataForm['produto'][$i])->first();

        //             $msg .= "Produto $produto->nome do Contole " . $dataForm['controle'][$i] . " nao existe na origem </br>";

        //             ItenRequisicao::where('id_requisicao', $requisicao->id)->where('id_produto', $dataForm['produto'][$i])->delete();
        //         } else {

        //             $qtd = $itenContainerOrigem->qtd - $dataForm['qtd'][$i];

        //             if ($qtd < 0) {
        //                 $status = 'CANCELADO';

        //                 $produto = Produto::where('id', $dataForm['produto'][$i])->first();

        //                 $msg .= "Produto $produto->nome do Contole " . $dataForm['controle'][$i] . " nao foi adicionado devido a falta de estoque </br>";


        //                 ItenRequisicao::where('id_requisicao', $requisicao->id)->where('id_produto', $dataForm['produto'][$i])->delete();
        //             } else {
        //                 //remove da origem
        //                 //caso chegar a zerdo deletar da origem
        //                 if ($qtd == 0) {
        //                     ItenContainer::where('id_container', $dataForm['id_origem'])->where('id_produto', $dataForm['produto'][$i])->where('controle', $dataForm['controle'][$i])->delete();
        //                 } else {
        //                     ItenContainer::where('id_container', $dataForm['id_origem'])->where('id_produto', $dataForm['produto'][$i])->where('controle', $dataForm['controle'][$i])->update([
        //                         'qtd' => $qtd
        //                     ]);
        //                 }
        //             }
        //         }
        //     }

        //     $itenContainerDestino = ItenContainer::where('id_container', $dataForm['id_destino'])->where('id_produto', $dataForm['produto'][$i])->where('controle', $dataForm['controle'][$i])->first();

        //     if ($status == 'CONFIRMADO') {
        //         //verificar se ja existe no destino se sim atualizar
        //         if (empty($itenContainerDestino)) {

        //             //add no destino
        //             $itenContainerDestino = new ItenContainer();

        //             $itenContainerDestino->id_container = $dataForm['id_destino'];
        //             $itenContainerDestino->id_produto = $dataForm['produto'][$i];
        //             $itenContainerDestino->qtd = $dataForm['qtd'][$i];
        //             $itenContainerDestino->controle = $dataForm['controle'][$i];
        //             $itenContainerDestino->mac = empty($itenContainerOrigem->mac) ? '' : $itenContainerOrigem->mac;
        //             $itenContainerDestino->save();
        //         } else {

        //             $qtd = $itenContainerDestino->qtd + $dataForm['qtd'][$i];

        //             ItenContainer::where('id_container', $dataForm['id_destino'])->where('id_produto', $dataForm['produto'][$i])->where('controle', $dataForm['controle'][$i])->update([
        //                 'qtd' => $qtd,
        //             ]);
        //         }
        //     }

        //     //cria movimento
        //     $movimento = new Movimento();

        //     $movimento->id_origem = $dataForm['id_origem'];
        //     $movimento->id_destino = $dataForm['id_destino'];
        //     $movimento->id_produto = $dataForm['produto'][$i];
        //     $movimento->qtd = $dataForm['qtd'][$i];
        //     $movimento->controle = $dataForm['controle'][$i];
        //     $movimento->mac = empty($itenContainerOrigem->mac) ? '' : $itenContainerOrigem->mac;
        //     $movimento->solicitante = $user->id;
        //     $movimento->aprovante = $user->id;
        //     $movimento->ordem = '';
        //     $movimento->status = $status;
        //     $movimento->save();

        //     //gera relatorio
        //     $produto = Produto::where('id',$dataForm['produto'][$i])->first();
        //     $relatorio['materiais'][$i] = [
        //         'nome' => $produto->nome,
        //         'un' => $produto->un,
        //         'qtd' => $dataForm['qtd'][$i],
        //         'controle' => $dataForm['controle'][$i],
        //     ];

        // }


        // //atualiza status da requisicao            
        // Requisicao::where('id', $dataForm['id'])->update([
        //     'status' => 'Confirmado'
        // ]);


        // //gera relatorio
        // $origem = Container::find($dataForm['id_origem']);
        // $origem = User::find($origem->id_user);

        // $destino = Container::find($dataForm['id_destino']);
        // $destino = User::find($destino->id_user);

        // $relatorio['origem'] = [
        //     'nome' => $origem->name,
        // ];

        // $relatorio['destino'] = [
        //     'nome' => $destino->name,
        // ];

        // setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        // date_default_timezone_set('America/Sao_Paulo');
        // $dataExtenso = strftime('%A, %d de %B de %Y', strtotime('today'));

        // return view('app.movimentos.comprovante')
        //     ->with([
        //         'relatorio' => $relatorio,
        //         'dataExtenso' => $dataExtenso,
        //         'msg'   =>  'Movimento Criado com Sucesso',
        //         'type'  =>  'success'
        //     ]);
    }

    // public function viewRequisicaoMovimento(Request $request)
    // {

    //     $this->authorize('almox_controle_supervisor');

    //     $requisicao = Requisicao::where('id', $request->requisicao)->first();

    //     $itenRequisicoes = ItenRequisicao::where('id_requisicao', $requisicao->id)->get();

    //     $origem = Container::where('id', $requisicao->id_origem)->first();
    //     $destino = Container::where('id', $requisicao->id_destino)->first();

    //     $requisicao = [
    //         'id' => $requisicao->id,
    //         'id_origem' => $requisicao->id_origem,
    //         'id_destino' => $requisicao->id_destino,
    //         'origem' => $origem->nome,
    //         'destino' => $destino->nome,
    //     ];

    //     $i = 0;
    //     $tabelaItens = [];
    //     foreach ($itenRequisicoes as $itenRequisicao) {

    //         $produto = Produto::where('id', $itenRequisicao->id_produto)->first();

    //         $tabelaItens[$i] = [
    //             'id'            =>  $itenRequisicao->id,
    //             'id_requisicao'   => $itenRequisicao->id_requisicao,
    //             'id_produto'   => $itenRequisicao->id_produto,
    //             'produto'  =>  $produto->nome,
    //             'unico'  =>  $produto->unico,
    //             'qtd'  =>  $itenRequisicao->qtd,
    //         ];

    //         $i++;
    //     }

    //     $itenRequisicoes = $tabelaItens;


    //     return view('app.movimentos.viewRequisicaoMovimento')
    //         ->with([
    //             'itenRequisicoes'    =>  $itenRequisicoes,
    //             'requisicao' => $requisicao,
    //         ]);
    // }

    public function delMovimento(Request $request)
    {

        $this->authorize('controle');

        $dataForm = $request->all();

        $url = $dataForm['url'];

        $movimento = Movimento::where('id', $request->id)->first();

        $movimento->controle == '' ? $movimento->controle = NULL : '';

        //addicionar na origem
        $itenContainerOrigem = ItenContainer::where('id_container', $movimento->id_origem)->where('id_produto', $movimento->id_produto)->where('controle', $movimento->controle)->first();

        $itenContainerDestino = ItenContainer::where('id_container', $movimento->id_destino)->where('id_produto', $movimento->id_produto)->where('controle', $movimento->controle)->first();


        if ($movimento->id_origem == 2 || $movimento->id_origem == 3) { //entrada

            if (empty($itenContainerDestino)) {

                return redirect($url)
                    //->route('viewMovimento')
                    ->with([
                        'msg'   =>  'Produto já Utilizado em outro movimento',
                        'type'  =>  'warning'
                    ]);
            } else {
                $qtd = $itenContainerDestino->qtd - $movimento->qtd;

                if ($qtd == 0) {
                    ItenContainer::where('id', $itenContainerDestino->id)->delete();
                } else {
                    ItenContainer::where('id', $itenContainerDestino->id)->update([
                        'qtd' => $qtd,
                    ]);
                }
            }
        } elseif ($movimento->id_destino == 3) { //saida

            if (empty($itenContainerOrigem)) {

                $itenContainerOrigem = new ItenContainer();

                $itenContainerOrigem->id_container    = $movimento->id_origem;
                $itenContainerOrigem->id_produto      = $movimento->id_produto;
                $itenContainerOrigem->qtd             = $movimento->qtd;
                $itenContainerOrigem->controle        = $movimento->controle  == '' ? NULL : $movimento->controle;
                $itenContainerOrigem->mac             = $movimento->mac  == '' ? NULL : $movimento->mac;

                $itenContainerOrigem->save();
            } else {
                $qtd = $itenContainerOrigem->qtd + $movimento->qtd;

                ItenContainer::where('id', $itenContainerOrigem->id)->update([
                    'qtd' => $qtd,
                ]);
            }
        } else {

            if (empty($itenContainerDestino)) {

                return redirect($url)
                    //->route('viewMovimento')
                    ->with([
                        'msg'   =>  'Produto já Utilizado em outro movimento',
                        'type'  =>  'warning'
                    ]);
            } else {
                $qtd = $itenContainerDestino->qtd - $movimento->qtd;

                if ($qtd == 0) {
                    ItenContainer::where('id', $itenContainerDestino->id)->delete();
                } else {
                    ItenContainer::where('id', $itenContainerDestino->id)->update([
                        'qtd' => $qtd,
                    ]);
                }
            }

            if (empty($itenContainerOrigem)) {

                $itenContainerOrigem = new ItenContainer();

                $itenContainerOrigem->id_container    = $movimento->id_origem;
                $itenContainerOrigem->id_produto      = $movimento->id_produto;
                $itenContainerOrigem->qtd             = $movimento->qtd;
                $itenContainerOrigem->controle        = $movimento->controle  == '' ? NULL : $movimento->controle;
                $itenContainerOrigem->mac             = $movimento->mac  == '' ? NULL : $movimento->mac;

                $itenContainerOrigem->save();
            } else {
                $qtd = $itenContainerOrigem->qtd + $movimento->qtd;

                ItenContainer::where('id', $itenContainerOrigem->id)->update([
                    'qtd' => $qtd,
                ]);
            }
        }

        //deletar movimento
        $movimento = Movimento::where('id', $request->id)->delete();

        return redirect($url)
            //->route('viewMovimento')
            ->with([
                'msg'   =>  'Movimento deletado com sucesso',
                'type'  =>  'success'
            ]);
    }

    public function addMovimento(Request $request)
    {

        $this->authorize('almox_controle');

        $user = Auth::user();

        $containers = Container::where('status', 'ativo')->get();

        $meuContainer = Container::where('id_user', $user->id)->first();

        $produtos = Produto::where('status', 'ativo')->get();

        return view('app.movimentos.addMovimento')->with([
            'containers'    =>  $containers,
            'produtos'      =>  $produtos,
            'msg'           =>  $request->msg,
            'nv_acesso'         =>  $user->nv_acesso,
            'meuContainer'         =>  $meuContainer,
        ]);
    }

    public function addMovimentoSave(Request $request)
    {

        $this->authorize('almox_controle');

        $dataForm = $request->all();

        $user = Auth::user();

        if ($dataForm['id_origem'] == $dataForm['id_destino']) {

            return redirect()
                ->route('viewMovimento')
                ->with([
                    'msg'   =>  'Origem e Destino não podem ser iguais',
                    'type'  =>  'warning'
                ]);
        }

        //verifica se tem na origem
        for ($i = 0; $i < count($dataForm['produto']); $i++) {

            $dataForm['controle'][$i] = strtoupper($dataForm['controle'][$i]);

            $produto = Produto::where('id', $dataForm['produto'][$i])->first();

            $dataForm['controle'][$i] == '' ? $dataForm['controle'][$i] = NULL : '';

            //remove da origem
            $itenContainerOrigem = ItenContainer::where('id_container', $dataForm['id_origem'])->where('id_produto', $dataForm['produto'][$i])->where('controle', $dataForm['controle'][$i])->first();

            if (!empty($produto)) {

                if (!empty($itenContainerOrigem)) {

                    $qtd = $itenContainerOrigem->qtd - $dataForm['qtd'][$i];

                    if ($qtd < 0) {

                        return redirect()
                            ->route('viewMovimento')
                            ->with([
                                'msg'   =>  'Produto ' . $produto->nome . ' com quantidade insuficente',
                                'type'  =>  'warning'
                            ]);
                    }

                    if ($qtd == 0) {
                        $itenContainerOrigemDel[$i] = $itenContainerOrigem->id;
                    } else {
                        $itenContainerOrigemUp[$i] = [
                            'id' => $itenContainerOrigem->id,
                            'qtd' => $qtd,
                        ];
                    }
                } else {
                    return redirect()
                        ->route('viewMovimento')
                        ->with([
                            'msg'   =>  'Produto ' . $produto->nome . ' não existe na origem',
                            'type'  =>  'warning'
                        ]);
                }
            } else {

                return redirect()
                    ->route('viewMovimento')
                    ->with([
                        'msg'   =>  'Produto ' . $dataForm['produto'][$i] . 'não existe',
                        'type'  =>  'warning'
                    ]);
            }

            //add no destino
            $itenContainerDestino = ItenContainer::where('id_container', $dataForm['id_destino'])->where('id_produto', $dataForm['produto'][$i])->where('controle', $dataForm['controle'][$i])->first();

            //verificar se ja existe no destino se sim atualizar
            if (empty($itenContainerDestino)) {

                $itenContainerDestinoSave[$i] = new ItenContainer();

                $itenContainerDestinoSave[$i]->id_container = $dataForm['id_destino'];
                $itenContainerDestinoSave[$i]->id_produto = $dataForm['produto'][$i];
                $itenContainerDestinoSave[$i]->qtd = $dataForm['qtd'][$i];
                $itenContainerDestinoSave[$i]->controle = $dataForm['controle'][$i]  == '' ? NULL : strtoupper($dataForm['controle'][$i]);
                $itenContainerDestinoSave[$i]->mac = empty($itenContainerOrigem->mac) ? '' : $itenContainerOrigem->mac;
            } else {

                $qtd = $itenContainerDestino->qtd + $dataForm['qtd'][$i];

                $itenContainerDestinoUp[$i] = [
                    'id' => $itenContainerDestino->id,
                    'qtd' => $qtd,
                ];
            }

            //inseri movimento 
            $movimento[$i] = new Movimento;

            //cadastra Movimento
            $movimento[$i]->id_origem   = $dataForm['id_origem'];
            $movimento[$i]->id_destino  = $dataForm['id_destino'];
            $movimento[$i]->id_produto  = $itenContainerOrigem->id_produto;
            $movimento[$i]->qtd         = $request->qtd[$i];
            $movimento[$i]->controle    = $itenContainerOrigem->controle;
            $movimento[$i]->mac         = $itenContainerOrigem->mac;
            $movimento[$i]->solicitante = $user->id;
            $movimento[$i]->aprovante   = $user->id;
            $movimento[$i]->status      = 'Confirmado';
        }

        //executa no bd
        //deletas os itenContainerOrigemDel caso qtd 0
        if (!empty($itenContainerOrigemDel)) {

            sort($itenContainerOrigemDel);



            for ($i = 0; $i < count($itenContainerOrigemDel); $i++) {
                ItenContainer::where('id', $itenContainerOrigemDel[$i])->delete();
            }
        }


        //atualiza os itenContainerOrigemUp com qtd > 0
        if (!empty($itenContainerOrigemUp)) {
            sort($itenContainerOrigemUp);



            for ($i = 0; $i < count($itenContainerOrigemUp); $i++) {
                ItenContainer::where('id', $itenContainerOrigemUp[$i]['id'])->update([
                    'qtd' => $itenContainerOrigemUp[$i]['qtd'],
                ]);
            }
        }

        //atualiza os itenContainerDestino com qtd > 0
        if (!empty($itenContainerDestinoUp)) {
            sort($itenContainerDestinoUp);


            for ($i = 0; $i < count($itenContainerDestinoUp); $i++) {
                ItenContainer::where('id', $itenContainerDestinoUp[$i]['id'])->update([
                    'qtd' => $itenContainerDestinoUp[$i]['qtd'],
                ]);
            }
        }

        //cria os itenContainerDestinoSave
        if (!empty($itenContainerDestinoSave)) {
            sort($itenContainerDestinoSave);


            for ($i = 0; $i < count($itenContainerDestinoSave); $i++) {
                $itenContainerDestinoSave[$i]->save();
            }
        }

        //cria os movimentos
        if (!empty($movimento)) {
            sort($movimento);




            for ($i = 0; $i < count($movimento); $i++) {
                $movimento[$i]->save();
            }
        }

        return redirect()
            ->route('viewMovimento')
            ->with([
                'msg'   =>  'Movimento inserido',
                'type'  =>  'success'
            ]);
    }
}
