<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContaResource;
use App\Services\ContaService;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    public function store(ContaService $contaService, Request $request)
    {
        $chave = sha1(str()->uuid());

        $request->request->add([
            'chave' => $chave,
        ]);

        $ret = $contaService->cadastrarNovaConta($request->all());

        return response()->json([
            'data' => new ContaResource($ret)
        ], 201);
    }

    public function bancoemissor(ContaService $contaService, string $uuid, Request $request)
    {
        $contaService->alterarBancoEmissor($uuid, $request->banco_emissor);

        return response()->json([
            'data' => 'success'
        ], 200);
    }

    public function valorminimo(ContaService $contaService, string $uuid, Request $request)
    {
        $contaService->alterarValorMinimo($uuid, $request->valor);

        return response()->json([
            'data' => 'success'
        ], 200);
    }

    public function status(ContaService $contaService, string $uuid, Request $request){

        $contaService->alterarStatus($uuid, $request->status);

        return response()->json([
            'data' => 'success'
        ], 200);
    }
}
