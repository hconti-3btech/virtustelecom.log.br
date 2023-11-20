<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\LinkContainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkContainerController extends Controller
{
    public function viewLinkContainer(Request $request){      

        $this->authorize('admin');

        $linksContainer = LinkContainer::all();

        //Trata para apresentar na tabela
        $tabelaItens = [];

        //Cria tabelaItens
        $i = 0;
        foreach ($linksContainer as $linkContainer) {

            $container = Container::where('id', $linkContainer['container'])->first();

            $pai = User::where('id',$linkContainer['pai'])->first();

            $tabelaItens[$i] = [
                'id'    =>  $linkContainer->id,
                'nome'  =>  $container->nome,
                'pai'   =>  $pai->name,
            ];

            $i++;
        }
        
        return view('app.linkcontainers.viewLinkContainer')->with([  
                                                                'tabelaItens'  => $tabelaItens,
                                                            ]);
    }


    public function cadLinkContainer(Request $request){      

        $this->authorize('admin');

        $containers = Container::where('status','ativo')->where('id','>',3)->orderBy('nome','ASC')->get();

        $users = User::where('status','ativo')->orderBy('name','ASC')->get();
        
        return view('app.linkcontainers.cadLinkContainer')->with([  
                                                                'containers'  => $containers,
                                                                'users'  => $users,
                                                            ]);
    }

    public function cadLinkContainerSave(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'container' => 'required|numeric|exists:containers,id',
            'pai' => 'required|numeric|exists:users,id',
        ]);

        $linkContainer = new LinkContainer();

        $linkContainer->container = $request->container;
        $linkContainer->pai = $request->pai;
        $linkContainer->save();
        
        return redirect()
        ->route('viewLinkContainer')
        ->with(['msg'   =>  'Link Cadastrado com Sucesso',
                'type'  =>  'success']);
    }

    public function delLinkContainer(Request $request){      

        $this->authorize('controle');

        $request->validate([
            'id' => 'required|numeric|gt:3'
        ]);

        //reculera registro
        $linkContainer = LinkContainer::find($request->id);

        //deleta registro
        $linkContainer->delete();

        return redirect()
            ->route('viewLinkContainer')
            ->with(['msg'   =>  'DELETADO com Sucesso',
                    'type'  =>  'danger']);
    }

    public function copyLinkContainer(){      

        $this->authorize('admin');

        $users = User::where('status','ativo')->orderBy('name','ASC')->get();
        
        return view('app.linkcontainers.copyLinkContainer')->with([  
                                                                'users'  => $users,
                                                            ]);
    }

    public function copyLinkContainerSave(Request $request){      

        $this->authorize('admin');

        $dataForm = $request->all();

        if ($dataForm['para']<=1){
            return redirect()
            ->route('viewLinkContainer')
            ->with(['msg'   =>  'Erro ID',
                    'type'  =>  'danger']);
        }

        $possoVer = LinkContainer::where('pai',$dataForm['de'])->get();

        $containersPara = Container::where('id_user',$dataForm['para'])->get();


        foreach ($containersPara as $containersParaKey => $containersParaValue) {

            $usuarioPara = User::where('id',$containersParaValue->id_user)->first();            

            foreach ($possoVer as $possoVerKey => $possoVerValue) {

                $verificaDuplisidade = LinkContainer::where('container',$possoVerValue->container)->where('pai',$usuarioPara->id)->first();

                if(empty($verificaDuplisidade)){
                
                    $linkContainer = new LinkContainer();

                    $linkContainer->container = $possoVerValue->container;
                    $linkContainer->pai = $usuarioPara->id;
                    $linkContainer->save();
                }
                
                
            }
                
        }
        
        return redirect()
            ->route('viewLinkContainer')
            ->with(['msg'   =>  'Link Copiados com Sucesso',
                    'type'  =>  'success']);
    }

}
