<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/*
 * Đọc CODE quốc gia
 */
$geoinfoFile = NV_ROOTDIR . '/libs/GeoLite2-Country-Locations-en.csv';
if (!file_exists($geoinfoFile)) {
    echo "File not exists: libs/GeoLite2-Country-Locations-en.csv\n";
    exit(1);
}

$array_geo_info = [];
$f = fopen($geoinfoFile, 'r');
if ($f === false) {
    echo "Cannot open the file: libs/GeoLite2-Country-Locations-en.csv\n";
    exit(1);
}

$stt = 0;
while (($row = fgetcsv($f)) !== false) {
    if ($stt ++ == 0) {
        // Bỏ qua cột tiêu đề
        continue;
    }
    if (!empty($row[4]) and !empty($row[0])) {
        $array_geo_info[$row[0]] = $row[4];
    }
}
fclose($f);
unset($geoinfoFile, $f, $row);
