<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItenContainer extends Model
{
    use HasFactory;
    protected $fillable = ['id_container','id_produto','qtd','controle','mac'];
}
