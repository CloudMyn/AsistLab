<?php

namespace Database\Factories;

use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mata_kuliah_id' => MataKuliah::inRandomOrder()->first()->id,
            'date' => now()->addDays(rand(1, 30)), // Menghasilkan tanggal acak
            'start_time' => rand(0, 23) . ':' . rand(0, 59), // Menghasilkan waktu mulai acak
            'end_time' => rand(0, 23) . ':' . rand(0, 59), // Menghasilkan waktu selesai acak
            'topic' => $this->faker->sentence(3), // Menghasilkan topik acak
            'room' => $this->faker->word(), // Menghasilkan nama ruangan acak
            'status' => 'OPEN',
        ];
    }
}
