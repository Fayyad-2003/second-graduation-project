<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    public function definition(): array
    {
        $subjects = [
            'مشكلة في نظام التسجيل', 'شكوى من مستوى صعوبة المادة', 'طلب تغيير جدول',
            'استفسار عن شروط التخرج', 'مشكلة في الدخول إلى البريد الإلكتروني',
            'طلب دعم تقني', 'اقتراح لتطوير المنصة', 'استفسار عن التأمينات',
        ];
        $messages = [
            'أواجه مشكلة في تسجيل المقررات الدراسية في نظام الـ KRS',
            'أرجو مراجعة مستوى صعوبة المادة لأنها تخرج عن مستوى الطلاب',
            'أرجو تغيير جدول المحاضرات لأنه يتعارض مع جدول مادة أخرى',
            'أرجو إعطائي معلومات دقيقة عن شروط التخرج من البرنامج',
            'لا أستطيع الدخول إلى حساب البريد الإلكتروني الخاص بي',
            'أحتاج إلى دعم تقني لتثبيت البرامج المطلوبة على جهازي',
            'أرجو التفكير في إضافة ميزات جديدة إلى المنصة الطلابية',
            'أريد استفسار عن حالة التأمينات الصحية والاجتماعية',
        ];
        return [
            'user_id' => User::factory(),
            'subject' => fake('ar_SA')->randomElement($subjects),
            'message' => fake('ar_SA')->randomElement($messages),
            'status' => fake()->randomElement(['pending', 'replied', 'closed']),
        ];
    }
}
