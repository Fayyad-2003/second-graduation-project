<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'تحديث جدول المحاضرات',
            'تأكيد تسجيل الخطة الدراسية',
            'تحديث درجات المادة',
            'إضافة واجب جديد',
            'إعلان عن امتحان',
            'تنبيه لتسليم الواجب',
            'إشعار بالحضور',
            'تأكيد إجازة',
            'إعلان عن نشاط طلابي',
        ];
        $messages = [
            'تم تحديث جدول المحاضرات لفصل الدراسي الحالي',
            'تمت الموافقة على خطة الدراسية الخاصة بك',
            'تم تحديث درجات مادة معينة في نظامك',
            'تم إضافة واجب جديد لـ مادة معينة',
            'تمت إضافة جدول الامتحانات على الموقع',
            'يرجى تسليم الواجب قبل الموعد النهائي',
            'لقد تم تسجيل حضورك في المحاضرة الأخيرة',
            'تمت الموافقة على طلب الإجازة المقدم',
            'يرجى حضور النشاط الطلابي الذي سيقام في الفترة القادمة',
        ];
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement([
                Notification::TYPE_SCHEDULE_CHANGE,
                Notification::TYPE_STUDY_PLAN_APPROVED,
                Notification::TYPE_GRADE_UPDATED,
                Notification::TYPE_CLASS_INFO,
            ]),
            'title' => fake('ar_SA')->randomElement($titles),
            'message' => fake('ar_SA')->randomElement($messages),
            'read_at' => fake()->optional()->dateTime(),
        ];
    }
}
