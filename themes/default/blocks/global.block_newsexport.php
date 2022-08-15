<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_newsexport')) {
    /**
     * nv_block_newsexport_config()
     *
     * @param string $module
     * @param array  $data_block
     * @param array  $lang_block
     * @return string
     */
    function nv_block_newsexport_config($module, $data_block, $lang_block)
    {
        $html =  '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">Label nút Download: </label>';
        $html .= '<div class="col-sm-18"><input type="text" name="config_export_label" class="form-control" value="' . $data_block['export_label'] . '"/></div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_newsexport_submit()
     *
     * @param string $module
     * @param array  $lang_block
     * @return array
     */
    function nv_block_newsexport_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config']['export_label'] = $nv_Request->get_title('config_export_label', 'post');

        return $return;
    }

    /**
     * nv_block_newsexport()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_newsexport($block_config)
    {
        global $nv_Request, $global_config, $site_mods, $lang_global, $base_url;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.block_newsexport.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.block_newsexport.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.block_newsexport.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('DATA', $block_config);
        $xtpl->assign('ACTION', $base_url);

        if (!empty($block_config['export_label'])) {
            $xtpl->parse('main.has_label');
        }else{
            $xtpl->parse('main.empty_label');
        }

        $xtpl->parse('main');

        if ($nv_Request->isset_request('newsexport', 'post')) {
            $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Hello World !');

            $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="news_list.xlsx"');
            $writer->save('php://output');
        }

        return $xtpl->text('main');
    }

       
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_newsexport($block_config);
}
