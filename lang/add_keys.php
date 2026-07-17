<?php
$file = __DIR__ . '/ar.json';
$data = json_decode(file_get_contents($file), true);

$data['Programs'] = 'البرامج';
$data['Graduation Requirements'] = 'متطلبات التخرج';
$data['Total Required Credits'] = 'إجمالي الساعات المطلوبة';
$data['Add New Faculty'] = 'إضافة كلية جديدة';
$data['Credits per Classification'] = 'الساعات حسب التصنيف';
$data['SKS'] = 'ساعة';
$data['Save Faculty'] = 'حفظ الكلية';

file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "Done. Keys: " . count($data) . "\n";
