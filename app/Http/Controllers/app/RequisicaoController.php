<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\ItenRequisicao;
use App\Models\Produto;
use App\Models\Requisicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\ErrorHandler\Debug;

class RequisicaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
    
    public function addRequisicao(Request $request){
        
        $this->authorize('tecnico_controle');

        $user = Auth::user();

        $msg = [$request->msg];

        if ($user->nv_acesso == 'admin'){
            $containers = Container::Where('status','ativo')->get();
        }else{
            $containers = Container::Where('id_user',$user->id)->get();
        }
        

        $produtos = Produto::Where('status','ativo')->get();

        return view('app.requisicoes.addRequisicao')->with([
            'containers' => $containers,
            'produtos' => $produtos,
            'msg' => $msg,
        ]);  

    }

    public function addRequisicaoSave(Request $request){
        
        $this->authorize('tecnico_controle');

        $regras = [
            'qtd.*' => 'required|numeric',
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatorio',
            'numeric' => 'O valor de :attribute precisa ser numerico'
        ];

        $request->validate($regras,$feedback);

        $verificaProdutosDuplicados = array_count_values($request->produto);

        foreach ($verificaProdutosDuplicados as $key => $verificaProdutosDuplicadosValue) {
            if ($verificaProdutosDuplicadosValue > 1){
                return redirect()
                ->route('addRequisicao',['msg'   =>  'Itens Duplicados',]); 
            }
        }

        $requisicao = new Requisicao();

        $requisicao->id_origem = 4;
        $requisicao->id_destino = $request->id_destino;
        $requisicao->status = 'Aguardando';
        $requisicao->save();

        for ($i=0; $i < count($request->produto); $i++) { 
                        
            $itenRequisicao = new ItenRequisicao();
            $itenRequisicao->id_requisicao = $requisicao->id;
            $itenRequisicao->id_produto = $request->produto[$i];
            $itenRequisicao->qtd = $request->qtd[$i];
            
            $itenRequisicao->save();
        }

        return redirect()
            ->route('home')
            ->with([
                'msg' => 'Requisição realizada com sucesso',
                'type' => 'primary',
            ]);  

    }

    public function viewRequisicao(Request $request){   

        $this->authorize('ativo');

        $hoje = strtotime("now");
        strlen($request->data_ini) ? $data_ini = $request->data_ini : $data_ini =  date('Y-m-d', strtotime('-30 days', $hoje));
        strlen($request->data_fim) ? $data_fim = $request->data_fim : $data_fim = date("Y-m-t");


        $requisicoes = Requisicao::where('id_origem','4')->whereBetween('created_at', [$data_ini, $data_fim])->get();

        //Trata para apresentar na tabela
        $tabelaItens = [];

        //Cria tabelaItens
        $i = 0;
        foreach ($requisicoes as $requisicao) {

            $origem = Container::where('id',$requisicao['id_origem'])->first();

            $destino = Container::where('id',$requisicao['id_destino'])->first();

            $cor = '';
            if ($requisicao['status'] == 'CANCELADO'){
                $cor = 'secondary';
            }elseif ($requisicao['status'] == 'CONFIRMADO'){
                $cor = 'primary';
            }elseif ($requisicao['status'] == 'AGUARDANDO') {
                $cor = 'warning';
            }

            $tabelaItens[$i] = [
                'id'            =>  $requisicao['id'],
                'origem'   =>  $origem['nome'],
                'destino'  =>  $destino['nome'],
                'status'  =>  $requisicao['status'],
                'cor'   => $cor,
                'created_at'  =>  $requisicao['created_at'],
            ];

            $i++;
        }

        $requisicoes = $tabelaItens;

        return view('app.requisicoes.viewRequisicao')->with([ 
            'requisicoes'    =>  $requisicoes,
            'msg'           =>  $request->msg
        ]);
    }

    public function aprovRequisicao(Request $request){

        $this->authorize('almox_controle_supervisor');

        $request->validate([
            'id' => 'required|integer'
        ]);

        $requisicao = Requisicao::find($request->id);

        if($request->status == 'CONFIRMADO'){
            
            return redirect()
            ->route('requisicaoMovimento',['requisicao'   =>  $requisicao->id,]);

        }

        if($request->status == 'CANCELADO'){
            $requisicao->update(['status' => 'CANCELADO']);
        }

        // if($request->status == 'VIEW'){
        //     return redirect()
        //     ->route('viewRequisicaoMovimento',['requisicao'   =>  $requisicao->id,]);
        // }


        return redirect()
            ->route('viewRequisicao')
            ->with(['msg'   =>  'Requisição ALTERADA com sucesso',
                    'type'  =>  'warning']);

    }


}
