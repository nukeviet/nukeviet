<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require NV_ROOTDIR . '/src/functions.php';
require NV_ROOTDIR . '/src/Large-CSVReader.php';
require NV_ROOTDIR . '/src/geoidInfo.php';

set_time_limit(0);
ini_set('memory_limit', '-1');

/**
 * Bắt đầu tool
 */

// Tạo các thư mục nếu chưa có
if (!is_dir(NV_ROOTDIR . '/release')) {
    mkdir(NV_ROOTDIR . '/release');
}
if (!is_dir(NV_ROOTDIR . '/release/ip')) {
    mkdir(NV_ROOTDIR . '/release/ip');
}

$inputFileType = 'Csv';
$inputFileName = NV_ROOTDIR . '/libs/ip/GeoLite2-Country-Blocks-IPv4.csv';

// Số row mỗi chunk
$chunkSize = 10000;
$offsetRow = 0;

while (1) {
    try {
        echo('Offset: ' . $offsetRow . PHP_EOL);
        echo 'Reading...' . PHP_EOL;

        $chunkFilter = new NukeViet\Files\ChunkReadFilter();

        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadFilter($chunkFilter)->setContiguous(true);
        $spreadsheet = new Spreadsheet();

        $sheet = 0;
        $startRow = 2 + $offsetRow;

        $chunkFilter->setRows($startRow, $chunkSize);
        $reader->setSheetIndex($sheet);
        $reader->loadIntoExisting($inputFileName, $spreadsheet);
        $sheetData = $spreadsheet->getActiveSheet();

        $maxRow = $sheetData->getHighestRow();

        $loaded_file = array();
        $loaded_file_reverse = array();

        if ($maxRow <= 1) {
            // Ghi lại các file không có từ 0 - 255
            for ($i = 0; $i <= 255; $i++) {
                if (!file_exists(NV_ROOTDIR . '/release/ip/' . $i . '.php')) {
                    $file_content = "<?php\n\n" . IP_FILEHEAD . "\n\$ranges=array();\n";
                    file_put_contents(NV_ROOTDIR . '/release/ip/' . $i . '.php', $file_content, LOCK_EX);
                }
            }
            echo 'No data.' . PHP_EOL;
            die('Finish!' . PHP_EOL);
        } else {
            $keyloop = 0;
            $keyecho = 0;

            for ($i = $startRow; $i <= $maxRow; $i++) {
                $keyloop++;
                if ($keyloop % 100 == 0) {
                    $keyecho++;
                    echo 'Writing step ' . $keyecho . '...' . PHP_EOL;
                }

                $ip_range = $sheetData->getCell('A' . $i)->getValue();
                $geo_id = $sheetData->getCell('B' . $i)->getValue();

                if (isset($array_geo_info[$geo_id])) {
                    list($range, $netmask) = explode('/', $ip_range, 2);
                    if (!empty($netmask)) {
                        $x = explode('.', $range);
                        while (count($x) < 4) {
                            $x[] = '0';
                        }
                        list($a, $b, $c, $d) = $x;
                        //$range = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);

                        $ip_start = ($a * 16777216) + ($b * 65536) + ($c * 256) + ($d);
                        $ip_end = $ip_start + pow(2, 32 - intval($netmask)) - 1;

                        if (!isset($loaded_file[$a]) and file_exists(NV_ROOTDIR . '/release/ip/' . $a . '.php')) {
                            $ranges = array();
                            $ranges_reverse = array();
                            include NV_ROOTDIR . '/release/ip/' . $a . '.php';
                            $loaded_file[$a] = $ranges;
                            foreach ($ranges as $rkey => $rval) {
                                $ranges_reverse[$rval[0]] = array($rkey, $rval[1]);
                            }
                            $loaded_file_reverse[$a] = $ranges_reverse;
                        } elseif (isset($loaded_file[$a])) {
                            $ranges = $loaded_file[$a];
                            $ranges_reverse = $loaded_file_reverse[$a];
                        } else {
                            $ranges = array();
                            $ranges_reverse = array();
                        }

                        $ip_sbefore = $ip_start - 1;

                        if (isset($ranges_reverse[$ip_sbefore]) and $ranges_reverse[$ip_sbefore][1] == $array_geo_info[$geo_id]) {
                            $ip_start = $ranges_reverse[$ip_sbefore][0];
                            unset($ranges_reverse[$ip_sbefore]);
                        }

                        $ranges[$ip_start] = array($ip_end, $array_geo_info[$geo_id]);
                        $ranges_reverse[$ip_end] = array($ip_start, $array_geo_info[$geo_id]);

                        $loaded_file[$a] = $ranges;
                        $loaded_file_reverse[$a] = $ranges_reverse;
                    } else {
                        die('IP range invalid on line ' . $startRow . PHP_EOL);
                    }
                }
            }
            $offsetRow = ($offsetRow + $chunkSize);
        }

        foreach ($loaded_file as $fname => $fdata) {
            $file_content = "<?php\n\n" . IP_FILEHEAD . "\n\$ranges=" . nv_print_variable_ip($fdata) . ";\n";
            file_put_contents(NV_ROOTDIR . '/release/ip/' . $fname . '.php', $file_content, LOCK_EX);
        }

        unset($chunkFilter, $reader, $spreadsheet, $sheetData, $file_content, $loaded_file, $loaded_file_reverse);
        sleep(1);
    } catch (Exception $ex) {
        echo 'Error:' . PHP_EOL;
        print_r($ex);
        die(PHP_EOL);
    }
}
