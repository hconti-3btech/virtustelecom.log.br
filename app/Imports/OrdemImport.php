<?php

namespace App\Imports;

use App\Models\ItenContainer;
use App\Models\Ordem;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class OrdemImport implements ToCollection
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {   

        Validator::make($rows->toArray(), 
        [
            '*.0' => ['required', 'unique:ordems,ordem'],
            '*.4' => ['required','exists:servicos,id'],
            '*.6' => 'required',
        ],
        [
            '*.0.unique' => 'Já existe no cadastro / ',
            '*.0.required' => 'Campo obrigatorio / ',
            '*.4.required' => 'Campo obrigatorio / ',
            '*.6.required' => 'Campo obrigatorio / ',
        ]
        )->validate();
        
        $hoje = DateTime::createFromFormat('Y-m-d',DATE('Y-m-d'));


        foreach ($rows as $row) 
        {       

                $dataAbertura = str_replace("/", "-", $row[5]);

                $dataAbertura = date('Y-m-d', strtotime($dataAbertura));

                // $dataAbertura = DateTime::createFromFormat('Y-m-d', $dataAbertura);

                // $difHojeDataAbertura = date_diff($hoje,$dataAbertura);

                //verifica se esse codigo de cliente ja teve uma ativacao nos ultimos X dias

                $cliente = Ordem::where('cdc',$row[1])->latest('dt_fechamento')->first();
                

                if (!empty($cliente->dt_fechamento) && $cliente->id_servico_tipo == 1 && $cliente->status == 'finalizada' && $row[4] == 3){

                    
                    $dtFechamento = DateTime::createFromFormat('Y-m-d H:i:s', $cliente->dt_fechamento);

                    $difHojeDtFechamento = date_diff($hoje,$dtFechamento);

                    //muda o servico para IF
                    if ($difHojeDtFechamento->d <= 15 || $difHojeDtFechamento->m >=1 || $difHojeDtFechamento->y >=1){
                    
                        $ordem = new Ordem();

                        $ordem->ordem = $cliente->ordem.'/IFI';
                        $ordem->cdc = $cliente->cdc;
                        $ordem->nome_cli = $cliente->nome_cli;
                        $ordem->id_user_tec = $cliente->id_user_tec;
                        $ordem->id_servico_tipo = 12;
                        $ordem->dt_abertura = $dataAbertura;
                        $ordem->dt_fechamento = $dataAbertura;
                        $ordem->status = 'finalizada';
                        $ordem->save();
                    }
                    
                }
                
                if (!empty($cliente->dt_fechamento) && $cliente->id_servico_tipo == 3 && $cliente->status == 'finalizada' && $row[4] == 3){
                    
                    $dtFechamento = DateTime::createFromFormat('Y-m-d H:i:s', $cliente->dt_fechamento);

                    $difHojeDtFechamento = date_diff($hoje,$dtFechamento);

                    //muda o servico para IF
                    if ($difHojeDtFechamento->d <= 30 || $difHojeDtFechamento->m >=1 || $difHojeDtFechamento->y >=1){
                        
                        $ordem = new Ordem();

                        $ordem->ordem = $cliente->ordem.'/IRR';
                        $ordem->cdc = $cliente->cdc;
                        $ordem->nome_cli = $cliente->nome_cli;
                        $ordem->id_user_tec = $cliente->id_user_tec;
                        $ordem->id_servico_tipo = 13;
                        $ordem->dt_abertura = $dataAbertura;
                        $ordem->dt_fechamento = $dataAbertura;
                        $ordem->status = 'finalizada';
                        $ordem->save();
                    }
                    
                }

                //verifica se esse codigo de cliente ja teve um reparo nos ultimos X dias

                $ordem = new Ordem();

                $ordem->ordem = $row[0];
                $ordem->cdc = $row[1];
                $ordem->nome_cli = $row[2];
                $ordem->id_user_tec = $row[3];
                $ordem->id_servico_tipo = $row[4];
                $ordem->dt_abertura = $dataAbertura;
                $ordem->status = $row[6];
                $ordem->save();

        }
    }
}
