<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\ItenContainer;
use App\Models\LinkContainer;
use App\Models\Produto;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Matcher\Contains;

class ContainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
    
    public function viewContainer($msg = null){      

        $this->authorize('ativo');

        $user = Auth::user();

        // $meuContainer = Container::where('id_user', $user->id)->first();

        $containersFilho = LinkContainer::where('pai', $user['id'])->get();

        //Trata para apresentar na tabela
        $tabelaItens = [];

        //Cria tabelaItens
        $i = 0;
        foreach ($containersFilho as $containerFilho) {

            $container = Container::where('id', $containerFilho['container'])->first();

            $pai = Container::where('id',$container['pai'])->first();

            $usuario = User::where('id',$container['id_user'])->first();

            $tabelaItens[$i] = [
                'id'            =>  $container->id,
                'nome'   =>  $container->nome,
                'pai'  =>  $pai <> NULL ? $pai->nome : 'ORFÃO',
                'usuario'  =>  $usuario->name ,
                'status'  =>  $container->status,
            ];

            $i++;
        }
        

        return view('app.containers.viewContainer')->with([ 'tabelaItens'     =>  $tabelaItens,
                                                            'msg'           =>  $msg]);
    }

    public function delContainer(Request $request){      

        $this->authorize('controle');

        $request->validate([
            'id' => 'required|numeric|gt:3'
        ]);

        //reculera registro
        $container = Container::find($request->id);

        //deleta registro
        $container->delete();

        return redirect()
            ->route('viewContainer')
            ->with(['msg'   =>  'DELETADO com Sucesso',
                    'type'  =>  'danger']);
    }

    public function desativaContainer(Request $request){      

        $this->authorize('controle');

        $request->validate([
            'id' => 'required|numeric|min:1'
        ]);

        $container = Container::find($request->id);

        $container->status == 'ativo' ? $container->update(['status' => 'desativo']): $container->update(['status' => 'ativo']);

        return redirect()
            ->route('viewContainer')
            ->with(['msg'   =>  'ALTERADO com sucesso',
                    'type'  =>  'warning']);
    }

    public function cadContainer(){      

        $this->authorize('controle');
        
        $containers = Container::where('status','Ativo')->whereNotIn('id',[2,3])->get()->toArray();

        $usuarios = User::where('status','Ativo')->get()->toArray();

        return view('app.containers.cadContainer')->with([  'containers'=> $containers,
                                                            'usuarios'  => $usuarios]);
    }

    public function cadContainerSave(Request $request){      

        $this->authorize('controle');

        $request->validate(
            [
                'nome'  =>  'required|max:191|unique:containers,nome',
                'pai'   =>  'required',
            ],
        );

        
        $container = new Container();

        $container->nome = $request->nome;
        $container->pai = $request->pai;
        $container->id_user = $request->id_user;
        $container->status = $request->status;

        $container->save();

        return redirect()
            ->route('viewContainer')
            ->with(['msg'   =>  'SALVO com sucesso',
                    'type'  =>  'success']);
    }

    public function edtContainer(Request $request){      

        $this->authorize('controle');
        
        //reculera registro
        $containerEdt = Container::find($request->id);

        $containers = Container::where('status','ativo')->get()->toArray();


        return view('app.containers.edtContainer')->with(['containerEdt'  => $containerEdt,
                                                        'containers'    => $containers]);
    }

    public function edtContainerSave(Request $request){      

        $this->authorize('controle');

        $request->validate([
            'nome'  =>  'required|max:191',
            'pai'   =>  'required'
        ]);

        Container::where('id',$request->input('id'))->update([
            'nome'  => $request->nome,
            'pai'   => $request->pai,
            'status' => $request->status
        ]);

        return redirect()
            ->route('viewContainer')
            ->with(['msg'   =>  'ALTERADO com sucesso',
                    'type'  =>  'warning']);
    }

    public function estoqueContainer(Request $request){      

        $this->authorize('ativo');

        $canSee = false;

        $user = Auth::user();

        // $meuContainer = Container::where('id_user', $user->id)->first();

        $containersFilho = LinkContainer::where('container', $request->id)->get();



        foreach ($containersFilho as $containerFilho) {

            if ($containerFilho['pai'] === $user->id){
                
                $canSee = TRUE;
            }
        }

        if ($canSee === false){
            return redirect()
            ->route('viewContainer')
            ->with(['msg'   =>  'Voçê não tem permissão para ver esse container',
                    'type'  =>  'danger']);
        }
        
        
        //reculera registro
        $itenContainers = ItenContainer::where('id_container',$request->id)->get()->toArray();

        $novoArray = [];
        
        $i = 0;
        foreach ($itenContainers as $itenContainer) {

            $container = Container::where('id',$itenContainer['id_container'])->first();
            $produto = Produto::where('id',$itenContainer['id_produto'])->first();

            $novoArray[$i] = [
                'id'        =>  $itenContainer['id'],
                'container' =>  $container->nome,
                'produto'   =>  $produto->nome,
                'qtd'       =>  $itenContainer['qtd'],
                'controle'  =>  $itenContainer['controle'],
                'mac'       =>  $itenContainer['mac'],        
            ];

            $i++;
        }

        $itenContainers = $novoArray;

        $container = Container::where('id',$request->id)->first();
        
        return view('app.containers.viewItenContainer')->with(['itenContainers'  => $itenContainers,
                                                            'container' =>  $container]);
    }

}
