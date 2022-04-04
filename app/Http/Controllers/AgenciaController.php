<?php

namespace App\Http\Controllers;

use App\Http\Resources\AgenciaResource;
use App\Services\AgenciaService;
use Illuminate\Http\Request;

class AgenciaController extends Controller
{
    public function store(AgenciaService $agenciaService, Request $request)
    {
        $ret = $agenciaService->cadastrarNovaConta($request->all());

        return response()->json([
            'data' => new AgenciaResource($ret)
        ], 201);
    }

    public function bancoemissor(AgenciaService $agenciaService, string $uuid, Request $request)
    {
        $agenciaService->alterarBancoEmissor($uuid, $request->banco_emissor);

        return response()->json([
            'data' => 'success'
        ], 200);
    }
}
