<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Pontos;
use App\Models\Servico;
use Illuminate\Http\Request;

class PontoController extends Controller
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
        
        $pontos = Pontos::get();

        foreach ($pontos as $key => $ponto) {

            $servico = Servico::find($ponto->id_servico);
            $ponto->servico = $servico->nome;
        }

        return view('app.pontos.index')
        ->with([
            'pontos'   =>  $pontos,
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

        $servicos = Servico::get();
        
        return view('app.pontos.create')
        ->with([
            'servicos'  => $servicos,
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
            'id_servico'    =>  'required|exists:servicos,id',
            'pontos'         =>  'required|integer'
        ],
        [   
            'required'  => 'O campo :attribute é obrigatorio',
            'exists'    => 'Servico nao cadastrado',
            'integer'   => 'Apenas numeros separados por "." (Ex 2.25)',
        ]);

        $ponto = new Pontos();

        $ponto->id_servico = $request->id_servico;
        $ponto->pontos = $request->pontos;
        $ponto->save();

        return redirect()
            ->route('ponto.index')
            ->with(['msg'   =>  'Ponto SALVO com sucesso',
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
        
        //reculera registro
        $ponto = Pontos::find($id);

        $servicos = Servico::get();

        return view('app.pontos.edit')
        ->with([
            'servicos'  => $servicos,
            'ponto'     => $ponto,
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
            'id_servico'    =>  'required|exists:servicos,id',
            'pontos'         =>  'required|integer'
        ],
        [   
            'required'  => 'O campo :attribute é obrigatorio',
            'exists'    => 'Servico nao cadastrado',
            'integer'   => 'Apenas numeros separados por "." (Ex 2.25)',
        ]);

        Pontos::find($request->id)->update([
            'id_servico'    => $request->id_servico,
            'pontos'         => $request->pontos,
        ]);

        return redirect()
            ->route('ponto.index')
            ->with(['msg'   =>  'Regra ALTERADO com sucesso',
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

        $ponto = Pontos::find($id);

        //deleta registro
        $ponto->delete();

        return redirect()->route('ponto.index');
    }
}
