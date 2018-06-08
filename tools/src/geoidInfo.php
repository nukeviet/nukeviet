<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$geoinfoFile = NV_ROOTDIR . '/libs/ip/GeoLite2-Country-Locations-en.csv';

if (!file_exists($geoinfoFile)) {
    trigger_error('No file libs/libs/ip/GeoLite2-Country-Locations-en.csv', 256);
}

$array_geo_info = array();
$handle = fopen($geoinfoFile, 'r');

$i = 0;
while (($buffer = fgets($handle, 4096)) !== false) {
    $buffer = trim($buffer);
    if (strpos($buffer, '#') === 0 or $i++ == 0) {
        continue;
    }
    $buffer = explode(",", $buffer);
    if (!empty($buffer[4]) and !empty($buffer[0])) {
        $array_geo_info[$buffer[0]] = $buffer[4];
    } else {
        //trigger_error('Error: countryInfo.txt get geoid false', 256);
    }
}
if (!feof($handle)) {
    trigger_error('Error: unexpected fgets() fail', 256);
}
fclose($handle);

unset($geoinfoFile, $handle, $buffer);
