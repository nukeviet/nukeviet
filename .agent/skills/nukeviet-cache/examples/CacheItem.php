<?php
// Lưu cache
// $nv_Cache->setItem(string $moduleName, string $fileName, string $content, string $lang = '', int $ttl = 0);

// Đọc cache
// $content = $nv_Cache->getItem(string $moduleName, string $fileName, string $lang = '', int $ttl = 0);

// Ví dụ lưu mảng:
$data = ['name' => 'NukeViet', 'version' => '5.0'];
$nv_Cache->setItem('my_module', 'settings.cache', json_encode($data, JSON_UNESCAPED_UNICODE));

// Đọc lại
$cache = $nv_Cache->getItem('my_module', 'settings.cache');
if ($cache !== false) {
    $data = json_decode($cache, true);
}
