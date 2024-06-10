<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receiver>
 */
class ReceiverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pix = fake()->randomElement([
            ["CPF", fake()->numerify('###.###.###-##')],
            ["CNPJ", fake()->numerify('##.###.###/####-##')],
            ["EMAIL", fake()->email],
            ["TELEFONE", fake()->numerify('+## (##) #####-####')],
            ["CHAVE_ALEATORIA", fake()->uuid],
        ]);

        return [
            'name' => fake()->name,
            'cpf_cnpj' => fake()->unique()->randomElement([fake()->numerify('###.###.###-##'), fake()->numerify('##.###.###/####-##')]),
            'banco' => fake()->company,
            'agencia' => fake()->randomNumber(4),
            'conta' => fake()->bankAccountNumber,
            'status' => fake()->randomElement(['rascunho', 'validado']),
            'pix_key_type' => $pix[0],
            'pix_key' => $pix[1],
            'email' => fake()->unique()->safeEmail,
        ];
    }
}
