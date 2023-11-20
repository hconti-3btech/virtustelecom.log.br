<?php

namespace Database\Seeders;

use App\Models\Container;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $container = new Container();
        $container->id = 1;
        $container->nome = 'MASTER';
        $container->pai = '0';
        $container->id_user = '1';
        $container->save();


        $container = new Container();
        $container->id = 2;
        $container->nome = 'ENTRADA';
        $container->pai = '0';
        $container->id_user = '1';
        $container->save();

        $container = new Container();
        $container->id = 3;
        $container->nome = 'SAIDA';
        $container->pai = '0';
        $container->id_user = '1';
        $container->save();

        $container = new Container();
        $container->id = 4;
        $container->nome = 'ALMOXARIFADO';
        $container->pai = '1';
        $container->id_user = '2';
        $container->save();
    }
}
