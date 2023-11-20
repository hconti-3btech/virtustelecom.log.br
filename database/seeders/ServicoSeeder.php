<?php

namespace Database\Seeders;

use App\Models\Servico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $servico = new Servico();

       $servico->id = 1;
       $servico->nome = 'ATIVAÇÃO BL';
       $servico->status = 'ativo';
       $servico->save();
       
       $servico = new Servico();

       $servico->id = 2;
       $servico->nome = 'ATIVAÇÃO TV';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();

       $servico->id = 3;
       $servico->nome = 'REPARO';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       $servico->id = 4;
       $servico->nome = 'MUDANÇA DE ENDEREÇO BL';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();

       $servico->id = 5;
       $servico->nome = 'MUDANÇA DE ENDEREÇO TV';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       
       $servico->id = 6;
       $servico->nome = 'ALTERAÇÃO DE LOCAL DE PONTO';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       
       $servico->id = 7;
       $servico->nome = 'UPGRADE';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       
       $servico->id = 8;
       $servico->nome = 'PONTO ADICIONAL';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       
       $servico->id = 9;
       $servico->nome = 'MUDANÇA DE COMODO';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       
       $servico->id = 10;
       $servico->nome = 'RECOLHIMENTO';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       
       $servico->id = 11;
       $servico->nome = 'SERVIÇO SEM FINANCEIRO';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       
       $servico->id = 12;
       $servico->nome = 'IFI';
       $servico->status = 'ativo';
       $servico->save();

       $servico = new Servico();
       
       $servico->id = 13;
       $servico->nome = 'IRR';
       $servico->status = 'ativo';
       $servico->save();
    }
}
