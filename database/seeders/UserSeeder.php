<?php

namespace Database\Seeders;

use App\Models\Container;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        $user->name = 'Administrador';
        $user->email = 'administrador@virtustelecom.log.br';
        $user->password = Hash::make('admin.$3nh@'); //
        $user->status = 'ativo';
        $user->nv_acesso = 'admin';
        $user->created_at = date('Y-m-d H:m:i');
        $user->updated_at = date('Y-m-d H:m:i');

        $user->save();

        // Almox
        $user = new User();

        $user->name = 'Almoxarifado';
        $user->email = 'almox@virtustelecom.log.br';
        $user->password = Hash::make('almox.$3nh@');; //
        $user->status = 'ativo';
        $user->nv_acesso = 'almox';
        $user->created_at = date('Y-m-d H:m:i');
        $user->updated_at = date('Y-m-d H:m:i');

        $user->save();

        // Apoio
        $user = new User();

        $user->name = 'Apoio';
        $user->email = 'apoio@virtustelecom.log.br';
        $user->password = Hash::make('apoio.$3nh@'); //
        $user->status = 'ativo';
        $user->nv_acesso = 'apoio';
        $user->created_at = date('Y-m-d H:m:i');
        $user->updated_at = date('Y-m-d H:m:i');

        $user->save();



    }
}
