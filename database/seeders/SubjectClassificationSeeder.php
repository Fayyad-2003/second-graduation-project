<?php

namespace Database\Seeders;

use App\Models\SubjectClassification;
use Illuminate\Database\Seeder;

class SubjectClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = [
            ['name' => 'متطلبات جامعة', 'slug' => 'university-requirements'],
            ['name' => 'متطلبات كلية', 'slug' => 'college-requirements'],
            ['name' => 'متطلبات تخصص', 'slug' => 'specialization-requirements'],
            ['name' => 'اختياري جامعة', 'slug' => 'university-optional'],
            ['name' => 'اختياري كلية', 'slug' => 'college-optional'],
            ['name' => 'اختياري تخصص', 'slug' => 'specialization-optional'],
        ];

        foreach ($classifications as $classification) {
            SubjectClassification::updateOrCreate(
                ['slug' => $classification['slug']],
                ['name' => $classification['name']]
            );
        }
    }
}
