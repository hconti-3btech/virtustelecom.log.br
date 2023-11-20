<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Meta;
use Illuminate\Http\Request;

class MetaController extends Controller
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
        
        $metas = Meta::all();

        return view('app.metas.viewMeta')->with(['metas'     =>  $metas,
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
        
        return view('app.metas.cadMeta');
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

        $dataForm['meta'] = str_replace(",", ".", $dataForm['meta']);
        $dataForm['comissao'] = str_replace(",", ".", $dataForm['comissao']);

        $meta = new Meta();

        $meta->descricao = $dataForm['descricao'];
        $meta->meta = $dataForm['meta'];
        $meta->comissao = $dataForm['comissao'];
        $meta->mes = $dataForm['mes'];
        $meta->ano = $dataForm['ano'];
        $meta->save();
        
        return redirect()
        ->route('viewMeta')
        ->with(['msg'   =>  'Meta cadastrada com Sucesso',
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

        $meta = Meta::find($id);

        $meta->delete();

        return redirect()
            ->route('viewMeta')
            ->with(['msg'   =>  'Meta DELETADO com Sucesso',
                    'type'  =>  'danger']);
    }
}
