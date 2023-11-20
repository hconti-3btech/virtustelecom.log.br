<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordem extends Model
{
    use HasFactory;
    protected $fillable = ['id','ordem','cdc','nome_cli','id_user_tec','motivo','id_servico_tipo','dt_abertura','dt_fechamento','status'];

}
