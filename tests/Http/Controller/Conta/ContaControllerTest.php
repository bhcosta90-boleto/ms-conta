<?php

use App\Models\Agencia;
use App\Models\Conta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class ContaControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_cadastrar()
    {
        $response = $this->json('POST', 'contas', [
            'nome' => 'teste',
            'email' => 'teste@email.com',
            'cpfcnpj' => '99999999999',
            'telefone' => '99999999',
            'taxa' => 5,
            'banco_repasse' => "033",
            'agencia_repasse' => '34156',
            'conta_repasse' => '34156',
        ]);

        $response->assertResponseStatus(201);

        $this->seeInDatabase('contas', [
            'credencial' => $response->response->json('data.credencial'),
            'chave' => sha1($response->response->json('data.chave')),
            'nome' => 'teste',
            'email' => 'teste@email.com',
            'cpfcnpj' => '99999999999',
            'telefone' => '99999999',
            'banco_codigo' => "033",
            'banco_agencia' => '34156',
            'banco_conta' => '34156',
            'tipo' => null,
            'valor_taxa' => 5,
        ]);
    }

    public function test_cadastrar_com_agencia()
    {
        $agencia = Agencia::factory()->create();

        $response = $this->json('POST', 'contas', [
            'nome' => 'teste',
            'email' => 'teste@email.com',
            'cpfcnpj' => '99999999999',
            'telefone' => '99999999',
            'agencia' => $agencia->uuid,
            'banco_repasse' => "033",
            'agencia_repasse' => '34156',
            'conta_repasse' => '34156',
        ]);

        $response->assertResponseStatus(201);

        $this->seeInDatabase('contas', [
            'credencial' => $response->response->json('data.credencial'),
            'chave' => sha1($response->response->json('data.chave')),
            'nome' => 'teste',
            'email' => 'teste@email.com',
            'cpfcnpj' => '99999999999',
            'telefone' => '99999999',
            'banco_codigo' => "033",
            'banco_agencia' => '34156',
            'banco_conta' => '34156',
            'agencia_id' => $agencia->id,
        ]);
    }

    public function test_cadastrar_cartao()
    {
        $response = $this->json('POST', 'contas', [
            'nome' => 'teste',
            'email' => 'teste@email.com',
            'cpfcnpj' => '99999999999',
            'telefone' => '99999999',
            'tipo' => 'cartao',
            'banco_repasse' => "033",
            'agencia_repasse' => '34156',
            'conta_repasse' => '34156',
        ]);

        $response->assertResponseStatus(201);

        $this->seeInDatabase('contas', [
            'credencial' => $response->response->json('data.credencial'),
            'chave' => sha1($response->response->json('data.chave')),
            'nome' => 'teste',
            'email' => 'teste@email.com',
            'cpfcnpj' => '99999999999',
            'telefone' => '99999999',
            'banco_codigo' => "033",
            'banco_agencia' => '34156',
            'banco_conta' => '34156',
            'tipo' => 'cartao'
        ]);
    }

    public function test_banco_emissor()
    {
        $conta = Conta::factory()->create();

        $response = $this->json('PUT', 'contas/' . $conta->credencial . '/bancoemissor', [
            'banco_emissor' => $bancoEmissor = str()->uuid(),
        ]);

        $response->assertResponseStatus(200);

        $this->seeInDatabase('contas', [
            'credencial' => $conta->credencial,
            'banco_emissor_id' => $bancoEmissor,
        ]);

        $response = $this->json('PUT', 'contas/' . $conta->credencial . '/bancoemissor');

        $response->assertResponseStatus(200);

        $this->seeInDatabase('contas', [
            'credencial' => $conta->credencial,
            'banco_emissor_id' => null,
        ]);
    }

    public function test_valor_minimo()
    {
        $conta = Conta::factory()->create();

        $response = $this->json('PUT', 'contas/' . $conta->credencial . '/valorminimo', [
            'valor' => 2.50,
        ]);

        $response->assertResponseStatus(200);

        $this->seeInDatabase('contas', [
            'credencial' => $conta->credencial,
            'valor_minimo' => 2.50,
        ]);
    }

}
