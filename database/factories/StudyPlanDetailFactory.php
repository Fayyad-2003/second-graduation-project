<?php

namespace Database\Factories;

use App\Models\StudyPlan;
use App\Models\AcademicClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudyPlanDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'study_plan_id' => StudyPlan::factory(),
            'class_id' => AcademicClass::factory(),
        ];
    }
}
