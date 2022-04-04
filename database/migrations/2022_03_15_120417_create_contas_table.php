<?php

use App\Models\Conta;
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
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->uuid('banco_emissor_id')->nullable()->index();
            $table->foreignId('agencia_id')->nullable()->constrained('agencias');
            $table->enum('tipo', Conta::TIPOS)->nullable();
            $table->string('credencial')->index();
            $table->string('chave');
            $table->string('nome');
            $table->string('cpfcnpj');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('status')->default(Conta::STATUS_PENDENTE);
            $table->string('banco_codigo', 5);
            $table->string('banco_agencia', 20);
            $table->string('banco_conta', 20);
            $table->unsignedDouble('valor_taxa')->nullable();
            $table->boolean('split')->nullable();
            $table->unsignedDouble('valor_minimo')->default(7.50);
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
        Schema::dropIfExists('contas');
    }
};
