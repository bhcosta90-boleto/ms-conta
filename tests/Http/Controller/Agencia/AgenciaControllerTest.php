<?php

use App\Models\Agencia;
use App\Models\Conta;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AgenciaControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_cadastrar()
    {
        $response = $this->json('POST', 'agencias', [
            'email' => 'teste@email.com',
            'taxa' => 5
        ]);

        $response->assertResponseStatus(201);

        $this->seeInDatabase('agencias', [
            'email' => 'teste@email.com',
            'valor_taxa' => 5,
        ]);
    }

    public function test_banco_emissor()
    {
        $conta = Agencia::factory()->create();

        $response = $this->json('PUT', 'agencias/' . $conta->uuid . '/bancoemissor', [
            'banco_emissor' => $bancoEmissor = str()->uuid(),
        ]);

        $response->assertResponseStatus(200);

        $this->seeInDatabase('agencias', [
            'uuid' => $conta->uuid,
            'banco_emissor_id' => $bancoEmissor,
        ]);

        $response = $this->json('PUT', 'agencias/' . $conta->uuid . '/bancoemissor');

        $response->assertResponseStatus(200);

        $this->seeInDatabase('agencias', [
            'uuid' => $conta->uuid,
            'banco_emissor_id' => null,
        ]);
    }

}
