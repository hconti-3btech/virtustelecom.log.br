<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use App\Models\ValorServico;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class ValoreServicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function viewValorServico(Request $request){   

        $this->authorize('admin');

        $dataForm = $request->all();

        $msg = isset($dataForm['msg']) ? $dataForm['msg'] : '';

        $valorServicos = ValorServico::all()->toArray();

        $i = 0;
        $tabelaItens = [];
        foreach ($valorServicos as $valorServico) {

            $servico = Servico::where('id',$valorServico['id_servico'])->first();

            $tabelaItens[$i] = [
                'id'            =>  $valorServico['id'],
                'servico'   => $servico->nome,
                'mes'   => $valorServico['mes'],
                'ano'  =>  $valorServico['ano'],
                'valor'  =>  $valorServico['valor'],
            ];

            $i++;
        }

        $valorServicos = $tabelaItens;

        return view('app.valorservico.viewValorServico')->with(['valorServicos'   =>  $valorServicos,
                                                        'msg'       =>  $msg]);
    }

    public function cadValorServico(){   

        $this->authorize('admin');

        $servicos = Servico::all();

        return view('app.valorservico.cadValorServico')->with([
                                                                "servicos" => $servicos,
        ]);
    }
    
    public function cadValorServicoSave(Request $request){   

        $this->authorize('admin');

        $request->validate([
            'id_servico' => 'required|exists:servicos,id',
            'mes' => 'required|max:2',
            'ano' => 'required|max:4',
            'valor' => 'required',
        ]);

        $dataForm = $request->all();
        $dataForm['valor'] = str_replace(",", ".", $dataForm['valor']);

        $valorServico = new ValorServico();

        $valorServico->id_servico = $dataForm['id_servico'];
        $valorServico->mes = $dataForm['mes'];
        $valorServico->ano = $dataForm['ano'];
        $valorServico->valor = $dataForm['valor'];
        $valorServico->save();
        

        return redirect()
        ->route('viewValorServico')
        ->with(['msg'   =>  'SALVO com sucesso',
                'type'  =>  'success']);
    }

    public function delValorServico(Request $request){   

        $this->authorize('admin');

        $request->validate([
            'id' => 'required|integer'
        ]);

        //reculera registro
        $valorServico = ValorServico::find($request->id);

        //deleta registro
        $valorServico->delete();

        return redirect()
            ->route('viewValorServico')
            ->with(['msg'   =>  'Valor do Servico DELETADO com Sucesso',
                    'type'  =>  'success']);
    }
    public function copyValorServico(){   

        $this->authorize('admin');

        return view('app.valorservico.copyValorServico');
    }

    public function copyValorServicoSave(Request $request){   

        $this->authorize('admin');

        $dataForm = $request->all();

        if ($dataForm['deMes'] == $dataForm['paraMes'] && $dataForm['deAno'] == $dataForm['paraAno']){
            
            return redirect()
            ->route('viewValorServico')
            ->with([
                'msg'   =>  'Datas Iguais',
                'type'  =>  'warning'
            ]);
        }

        $valorServicos = ValorServico::where('mes',$dataForm['deMes'])->where('ano',$dataForm['deAno'])->get();

        if (!isset($valorServicos[0])){
            
            return redirect()
            ->route('viewValorServico')
            ->with([
                'msg'   =>  'Não existem valores para Copia',
                'type'  =>  'warning'
            ]);
        }

        
        foreach ($valorServicos as $valorServico) {

            $verifica = ValorServico::where('id_servico',$valorServico->id_servico)->where('mes',$dataForm['paraMes'])->where('ano',$dataForm['paraAno'])->first();
           
            if (empty($verifica)){
                $copyToValorServico = new ValorServico();
    
                $copyToValorServico->id_servico = $valorServico->id_servico;
                $copyToValorServico->mes = $dataForm['paraMes'];
                $copyToValorServico->ano = $dataForm['paraAno'];
                $copyToValorServico->valor = $valorServico->valor;
                $copyToValorServico->save();
            }

        }
        
        

        return redirect()
            ->route('viewValorServico')
            ->with(['msg'   =>  'Copiado Valores de Servico com Sucesso',
                    'type'  =>  'success']);
    }

}
