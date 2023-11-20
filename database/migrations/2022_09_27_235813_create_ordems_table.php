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
        Schema::create('ordems', function (Blueprint $table) {
            $table->id();
            $table->string('ordem')->unique();
            $table->string('cdc')->nullable(); //codigo do contrato
            $table->string('nome_cli')->nullable(); //Nome cliente
            $table->integer('id_user_tec')->nullable(); //id do usuario tecnico
            $table->string('motivo')->nullable(); //codigo do contrato
            $table->string('id_servico_tipo'); //codigo do contrato
            $table->timestamp('dt_abertura'); //data de abertura 
            $table->timestamp('dt_fechamento')->nullable(); //data fechamento
            $table->enum('status',['aberta','finalizada','atribuida','informada','cancelada'])->default('aberta'); //estado da ordem 
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
        Schema::dropIfExists('ordems');
    }
};
