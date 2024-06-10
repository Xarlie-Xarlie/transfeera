<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf_cnpj',
        'banco',
        'agencia',
        'conta',
        'status',
        'pix_key_type',
        'pix_key',
        'email'
    ];
}
