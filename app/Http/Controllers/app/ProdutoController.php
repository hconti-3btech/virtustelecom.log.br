<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
    
    public function viewProduto($msg = null){   
        
        $this->authorize('admin');
        
        $produtos = Produto::all()->toArray();

        return view('app.produtos.viewProduto')->with(['produtos'   =>  $produtos,
                                                        'msg'       =>  $msg]);
    }

    public function delProduto(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'id' => 'required|integer'
        ]);

        //reculera registro
        $produto = Produto::find($request->id);

        //deletar img associada
        Storage::delete("img/produtos/{$produto->path}");
        

        //deleta registro
        $produto->delete();

        return redirect()
            ->route('viewProduto')
            ->with(['msg'   =>  'PRODUTO DELETADO com Sucesso',
                    'type'  =>  'danger']);
    }

    public function desativaProduto(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'id' => 'required|integer'
        ]);

        $produto = Produto::find($request->id);

        $produto->status == 'ativo' ? $produto->update(['status' => 'desativo']): $produto->update(['status' => 'ativo']);

        return redirect()
            ->route('viewProduto')
            ->with(['msg'   =>  'PRODUTO ALTERADO com sucesso',
                    'type'  =>  'warning']);
    }

    public function cadProduto(){      

        $this->authorize('admin');
        
        return view('app.produtos.cadProduto');
    }

    public function cadProdutoSave(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'nome'          =>  'required|max:191',
            'descricao'     =>  'max:500'
        ]);

        // Deixa valor em branco caso nao faça o upload
        $imageName = '';
        
        $produto = new Produto;

        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->un = $request->un;
        $produto->fabricante = $request->fabricante;
        $produto->est_min = $request->est_min;
        $produto->est_pedido = $request->est_pedido;
        $produto->unico = $request->unico;
        $produto->status = $request->status;

        // Img Upload
        if($request->hasFile('imgFile')!=null && $request->file('imgFile')->isValid()){

            $requestImage = $request->imgFile;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(storage_path('app/public/img/produtos'), $imageName);

        }

        $produto->path = $imageName;

        $produto->save();

        return redirect()
            ->route('viewProduto')
            ->with(['msg'   =>  'PRODUTO SALVO com sucesso',
                    'type'  =>  'success']);
    }

    public function edtProduto(Request $request){      

        $this->authorize('admin');
        
        //reculera registro
        $produto = Produto::find($request->id);

        return view('app.produtos.edtProduto',compact('produto'));
    }

    public function edtProdutoSave(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'nome'          =>  'required|max:191',
            'descricao'     =>  'max:90000'
        ]);
        
        $imageName = '';

        //reculera registro
        $produto = Produto::find($request->id);

        // dell img antiga
        if ($request->hasFile('imgFile')!=null){
            Storage::delete("img/produtos/{$produto->path}");
        }
        

        // Img Upload
        if($request->hasFile('imgFile')!=null && $request->file('imgFile')->isValid()){

            $requestImage = $request->imgFile;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(storage_path('app/public/img/produtos'), $imageName);

        }

        Produto::where('id',$request->input('id'))->update([
            'nome'          => $request->nome,
            'descricao'     => $request->descricao,
            'un'            => $request->un,
            'fabricante'    => $request->fabricante,
            'est_min'       => $request->est_min,
            'est_pedido'    => $request->est_pedido,
            'unico'         => $request->unico,
            'path'          => $imageName != '' ? $imageName : $produto->path,
            'status'        => $request->status
        ]);

        return redirect()
            ->route('viewProduto')
            ->with(['msg'   =>  'PRODUTO ALTERADO com sucesso',
                    'type'  =>  'warning']);
    }



}

