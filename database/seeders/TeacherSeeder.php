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
        $teacher = Teacher::create([
            'name' => 'Dewi Rahma, S.Pd',
            'code' => '90',
            'nip' => '0',
            'phone' => '0',
            'lesson_id' => 28,
            'user_id' => 4
        ]);
    }
}
