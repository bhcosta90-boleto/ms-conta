<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PJBank\Package\Models\Traits\SendQueue;
use PJBank\Package\Models\Traits\UuidGenerate;

class Conta extends Model
{
    use HasFactory, SendQueue;

    const TIPO_BOLETO = 'boleto';
    const TIPO_CARTAO = 'cartao';
    const TIPO_SPLIT = 'split';

    const TIPOS = [self::TIPO_BOLETO, self::TIPO_CARTAO];

    const STATUS_PENDENTE = 'pendente';
    const STATUS_APROVADO = 'aprovado';

    public static function booted(): void
    {
        parent::creating(function ($obj) {
            $obj->credencial = sha1(str()->uuid());
            $obj->chave = sha1($obj->chave);
        });
    }

    public function agencia()
    {
        return $this->belongsTo(Agencia::class);
    }

    public $fillable = [
        'banco_emissor_id',
        'agencia_id',
        'credencial',
        'chave',
        'nome',
        'cpfcnpj',
        'email',
        'telefone',
        'tipo',
        'valor_taxa',
        'split',
        'status',
        'banco_codigo',
        'banco_agencia',
        'banco_conta',
        'valor_minimo',
    ];

    public function sendQueue()
    {
        $ret = $this->toArray();

        if ($this->agencia_id) {
            $ret['agencia_id'] = $this->agencia->uuid;
        }

        return $ret;
    }
}
