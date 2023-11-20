<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nome do usuario
            $table->string('email')->unique(); //email
            $table->timestamp('email_verified_at')->nullable(); //data de veificacao do email
            $table->string('password'); //senha para acesso
            $table->string('regiao'); // qual o banco de dados usaremos
            $table->enum('status',['ativo','desativo','pre_registro'])->default('pre_registro'); //estado do usuario 
            $table->enum('nv_acesso',['admin','almox','controle','tecnico','supervisor','apoio'])->default('tecnico'); //nivel de acesso 
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
