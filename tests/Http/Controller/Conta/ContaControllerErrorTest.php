<?php

use App\Models\Conta;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ContaControllerErrorTest extends TestCase
{
    public function test_obrigatorio()
    {
        $response = $this->json('POST', 'contas', []);

        $response->seeJson([
            Lang::get('validation.required', ['attribute' => 'nome'])
        ]);

        $response->seeJson([
            Lang::get('validation.required', ['attribute' => 'email'])
        ]);

        $response->seeJson([
            Lang::get('validation.required', ['attribute' => 'cpfcnpj'])
        ]);

        $response->seeJson([
            Lang::get('validation.required', ['attribute' => 'telefone'])
        ]);

        $response->seeJson([
            Lang::get('validation.required', ['attribute' => 'banco repasse'])
        ]);

        $response->seeJson([
            Lang::get('validation.required', ['attribute' => 'agencia repasse'])
        ]);

        $response->seeJson([
            Lang::get('validation.required', ['attribute' => 'conta repasse'])
        ]);
    }

    public function test_numero()
    {
        $response = $this->json('POST', 'agencias', [
            'email' => 'a',
            'taxa' => 'a',
        ]);

        $response->seeJson([
            Lang::get('validation.numeric', ['attribute' => 'taxa', 'min' => 3])
        ]);
    }

    public function test_min()
    {
        $response = $this->json('POST', 'contas', [
            'nome' => 'a',
            'email' => 'a',
            'cpfcnpj' => 'a',
            'telefone' => 'a',
            'banco_repasse' => 'a',
            'agencia_repasse' => 'a',
            'conta_repasse' => 'a',
            'taxa' => -5,
        ]);

        $response->seeJson([
            Lang::get('validation.min.string', ['attribute' => 'nome', 'min' => 3])
        ]);

        $response->seeJson([
            Lang::get('validation.min.string', ['attribute' => 'email', 'min' => 3])
        ]);

        $response->seeJson([
            Lang::get('validation.min.string', ['attribute' => 'cpfcnpj', 'min' => 11])
        ]);

        $response->seeJson([
            Lang::get('validation.min.string', ['attribute' => 'telefone', 'min' => 8])
        ]);

        $response->seeJson([
            Lang::get('validation.min.numeric', ['attribute' => 'taxa', 'min' => 0])
        ]);

        $response->seeJson([
            Lang::get('validation.min.string', ['attribute' => 'banco repasse', 'min' => 3])
        ]);

        $response->seeJson([
            Lang::get('validation.min.string', ['attribute' => 'agencia repasse', 'min' => 3])
        ]);

        $response->seeJson([
            Lang::get('validation.min.string', ['attribute' => 'conta repasse', 'min' => 3])
        ]);
    }

    public function test_max()
    {
        $response = $this->json('POST', 'contas', [
            'nome' => str_repeat('a', 500),
            'email' => str_repeat('a', 500),
            'cpfcnpj' => str_repeat('a', 500),
            'telefone' => str_repeat('a', 500),
            'banco_repasse' => str_repeat('a', 500),
            'agencia_repasse' => str_repeat('a', 500),
            'conta_repasse' => str_repeat('a', 500),
        ]);

        $response->seeJson([
            Lang::get('validation.max.string', ['attribute' => 'nome', 'max' => 150])
        ]);

        $response->seeJson([
            Lang::get('validation.max.string', ['attribute' => 'email', 'max' => 100])
        ]);

        $response->seeJson([
            Lang::get('validation.max.string', ['attribute' => 'cpfcnpj', 'max' => 14])
        ]);

        $response->seeJson([
            Lang::get('validation.max.string', ['attribute' => 'telefone', 'max' => 20])
        ]);

        $response->seeJson([
            Lang::get('validation.max.string', ['attribute' => 'banco repasse', 'max' => 5])
        ]);

        $response->seeJson([
            Lang::get('validation.max.string', ['attribute' => 'agencia repasse', 'max' => 20])
        ]);

        $response->seeJson([
            Lang::get('validation.max.string', ['attribute' => 'conta repasse', 'max' => 20])
        ]);
    }

    public function test_banco_emissor()
    {
        $this->artisan('migrate:fresh');

        $conta = Conta::factory()->create();

        $response = $this->json('PUT', 'contas/' . $conta->credencial . '/bancoemissor', ['banco_emissor' => 'a']);

        $response->seeJson([
            Lang::get('validation.uuid', ['attribute' => 'banco emissor'])
        ]);

        $this->artisan('migrate:rollback');
    }

    public function test_exists()
    {
        $this->artisan('migrate:fresh');

        $response = $this->json('POST', 'contas', [
            'agencia' => '123',
        ]);

        $response->seeJson([
            Lang::get('validation.exists', ['attribute' => 'agencia'])
        ]);

        $this->artisan('migrate:rollback');
    }

    public function test_in()
    {
        $response = $this->json('POST', 'contas', [
            'tipo' => '123',
        ]);

        $response->seeJson([
            Lang::get('validation.in', ['attribute' => 'tipo'])
        ]);
    }

    public function test_email()
    {
        $response = $this->json('POST', 'contas', [
            'nome' => str_repeat('a', 500),
            'email' => str_repeat('a', 500),
            'cpfcnpj' => str_repeat('a', 500),
            'telefone' => str_repeat('a', 500),
        ]);

        $response->seeJson([
            Lang::get('validation.email', ['attribute' => 'email'])
        ]);
    }
}
