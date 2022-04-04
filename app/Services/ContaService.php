<?php

namespace App\Services;

use App\Models\Conta;

final class ContaService
{

    use Traits\ValidateTrait;

    public function __construct(private Conta $repository, private AgenciaService $agenciaService)
    {
    }

    public function cadastrarNovaConta(array $data)
    {
        $data = $this->validate($data, [
            'nome' => 'required|min:3|max:150',
            'cpfcnpj' => 'required|min:11|max:14|string',
            'email' => 'required|min:3|max:100|email',
            'telefone' => 'required|min:8|max:20|string',
            'chave' => 'required|string',
            'agencia' => 'nullable|exists:agencias,uuid',
            'tipo' => 'nullable|in:cartao',
            'taxa' => "nullable|numeric|min:0|max:150",
            'banco_repasse' => 'required|min:3|max:5',
            'agencia_repasse' => 'required|min:3|max:20',
            'conta_repasse' => 'required|min:3|max:20',
        ]);

        $data['agencia_id'] = $this->agenciaService->findByUuid($data['agencia'] ?? null, 'id');
        $data['valor_taxa'] = $data['taxa'] ?? null;

        $obj = $this->repository->fill($data + [
            'banco_codigo' => $data['banco_repasse'],
            'banco_agencia' => $data['agencia_repasse'],
            'banco_conta' => $data['conta_repasse'],
        ]);

        $obj->chave = $data['chave'];

        $obj->save();
        $obj->refresh();
        return $obj;
    }

    public function alterarBancoEmissor(string $credencial, string $bancoemissor = null)
    {
        $this->validate(['banco_emissor' => $bancoemissor], [
            'banco_emissor' => 'nullable|uuid',
        ]);

        $obj = $this->repository->where('credencial', $credencial)->firstOrFail();

        $obj->update(['banco_emissor_id' => $bancoemissor]);

        return $obj;
    }

    public function alterarValorMinimo(string $credencial, string $valorMinimo = null)
    {
        $obj = $this->repository->where('credencial', $credencial)->firstOrFail();

        $this->validate(['valor_minimo' => $valorMinimo], [
            'valor_minimo' => 'required|numeric|min:0|max:10',
        ]);

        $obj->update(['valor_minimo' => $valorMinimo]);

        return $obj;
    }

    public function alterarStatus(string $credencial, string $status) {
        $this->validate(['status' => $status], [
            'status' => 'required|string|min:1|max:50',
        ]);

        $obj = $this->repository->where('credencial', $credencial)->firstOrFail();

        $obj->update(['status' => $status]);

        return $obj;
    }
}
