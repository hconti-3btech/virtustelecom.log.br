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
        Schema::create('valor_servicos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_servico'); // id servico
            $table->integer('mes'); // id servico
            $table->integer('ano'); // id servico
            $table->float('valor'); // id servico
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
        Schema::dropIfExists('valor_servicos');
    }
};
