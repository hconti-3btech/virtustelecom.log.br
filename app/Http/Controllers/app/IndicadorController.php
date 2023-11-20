<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Indicador;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Http\Request;

class IndicadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($msg = null)
    {
        $this->authorize('admin');
        
        $indicadores = Indicador::get();

        foreach ($indicadores as $key => $indicador) {
            $usuario = User::find($indicador->id_user_tec);
            $servico = Servico::find($indicador->id_servico_tipo);
            

            $indicador->tecnico = $usuario->name;
            $indicador->servico = $servico->nome;
        }

        return view('app.indicadores.index')
        ->with([
            'indicadores'   =>  $indicadores,
            'msg'       =>  $msg
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin');

        $tecnicos = User::where('nv_acesso','tecnico')->get();
        $servicos = Servico::get();
        
        return view('app.indicadores.create')
        ->with([
            'tecnicos' => $tecnicos,
            'servicos' => $servicos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'id_user_tec'       =>  'required|exists:users,id',
            'id_servico_tipo'   =>  'required|exists:servicos,id',
            'mes'   =>  'required|numeric|max:12|min:1',
            'ano'   =>  'required|numeric|min:2023',
            'qtd'   =>  'required|integer',
        ],
        [   
            'required' => 'O campo :attribute é obrigatorio',
        ]);

        $indicador = new Indicador();
        
        $indicador->id_user_tec = $request->id_user_tec;
        $indicador->id_servico_tipo = $request->id_servico_tipo;
        $indicador->mes = $request->mes;
        $indicador->ano = $request->ano;
        $indicador->qtd = $request->qtd;
        $indicador->save();


        return redirect()
            ->route('indicador.index')
            ->with(['msg'   =>  'Indicador SALVO com sucesso',
                    'type'  =>  'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('admin');

        $tecnicos = User::where('nv_acesso','tecnico')->get();
        $servicos = Servico::get();

        $indicador = Indicador::find($id);
        
        return view('app.indicadores.edit')
        ->with([
            'tecnicos' => $tecnicos,
            'servicos' => $servicos,
            'indicador' => $indicador,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'id_user_tec'       =>  'required|exists:users,id',
            'id_servico_tipo'   =>  'required|exists:servicos,id',
            'mes'   =>  'required|numeric|max:12|min:1',
            'ano'   =>  'required|numeric|min:2023',
            'qtd'   =>  'required|integer',
        ],
        [   
            'required' => 'O campo :attribute é obrigatorio',
        ]);

        Indicador::find($request->id)->update([
            'id_user_tec'    => $request->id_user_tec,
            'id_servico_tipo'         => $request->id_servico_tipo,
            'mes'         => $request->mes,
            'ano'         => $request->ano,
            'qtd'         => $request->qtd,
        ]);

        return redirect()
            ->route('indicador.index')
            ->with(['msg'   =>  'Indicador ALTERADO com sucesso',
                    'type'  =>  'warning']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('admin');

        $indicador = Indicador::find($id);

        //deleta registro
        $indicador->delete();

        return redirect()->route('indicador.index');
    }
}
