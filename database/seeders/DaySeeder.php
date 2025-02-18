<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',
        ];

        foreach ($days as $day) {
            Day::insert([
                'name' => $day,
            ]);
        }
    }
}
