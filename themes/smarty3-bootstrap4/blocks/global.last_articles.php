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

if (!nv_function_exists('nv_block_last_articles')) {

    /**
     * nv_block_config_tophits_blocks()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_last_articles_config($module, $data_block, $lang_block)
    {
        global $nv_Cache, $site_mods;
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_numrow" class="form-control w100" size="5" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';

        $html .= '</div>';
        return $html;
    }


    /**
     * nv_block_config_tophits_blocks_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_last_articles_config_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();

        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);

        return $return;

    }

    /**
     * nv_block_last_articles()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_last_articles($block_config)
    {
        // print_r($block_config); die("ok");
        global $global_config, $lang_global,$db,$site_mods;
        $number = $block_config['numrow'];

        $sql = "SELECT id, catid, publtime, title, alias, homeimgthumb, homeimgfile, hometext, external_link FROM nv4_vi_news_rows ORDER BY id DESC LIMIT $number";

        $stmt = $db->query($sql);
        $array_news = $stmt->fetchAll();



        for($i=0;$i<$number;$i++){
            $sql1 = "SELECT title, alias FROM nv4_vi_news_cat WHERE catid =".$array_news["$i"]['catid'];
            $stmt1 = $db->query($sql1);
            $row1 = $stmt1->fetch();

            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .'&'. NV_NAME_VARIABLE . '=' . 'news&' . NV_OP_VARIABLE . '=' .  $row1['alias'] . '/' . $array_news["$i"]['alias'].'-' .$array_news["$i"]['id'] . $global_config['rewrite_exturl'];
            $link1 = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .'&'. NV_NAME_VARIABLE . '=' . 'news&' . NV_OP_VARIABLE . '=' .  $row1['alias'] . '/';

            $array_news["$i"]['cate'] = $row1['title'];
            $array_news["$i"]['acate'] = $row1['alias'];
            $array_news["$i"]['link'] = $link;
            $array_news["$i"]['link1'] = $link1;


            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.last_articles.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.last_articles.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $tpl = new \NukeViet\Template\NvSmarty();
            $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
            $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
            $tpl->assign('row',$array_news);


            return $tpl->fetch('global.last_articles.tpl');
        }
    }
}
    if (defined('NV_SYSTEM')) {
        $content = nv_block_last_articles($block_config);
    }