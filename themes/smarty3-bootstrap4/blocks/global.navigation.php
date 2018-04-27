<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */
if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_navigation')) {


    /**
     * nv_block_navigation_config()
     *
     * @param mixed $module
     * @param mixed $nv_Lang
     * @param mixed $lang_block
     * @return
     */
    function nv_block_navigation_config($module, $data_block, $nv_Lang)

    {
        global $nv_Cache, $site_mods, $db;
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $nv_Lang->getBlock('title1') . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="config_title1" class="form-control w100" size="5" value="' . $data_block['title1'] . '"/></div>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getBlock('rownumber') . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="config_rownumber" class="form-control w100" size="5" value="' . $data_block['rownumber'] . '"/></div>';
        $html .= '  </div>';
        $html .= '  </div>';
        return $html;
    }


    /**
     * nv_block_navigation_config_submit()
     *
     * @param mixed $module
     * @param mixed $nv_Lang
     * @return
     */
    function nv_block_navigation_config_submit($module, $nv_Lang)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['config_title1'] = $nv_Request->get_title('config_title1', 'post');

        $return['config']['rownumber'] = $nv_Request->get_int('config_rownumber', 'post', 0);
        return $return;
    }


    function nv_block_navigation($block_config)
    {

        global $global_config, $nv_Lang,$db;
        $title1 = $block_config['config_title1'];

        // Lấy số lượng bài viết ( tab1)
        $number = $block_config['rownumber'];

        $sql = "SELECT id, catid, publtime, title, alias, homeimgthumb, homeimgfile, hometext, external_link FROM ".NV_PREFIXLANG."_news_rows ORDER BY id DESC LIMIT $number";
        $stmt = $db->query($sql);
        $array_news = $stmt->fetchAll();
        for($i=0;$i<$number;$i++){

            $sql1 = "SELECT title, alias FROM ".NV_PREFIXLANG."_news_cat WHERE catid =".$array_news["$i"]['catid'];
            $stmt1 = $db->query($sql1);
            $row1 = $stmt1->fetch();

            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .'&'. NV_NAME_VARIABLE . '=' . 'news&' . NV_OP_VARIABLE . '=' .  $row1['alias'] . '/' . $array_news["$i"]['alias'].'-' .$array_news["$i"]['id'] . $global_config['rewrite_exturl'];
            $link1 = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .'&'. NV_NAME_VARIABLE . '=' . 'news&' . NV_OP_VARIABLE . '=' .  $row1['alias'] . '/';

            $array_news["$i"]['cate'] = $row1['title'];
            $array_news["$i"]['acate'] = $row1['alias'];
            $array_news["$i"]['link'] = $link;
            $array_news["$i"]['link1'] = $link1;
        }

        // end tab 1

        // lấy tên chuyên mục và số lượng chuyên mục(tab2)
        $stmtcate = $db->query("SELECT * FROM nv4_vi_news_cat WHERE lev = 0");
        $rowcount = $stmtcate->rowCount();
        $cates = $stmtcate->fetchAll();

        for($i=0;$i<$rowcount; $i++){
            $query1= "SELECT * FROM nv4_vi_news_".$cates["$i"]['catid'];
            $stmtcate = $db->query($query1);
            $numcate = $stmtcate->rowCount();
            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .'&'. NV_NAME_VARIABLE . '=' . 'news&' . NV_OP_VARIABLE . '=' .  $cates["$i"]['alias'] . '/';
            $cates["$i"]['numcate']=$numcate;
            $cates["$i"]['link']=$link;

        }



        // end tab2

        // tab3
        $stmtcount = $db->query("SELECT c_val,c_count FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type='year' ORDER BY c_val LIMIT 5");
        $count = $stmtcount->fetchAll();

        // end tab3

        //tab4

        $stmttags = $db->query("SELECT * FROM nv4_vi_news_tags LIMIT 30");
        $rowstags = $stmttags->fetchAll();

        // end tab4



        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.navigation.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.navigation.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\NvSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $tpl->assign('row',$array_news);
        $tpl->assign('title1',$title1);
        $tpl->assign('cates',$cates);
        $tpl->assign('count',$count);
        $tpl->assign('tags',$rowstags);
        return $tpl->fetch('global.navigation.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_navigation($block_config);
}