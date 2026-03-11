<?php

// Chữ ký hàm nhận vào
/*
$nv_Cache->db(
    string $sql,         // Câu lệnh SQL SELECT
    string $key = '',    // Tên trường làm key cho mảng kết quả ('' = mảng index số)
    string $moduleName,  // Tên module sở hữu cache này (để invalidate)
    string $lang = '',   // Ngôn ngữ (mặc định NV_LANG_DATA)
    int $ttl = 0         // Time-to-live (giây). 0 = vô hạn (cho đến khi bị xóa)
) : array
*/

// VD Sử dụng
$sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_news WHERE status = 1';
$list = $nv_Cache->db($sql, 'id', 'news');
// Kết quả: [ '1' => ['id'=>1, 'title'=>'...'], '2' => [...] ]
