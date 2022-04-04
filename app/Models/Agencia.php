<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PJBank\Package\Models\Traits\SendQueue;
use PJBank\Package\Models\Traits\UuidGenerate;

class Agencia extends Model
{
    use HasFactory, SendQueue, UuidGenerate;

    public $fillable = [
        'banco_emissor_id',
        'email',
        'valor_taxa',
    ];
}
