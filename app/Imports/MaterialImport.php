<?php

namespace App\Imports;

use App\Models\ItenContainer;
use App\Models\Movimento;
use App\Models\Produto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Validators\Failure;

class MaterialImport implements ToCollection, SkipsOnFailure
{
    use Importable, SkipsFailures;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {

        Validator::make($rows->toArray(), 
        [
            '*.0' => ['required', 'exists:containers,id'],
            '*.1' => ['required', 'exists:produtos,id'],
            '*.2' => 'required',
        ],
        [
            '*.0.required' => 'Destino Campo obrigatorio / ',
            '*.0.exists' => 'Destino nao existe / ',
            '*.1.required' => 'Produto Campo obrigatorio / ',
            '*.1.exists' => 'Produto nao existe / ',
            '*.2.required' => 'Qtd Campo obrigatorio / ',
        ]
        )->validate();

        //  0       1       2     3     4    5
        //destino/produto/qtd/controle/mac/status

        foreach ($rows as $row) 
        {       
            
            $itenContainerDestino = ItenContainer::where('id_container',$row[0])->where('id_produto',$row[1])->where('controle',$row[3])->first();
            
            //dd($itenContainerDestino);

            //verifica se ja existe esse produto
            if (empty($itenContainerDestino)){
                
                //adiciona
                $itenContainerDestino = new ItenContainer();

                $itenContainerDestino->id_container = $row[0];
                $itenContainerDestino->id_produto = $row[1];
                $itenContainerDestino->qtd = $row[2];
                $itenContainerDestino->controle = $row[3];
                $itenContainerDestino->mac = $row[4];
                $itenContainerDestino->save();

            }else{//atualiza

                $qtd = $itenContainerDestino->qtd + $row[2];

                ItenContainer::where('id_container',$row[0])->where('id_produto',$row[1])->where('controle',$row[3])->update(
                    [
                        'qtd' => $qtd,
                    ]
                );
            }
        
            //  0       1       2     3     4    5 
            //destino/produto/qtd/controle/mac/status
            
            //inseri movimento 
            $movimento = new Movimento; 
    
            $movimento->id_origem   = 2;
            $movimento->id_destino  = $row[0];
            $movimento->id_produto  = $row[1];
            $movimento->qtd         = $row[2];
            $movimento->controle    = $row[3];
            $movimento->mac         = $row[4];
            $movimento->status      = 'CONFIRMADO';
            $movimento->save();

        }
    }

    public function onFailure(Failure ...$failure)
    {
        # code...
    }
}
