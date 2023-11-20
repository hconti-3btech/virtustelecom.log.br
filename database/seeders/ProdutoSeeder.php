<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $produto = new Produto();

        $produto->id = 999;
        $produto->nome = "RETIRADA - ONU NOKIA/HUAWEI/FIBERHOME";
        $produto->descricao = "";
        $produto->un = "UN";
        $produto->fabricante = NULL;
        $produto->est_min = 0;
        $produto->est_pedido = 0;
        $produto->unico = "s";
        $produto->save();
        
    
    }
}
