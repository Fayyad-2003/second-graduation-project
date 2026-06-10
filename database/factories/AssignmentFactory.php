<?php

namespace Database\Factories;

use App\Models\AcademicClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'واجب أول',
            'واجب ثاني',
            'تقرير بحثي',
            'مشروع صغير',
            'الواجب النهائي',
            'تقرير عملي',
            'واجب منزلي',
            'تحليل حالة دراسية',
            'ممارسة حل المسائل',
        ];
        $descriptions = [
            'يرجى تقديم الحل بصيغة PDF قبل الموعد النهائي',
            'الواجب يحتوي على مسائل تطبيقية في المادة',
            'يرجى كتابة تقرير بحثي يحتوي على 2000 كلمة',
            'تقديم المشروع مع ملفات التطبيق والوثائق',
            'حل جميع المسائل المطلوبة مع شرح الخطوات',
        ];
        return [
            'class_id' => AcademicClass::factory(),
            'title' => fake('ar_SA')->randomElement($titles) . ' لـ ' . fake('ar_SA')->word(),
            'description' => fake('ar_SA')->randomElement($descriptions),
            'deadline' => fake()->dateTimeBetween('now', '+1 month'),
            'max_file_size' => 2048,
            'allowed_extensions' => 'pdf,doc,docx,zip',
            'is_active' => true,
        ];
    }
}
