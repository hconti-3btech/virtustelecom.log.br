<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Custo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class CustoController extends Controller
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
        
        $custos = Custo::all();

        return view('app.custos.viewCusto')->with([ 'custos'     =>  $custos,
                                                    'msg'           =>  $msg]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin');
        
        return view('app.custos.cadCusto');
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

        $dataForm = $request->all();

        $dataForm['valor'] = str_replace(",", ".", $dataForm['valor']);

        $verifica = Custo::where('mes',$dataForm['mes'])->where('ano',$dataForm['ano'])->first();

        if (!empty($verifica)){
            return redirect()
            ->route('viewCusto')
            ->with(['msg'   =>  'Custo Já Cadastrado',
                    'type'  =>  'warning']);
        }

        $custo = new Custo();

        $custo->mes = $dataForm['mes'];
        $custo->ano = $dataForm['ano'];
        $custo->valor = $dataForm['valor'];
        $custo->save();

        return redirect()
            ->route('viewCusto')
            ->with(['msg'   =>  'Custo cadastrado com SUCESSO',
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

        //reculera registro
        $custo = Custo::find($id);

        //deleta registro
        $custo->delete();

        return redirect()
            ->route('viewCusto')
            ->with(['msg'   =>  'Custo DELETADO com Sucesso',
                    'type'  =>  'success']);
    }
}
