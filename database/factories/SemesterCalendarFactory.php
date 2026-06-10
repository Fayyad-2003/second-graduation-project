<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterCalendarFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'بداية الفصل الدراسي', 'نهاية التسجيل', 'إجازة عيد الفطر', 'امتحانات منتصف الفصل',
            'إجازة عيد الأضحى', 'بداية الامتحانات النهائية', 'نهاية الفصل الدراسي',
            'يوم التأسيس', 'إجازة استقلال', 'يوم العلم',
        ];
        $descriptions = [
            'يوم بداية الفصل الدراسي الجديد',
            'آخر يوم لتسجيل وتعديل الخطة الدراسية',
            'إجازة عيد الفطر المبارك',
            'بداية امتحانات منتصف الفصل الدراسي',
            'إجازة عيد الأضحى المبارك',
            'بداية امتحانات نهاية الفصل الدراسي',
            'آخر يوم في الفصل الدراسي الحالي',
            'احتفال بيوم التأسيس الوطني',
            'إجازة يوم الاستقلال الوطني',
            'احتفال بيوم العلم',
        ];
        $types = ['academic', 'holiday', 'exam', 'event', 'national'];

        return [
            'academic_year_id' => AcademicYear::factory(),
            'week_number' => fake()->numberBetween(1, 16),
            'date' => fake()->date(),
            'title' => fake('ar_SA')->randomElement($titles),
            'description' => fake('ar_SA')->randomElement($descriptions),
            'type' => fake()->randomElement($types),
            'is_active' => true,
        ];
    }
}
