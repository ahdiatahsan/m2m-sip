<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = Teacher::factory()->create([
            'name' => 'Guru',
            'code' => 'A1',
            'nip' => '123456789',
            'phone' => '081212345678',
            // 'address' => 'Makassar',
            'user_id' => 3
        ]);
    }
}
