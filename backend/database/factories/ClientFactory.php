<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->firstName(),
            'prenom' => fake()->lastName(),
            'date_naissance' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'numero_cin' => fake()->unique()->bothify('?######'),
            'email' => fake()->unique()->email(),
            'mot_de_passe' => Hash::make('password'),
            "telephone" => fake()->phoneNumber(),
            "adresse" => fake()->address()
        ];
    }
}
