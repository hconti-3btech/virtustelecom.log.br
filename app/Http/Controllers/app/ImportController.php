<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Imports\MaterialImport;
use App\Imports\OrdemImport;
use App\Models\ItenContainer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{

    public function importOrdem(){
        $this->authorize('controle_apoio');

        return view('app.import.importOrdem');
    }

    public function importOrdemSave(Request $request){
        $this->authorize('controle_apoio');

        $file = $request->file('csvFile')->store('import');

        // Excel::import(new OrdemImport, $file);

        $import = new OrdemImport;
        $import->import($file);

        // dd($import->failures());

        return redirect()
        ->route('importOrdem')
        ->with(['msg'   =>  'Importado com sucesso',
                'type'  =>  'success']);


    }

    public function importMaterial(){
        $this->authorize('controle_apoio');

        return view('app.import.importMaterial');
    }

    public function importMaterialSave(Request $request){
        $this->authorize('controle_apoio');

        $file = $request->file('csvFile')->store('import');

        // Excel::import(new OrdemImport, $file);

        $import = new MaterialImport;
        $import->import($file);

        // dd($import->failures());

        // $import->failures();

        return redirect()
        ->route('importMaterial')
        ->with(['msg'   =>  'Importado com sucesso',
                'type'  =>  'success']);
    }
}
