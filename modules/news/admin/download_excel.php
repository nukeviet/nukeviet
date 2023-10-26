<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

if ($nv_Request->isset_request('checkss', 'get') and $nv_Request->get_string('checkss', 'get') == NV_CHECK_SESSION) {
    $listid = $nv_Request->get_string('listid', 'get');
    $id_array = array_map('intval', explode(',', $listid));

    $publ_array = [];

    $sql = 'SELECT title, author, publtime, hitstotal, total_rating, click_rating, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id in (' . implode(',', $id_array) . ')';
    $result = $db->query($sql);

    $data_export = [];
    while (list($title, $author, $publtime, $hitstotal, $total_rating, $click_rating, $status) = $result->fetch(3)) {
        if ($status != 4 and $status <= $global_code_defined['row_locked_status']) {
            $arr_catid = explode(',', $listcatid);

            $check_permission = false;
            if (defined('NV_IS_ADMIN_MODULE')) {
                $check_permission = true;
            } else {
                $check_edit = 0;
                foreach ($arr_catid as $catid_i) {
                    if (isset($array_cat_admin[$admin_id][$catid_i])) {
                        if ($array_cat_admin[$admin_id][$catid_i]['admin'] == 1) {
                            ++$check_edit;
                        }
                    }
                }
                if ($check_edit == sizeof($arr_catid)) {
                    $check_permission = true;
                }
            }

            if ($check_permission > 0) {
                $data_export[] = [
                    'title' => $title,
                    'author' => $author,
                    'publtime' => nv_date('H:i d/m/Y', $publtime),
                    'hitstotal' => $hitstotal,
                    'avg_rating' => $click_rating != 0 ? $total_rating / $click_rating : 0,
                    'status' => $status > $global_code_defined['row_locked_status'] ? $lang_module['content_locked_bycat'] : $lang_module['status_' . $status],
                ];
            }
        }
    }
    $spreadsheet = new Spreadsheet();
    $activeSheet = $spreadsheet->getActiveSheet();
    $conditional1 = new Conditional();
    $conditional1->getStyle()->getFont()->setBold(true);

    $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ];
    $styleArrayCommon = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ];
    $arrColumn = range('A', 'G');
    $activeSheet->getColumnDimension('A')->setWidth(30, 'pt');
    $activeSheet->getColumnDimension('B')->setWidth(350, 'pt');
    $activeSheet->getColumnDimension('C')->setWidth(120, 'pt');
    $activeSheet->getColumnDimension('D')->setWidth(100, 'pt');
    $activeSheet->getColumnDimension('E')->setWidth(70, 'pt');
    $activeSheet->getColumnDimension('F')->setWidth(120, 'pt');
    $activeSheet->getColumnDimension('G')->setWidth(120, 'pt');
    $activeSheet->getStyle('A1:G1')->applyFromArray($styleArray);
    $numberRows = count($data_export) + 1;
    $activeSheet->getStyle("A1:G$numberRows")->applyFromArray($styleArrayCommon);

    $headers = [
        'STT',
        $lang_module['news_title'],
        $lang_module['author'],
        $lang_module['news_publictime'],
        $lang_module['news_viewed'],
        $lang_module['news_rating'],
        $lang_module['news_status'],
    ];
    for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
        $activeSheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
    }
    for ($i = 0, $l = sizeof($data_export); $i < $l; $i++) { // row $i
        $j = 0;
        $activeSheet->setCellValueByColumnAndRow($j + 1, ($i + 1 + 1), $i + 1);
        foreach ($data_export[$i] as $k => $v) { // column $j
            $activeSheet->setCellValueByColumnAndRow($j + 1 + 1, ($i + 1 + 1), $v);
            $j++;
        }
    }

    $fileName = 'list_news.xlsx';
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
    $writer->save('php://output');
}
