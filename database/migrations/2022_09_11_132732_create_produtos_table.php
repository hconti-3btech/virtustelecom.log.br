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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); 
            $table->longText('descricao')->nullable();
            $table->string('un')->nullable(); // unidade
            $table->string('fabricante')->nullable(); // fabricante / modelo (apresenta no relatorio de produto)
            $table->string('est_min')->nullable(); //valor do estoque minimo 
            $table->string('est_pedido')->nullable(); //valor que sera feito o pedido quando baixar o estoque minimo
            $table->string('path')->nullable(); // caminho da imagem
            $table->enum('unico',['s','n'])->default('n'); // caminho da imagem
            $table->enum('status',['ativo','desativo'])->default('ativo'); //estado do produto 
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
        Schema::dropIfExists('produtos');
    }
};
