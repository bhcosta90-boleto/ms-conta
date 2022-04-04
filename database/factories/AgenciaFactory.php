<?php

namespace Database\Factories;

use App\Models\Agencia as Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgenciaFactory extends Factory
{
    protected $model = Model::class;

    public function definition(): array
    {
    	return [
            'email' => $this->faker->safeEmail(),
    	];
    }
}
