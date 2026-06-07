<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = [
            ['name' => 'Required', 'slug' => 'required'],
            ['name' => 'Optional', 'slug' => 'optional'],
            ['name' => 'College Required', 'slug' => 'college-required'],
            ['name' => 'University Required', 'slug' => 'university-required'],
        ];

        foreach ($classifications as $classification) {
            \App\Models\SubjectClassification::updateOrCreate(
                ['slug' => $classification['slug']],
                ['name' => $classification['name']]
            );
        }
    }
}
