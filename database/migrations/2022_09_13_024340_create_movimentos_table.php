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
        Schema::create('movimentos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_origem'); // origem do movimento
            $table->integer('id_destino'); // destino do movimento
            $table->integer('id_produto'); // produto
            $table->float('qtd')->default('1'); // qtd do produto
            $table->string('controle')->nullable(); // ex: patrimonio etc...
            $table->string('mac')->nullable(); // MAC
            $table->string('solicitante')->nullable(); // id do usuario solicitante
            $table->string('aprovante')->nullable(); // id usuario q aprovou / entegou
            $table->string('ordem')->nullable(); // ordem de servico que gerou esse movimento
            $table->enum('status',['AGUARDANDO','CANCELADO','CONFIRMADO'])->default('AGUARDANDO');
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
        Schema::dropIfExists('movimentos');
    }
};
