<?php

use App\Models\Agencia;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class AgenciaControllerErrorTest extends TestCase
{
    public function test_obrigatorio()
    {
        $response = $this->json('POST', 'agencias', []);

        $response->seeJson([
            Lang::get('validation.required', ['attribute' => 'email'])
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
        $response = $this->json('POST', 'agencias', [
            'email' => 'a',
            'taxa' => -5,
        ]);

        $response->seeJson([
            Lang::get('validation.min.string', ['attribute' => 'email', 'min' => 3])
        ]);

        $response->seeJson([
            Lang::get('validation.min.numeric', ['attribute' => 'taxa', 'min' => 0])
        ]);
    }

    public function test_max()
    {
        $response = $this->json('POST', 'agencias', [
            'email' => str_repeat('a', 500),
            'taxa' => 200,
        ]);

        $response->seeJson([
            Lang::get('validation.max.string', ['attribute' => 'email', 'max' => 100])
        ]);

        $response->seeJson([
            Lang::get('validation.max.numeric', ['attribute' => 'taxa', 'max' => 150])
        ]);
    }

    public function test_banco_emissor()
    {
        $this->artisan('migrate:fresh');

        $conta = Agencia::factory()->create();

        $response = $this->json('PUT', 'agencias/' . $conta->uuid . '/bancoemissor', ['banco_emissor' => 'a']);

        $response->seeJson([
            Lang::get('validation.uuid', ['attribute' => 'banco emissor'])
        ]);

        $this->artisan('migrate:rollback');
    }

    public function test_email()
    {
        $response = $this->json('POST', 'agencias', [
            'email' => str_repeat('a', 500),
        ]);

        $response->seeJson([
            Lang::get('validation.email', ['attribute' => 'email'])
        ]);
    }
}
