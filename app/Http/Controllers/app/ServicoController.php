<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
    
    public function viewServico($msg = null){   

        $this->authorize('admin');
        
        $servicos = Servico::all()->toArray();

        return view('app.servicos.viewServico')->with(['servicos'   =>  $servicos,
                                                        'msg'       =>  $msg]);
    }

    public function cadServico(){      

        $this->authorize('admin');


        return view('app.servicos.cadServico');
    }

    public function cadServicoSave(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'nome'          =>  'required|max:191',
            'descricao'     =>  'max:500'
        ]);

        $servico = new Servico();

        $servico->nome = $request->nome;
        $servico->status = $request->status;
        $servico->save();

        return redirect()
            ->route('viewServico')
            ->with(['msg'   =>  'Serviço SALVO com sucesso',
                    'type'  =>  'success']);
    }

    public function desativaServico(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'id' => 'required|integer'
        ]);

        $servico = Servico::find($request->id);

        $servico->status == 'ativo' ? $servico->update(['status' => 'desativo']): $servico->update(['status' => 'ativo']);

        return redirect()
            ->route('viewServico')
            ->with(['msg'   =>  'PRODUTO ALTERADO com sucesso',
                    'type'  =>  'warning']);
    }

    public function edtServico(Request $request){      

        $this->authorize('admin');


        $servico = Servico::Where('id',$request->id)->first();

        return view('app.servicos.edtServico')->with(['servico' => $servico]);
    }

    public function edtServicoSave(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'id'    => 'required|integer',
            'nome'  =>  'required|max:191',
        ]);

        $servico = Servico::Where('id',$request->id)->update([
            'nome' => $request->nome,
            'status' => $request->status,
        ]);

        return redirect()
        ->route('viewServico')
        ->with(['msg'   =>  'Servico ALTERADO com sucesso',
                'type'  =>  'warning']);
    }

    public function delServico(Request $request){      

        $this->authorize('admin');

        $request->validate([
            'id' => 'required|integer'
        ]);

        //reculera registro
        $servico = Servico::find($request->id);

        //deleta registro
        $servico->delete();

        return redirect()
            ->route('viewServico')
            ->with(['msg'   =>  'Servico DELETADO com Sucesso',
                    'type'  =>  'danger']);
    }


}
