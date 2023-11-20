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
        Schema::create('iten_containers', function (Blueprint $table) {
            $table->id();
            $table->integer('id_container');
            $table->integer('id_produto');
            $table->float('qtd');
            $table->string('controle')->nullable();
            $table->string('mac')->nullable();
            $table->timestamps();
            // CREATE UNIQUE INDEX unicContainerProdutoControle ON iten_containers (`id_container`,`id_produto` ,`controle`); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iten_containers');
    }
};
