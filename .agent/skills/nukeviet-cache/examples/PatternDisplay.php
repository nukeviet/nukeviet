<?php
$cache_file = 'hits_' . $id . '.cache';
if (($cache = $nv_Cache->getItem($module_name, $cache_file, '', 3600)) !== false) {
    $data = json_decode($cache, true);
} else {
    // Truy vấn DB và xử lý
    $data = []; // ... Truyền kết quả của bạn
    $nv_Cache->setItem($module_name, $cache_file, json_encode($data, JSON_UNESCAPED_UNICODE));
}
