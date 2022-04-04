<?php

namespace App\Services;

use App\Models\Agencia;

final class AgenciaService
{

    use Traits\ValidateTrait, Traits\GetFindTrait;

    private static $data;

    public function __construct(private Agencia $repository)
    {
    }

    public function cadastrarNovaConta(array $data)
    {
        $data = $this->validate($data, [
            'email' => 'required|min:3|max:100|email',
            'taxa' => "nullable|numeric|min:0|max:150",
        ]);

        $data['valor_taxa'] = $data['taxa'] ?? null;

        $obj = $this->repository->fill($data);
        $obj->save();
        return $obj;
    }

    public function alterarBancoEmissor(string $uuid, string $bancoemissor = null)
    {
        $this->validate(['banco_emissor' => $bancoemissor], [
            'banco_emissor' => 'nullable|uuid',
        ]);

        $obj = $this->repository->where('uuid', $uuid)->firstOrFail();

        $obj->update(['banco_emissor_id' => $bancoemissor]);

        return $obj;
    }
}
