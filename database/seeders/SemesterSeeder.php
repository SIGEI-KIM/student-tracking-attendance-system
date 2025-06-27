<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester; // Make sure to import your Semester model

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Semester::create([
            'name' => 'Semester 1',
            'year_number' => 1, // Or null if semester isn't tied to a specific year here
        ]);

        Semester::create([
            'name' => 'Semester 2',
            'year_number' => 1,
        ]);

        Semester::create([
            'name' => 'Semester 1',
            'year_number' => 2,
        ]);

        Semester::create([
            'name' => 'Semester 2',
            'year_number' => 2,
        ]);

        // Add more semesters as per your academic structure
    }
}