<?php

namespace Database\Factories;

use App\Models\Frekuensi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Praktikan>
 */
class PraktikanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'kelas' => $this->faker->randomElement(['A', 'B', 'C']),
            'jurusan' => $this->faker->randomElement(['TI', 'SI', 'DKV']),
            'frekuensi_id' => Frekuensi::inRandomOrder()->first()->id
        ];
    }
}
