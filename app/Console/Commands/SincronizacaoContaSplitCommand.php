<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PJBank\Package\Support\ConsumeSupport;
use PJBank\Package\Support\SynchronizeTableSupport;

class SincronizacaoContaSplitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contasplit:sincronizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronização de conta split';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private ConsumeSupport $consumeSupport,
        private SynchronizeTableSupport $synchronizeTableSupport
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rules = [
            'credencial' => 'required',
            'chave' => 'required',
            'nome' => 'required',
            'cpfcnpj' => 'required',
            'created_at' => 'required',
            'updated_at' => 'required',
            'split' => 'nullable',
            'banco' => 'required',
            'agencia' => 'required',
            'conta' => 'required'
        ];

        $this->consumeSupport->function('contas', 'app.ms_cobrancas.table.conta_splits.*', $rules, function ($data) {
            $this->synchronizeTableSupport->sync('contas', "credencial", $data["credencial"], [
                'credencial' => $data['credencial'],
                'chave' => $data['chave'],
                'banco_codigo' => $data['banco'],
                'banco_agencia' => $data['agencia'],
                'banco_conta' => $data['conta'],
                'split' => $data['split'] ?? null,
                "nome" => $data['nome'],
                "cpfcnpj" => $data['cpfcnpj'],
            ]);
        });
    }
}
