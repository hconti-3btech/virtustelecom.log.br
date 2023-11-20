<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    use HasFactory;
    protected $fillable = ['id_origem','id_destino','id_produto','qtd','controle','mac','solicitante','aprovante','ordem','requisicao','status'];
}
