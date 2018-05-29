<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_headline')) {
    /**
     * nv_block_config_news_headline()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_news_headline($module, $data_block, $lang_block)
    {
        $tooltip_position = array(
            'top' => $lang_block['tooltip_position_top'],
            'bottom' => $lang_block['tooltip_position_bottom'],
            'left' => $lang_block['tooltip_position_left'],
            'right' => $lang_block['tooltip_position_right']
        );

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['showtooltip'] . ':</label>';
        $html .= '<div class="col-sm-18">';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-4">';
        $html .= '<div class="checkbox"><label><input type="checkbox" value="1" name="config_showtooltip" ' . ($data_block['showtooltip'] == 1 ? 'checked="checked"' : '') . ' /></label>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-10">';
        $html .= '<div class="input-group margin-bottom-sm">';
        $html .= '<div class="input-group-addon">' . $lang_block['tooltip_position'] . '</div>';
        $html .= '<select name="config_tooltip_position" class="form-control">';

        foreach ($tooltip_position as $key => $value) {
            $html .= '<option value="' . $key . '" ' . ($data_block['tooltip_position'] == $key ? 'selected="selected"' : '') . '>' . $value . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-10">';
        $html .= '<div class="input-group">';
        $html .= '<div class="input-group-addon">' . $lang_block['tooltip_length'] . '</div>';
        $html .= '<input type="text" class="form-control" name="config_tooltip_length" value="' . $data_block['tooltip_length'] . '"/>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_config_news_headline_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_news_headline_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_string('config_tooltip_position', 'post', 0);
        $return['config']['tooltip_length'] = $nv_Request->get_string('config_tooltip_length', 'post', 0);
        return $return;
    }

    /**
     * nv_block_headline()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_headline($block_config)
    {
        global $nv_Cache, $module_name, $module_data, $db_slave, $my_head, $module_info, $module_upload, $global_array_cat, $global_config;

        $array_bid_content = array();

        $cache_file = NV_LANG_DATA . '_block_headline_' . NV_CACHE_PREFIX . '.cache';

        if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
            $array_bid_content = unserialize($cache);
        } else {
            $id = 0;
            $db_slave->sqlreset()->select('bid, title, numbers')->from(NV_PREFIXLANG . '_' . $module_data . '_block_cat')->order('weight ASC')->limit(2);
            $result = $db_slave->query($db_slave->sql());

            while (list($bid, $titlebid, $numberbid) = $result->fetch(3)) {
                ++$id;
                $array_bid_content[$id] = array(
                    'id' => $id,
                    'bid' => $bid,
                    'title' => $titlebid,
                    'number' => $numberbid
                );
            }

            foreach ($array_bid_content as $i => $array_bid) {
                $db_slave->sqlreset()->select('t1.id, t1.catid, t1.title, t1.alias, t1.homeimgfile, t1.homeimgalt, t1.hometext, t1.external_link')->from(NV_PREFIXLANG . '_' . $module_data . '_rows t1')->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id')->where('t1.status= 1 AND t2.bid=' . $array_bid['bid'])->order('t2.weight ASC')->limit($array_bid['number']);

                $result = $db_slave->query($db_slave->sql());
                $array_content = array();
                while (list($id, $catid_i, $title, $alias, $homeimgfile, $homeimgalt, $hometext, $external_link) = $result->fetch(3)) {
                    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];
                    $array_content[] = array(
                        'title' => $title,
                        'link' => $link,
                        'homeimgfile' => $homeimgfile,
                        'homeimgalt' => $homeimgalt,
                        'hometext' => $hometext,
                        'external_link' => $external_link
                    );
                }
                $array_bid_content[$i]['content'] = $array_content;
            }
            $cache = serialize($array_bid_content);
            $nv_Cache->setItem($module_name, $cache_file, $cache);
        }

        $xtpl = new XTemplate('block_headline.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

        $xtpl->assign('PIX_IMG', NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/pix.gif');
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $module_info['template']);

        $images = array();

        // Tab 1: Tab có ảnh
        if (!empty($array_bid_content[1]['content'])) {
            $hot_news = $array_bid_content[1]['content'];
            $a = 0;
            foreach ($hot_news as $hot_news_i) {

                if ($hot_news_i['external_link']) {
                    $hot_news_i['target_blank'] = 'target="_blank"';
                }

                if (!empty($hot_news_i['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $hot_news_i['homeimgfile'])) {
                    $images_url = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $hot_news_i['homeimgfile'];
                } elseif (nv_is_url($hot_news_i['homeimgfile'])) {
                    $images_url = $hot_news_i['homeimgfile'];
                }

                if (!empty($images_url)) {
                    $hot_news_i['image_alt'] = !empty($hot_news_i['homeimgalt']) ? $hot_news_i['homeimgalt'] : $hot_news_i['title'];
                    $hot_news_i['imgID'] = $a;
                    $hot_news_i['imagefull'] = $images_url;
                    $images[] = $images_url;
                    $xtpl->assign('HOTSNEWS', $hot_news_i);
                    $xtpl->parse('main.hots_news_img.loop');
                    ++$a;
                }
            }
            $xtpl->parse('main.hots_news_img');
        }

        foreach ($array_bid_content as $i => $array_bid) {
            $xtpl->assign('TAB_TITLE', $array_bid);
            $xtpl->parse('main.loop_tabs_title');

            $content_bid = $array_bid['content'];
            if (!empty($content_bid)) {
                foreach ($content_bid as $lastest) {
                    if (!empty($lastest['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $lastest['homeimgfile'])) {
                        $lastest['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $lastest['homeimgfile'];
                    } elseif (nv_is_url($lastest['homeimgfile'])) {
                        $lastest['homeimgfile'] = $lastest['homeimgfile'];
                    } else {
                        $lastest['homeimgfile'] = '';
                    }

                    if (!$block_config['showtooltip']) {
                        $xtpl->assign('TITLE', 'title="' . $lastest['title'] . '"');
                    }

                    $lastest['hometext_clean'] = strip_tags($lastest['hometext']);
                    $lastest['hometext_clean'] = nv_clean60($lastest['hometext_clean'], $block_config['tooltip_length'], true);

                    if ($lastest['external_link']) {
                        $lastest['target_blank'] = 'target="_blank"';
                    }

                    $xtpl->assign('LASTEST', $lastest);
                    $xtpl->parse('main.loop_tabs_content.content.loop');
                }
                $xtpl->parse('main.loop_tabs_content.content');
            }

            $xtpl->parse('main.loop_tabs_content');
        }

        if ($block_config['showtooltip']) {
            $xtpl->assign('TOOLTIP_POSITION', $block_config['tooltip_position']);
            $xtpl->parse('main.tooltip');
        }

        if (empty($my_head) or !preg_match("/jquery\.imgpreload\.min\.js[^>]+>/", $my_head)) {
            $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/jquery/jquery.imgpreload.min.js\"></script>\n";
        }

        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/js/contentslider.js\"></script>\n";
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/jquery-ui/jquery-ui.min.js\"></script>\n";
        $my_head .= "<script type=\"text/javascript\">\n//<![CDATA[\n";
        $my_head .= '$(document).ready(function(){var b=["' . implode('","', $images) . '"];$.imgpreload(b,function(){for(var c=b.length,a=0;a<c;a++)$("#slImg"+a).attr("src",b[a]);featuredcontentslider.init({id:"slider1",contentsource:["inline",""],toc:"#increment",nextprev:["&nbsp;","&nbsp;"],revealtype:"click",enablefade:[true,0.2],autorotate:[true,3E3],onChange:function(){}});$("#tabs").tabs({ajaxOptions:{error:function(e,f,g,d){$(d.hash).html("Couldnt load this tab.")}}});$("#topnews").show()})});';
        $my_head .= "\n//]]>\n</script>\n";
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $module = $block_config['module'];
    $content = nv_block_headline($block_config);
}
