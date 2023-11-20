<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\ItenContainer;
use App\Models\LinkContainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
    
    public function viewUsuario($msg = null){   

        $this->authorize('ativo');

        $user = Auth::user();

        if ($user->nv_acesso == 'admin'){
            $usuarios = User::select('id','name','email','status','nv_acesso')->get();
        }elseif ($user->nv_acesso == 'controle'){
            $usuarios = User::select('id','name','email','status','nv_acesso')->where('nv_acesso','!=','admin')->get();
        }else{
            $usuarios = User::select('id','name','email','status','nv_acesso')->where('id',$user->id)->get();
        }
        

        return view('app.usuarios.viewUsuario')->with(['usuarios'   =>  $usuarios,
                                                        'msg'       =>  $msg]);
    }

    public function desativaUser(Request $request){      

        $this->authorize('controle');

        $request->validate([
            'id' => 'required|integer'
        ]);

        $usuario = User::find($request->id);
        
        switch ($usuario->status) {
            case 'ativo':
                $usuario->update(['status' => 'desativo']);
                break;

            case 'desativo':
                $usuario->update(['status' => 'ativo']);
                break;

            case 'pre_registro':
                $usuario->update(['status' => 'ativo']);
                break;
        }

        return redirect()
            ->route('viewUsuario')
            ->with(['msg'   =>  'Usuario ALTERADO com sucesso',
                    'type'  =>  'warning']);
    }

    public function edtUser(Request $request){      

        $this->authorize('ativo');
        
        //reculera registro
        $usuario = User::select('id','name','email','nv_acesso','status')->find($request->id);

        return view('app.usuarios.edtUser')->with([
            'usuario' => $usuario,
        ]);
    }

    public function edtUserSave(Request $request){      

        $this->authorize('ativo');

        $request->validate([
            'name'          =>  'required|max:191',
            'email'     =>  'required|email',
            'status'     =>  'required',
            'nv_acesso'     =>  'required',
        ]);

        $usuario = User::where('id',$request->id)->first();
        
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => !empty($request->password) ? Hash::make($request->password): $usuario->password,
            'status' => $request->status,
            'nv_acesso' => $request->nv_acesso,
        ]);

        return redirect()
            ->route('viewUsuario')
            ->with(['msg'   =>  'Usuario ALTERADO com sucesso',
                    'type'  =>  'warning']);
    }

    public function delUser(Request $request){    

        $this->authorize('admin');

        $request->validate([
            'id' => 'required|integer'
        ]);

        //remover links container
        $sql = "SELECT * FROM link_containers WHERE pai = $request->id";
        $linkContainer = DB::select($sql);
        foreach ($linkContainer as $linkContainerKey => $linkContainerValue) {
            $delLinkContaine = LinkContainer::find($linkContainerValue->id);
            $delLinkContaine->delete();
        }

        //reculera registro
        $usuario = User::find($request->id);

        //deleta registro
        $usuario->delete();

        //atualiza os container do proprietario
        Container::where('id_user',$request->id)->update([
            'id_user' => 1,
        ]);

        return redirect()
            ->route('viewUsuario')
            ->with(['msg'   =>  'Usuario DELETADO com Sucesso',
                    'type'  =>  'danger']);
    }
        
}