<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Regra;
use Illuminate\Http\Request;

class RegraController extends Controller
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
        
        $regras = Regra::all()->toArray();

        return view('app.regras.index')
        ->with([
            'regras'   =>  $regras,
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
        
        return view('app.regras.create');
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
            'qtd_pontos'    =>  'required|integer',
            'valor'         =>  'required|regex:/^\d+(\.\d{1,2})?$/'
        ],
        [   
            'required' => 'O campo :attribute é obrigatorio',
            'regex' => 'Apenas numeros separados por "." (Ex 2.25)',
        ]);

        $regra = new Regra();

        $regra->qtd_pontos = $request->qtd_pontos;
        $regra->valor = $request->valor;
        $regra->save();

        return redirect()
            ->route('regra.index')
            ->with(['msg'   =>  'Regra SALVO com sucesso',
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
        $regra = Regra::find($id);

        return view('app.regras.edit',compact('regra'));
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
            'qtd_pontos'    =>  'required|integer',
            'valor'         =>  'required|regex:/^\d+(\.\d{1,2})?$/'
        ],
        [   
            'required' => 'O campo :attribute é obrigatorio',
            'regex' => 'Apenas numeros separados por "." (Ex 2.25)',
        ]);

        Regra::find($request->id)->update([
            'qtd_pontos'    => $request->qtd_pontos,
            'valor'         => $request->valor,
        ]);

        return redirect()
            ->route('regra.index')
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

        $regra = Regra::find($id);

        //deleta registro
        $regra->delete();

        return redirect()->route('regra.index');
    }
}
