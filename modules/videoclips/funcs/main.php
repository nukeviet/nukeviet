<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_IS_MOD_VIDEOCLIPS')) die('Stop!!!');

$_otherTopic = array(
    'main' => array(),
    'sub' => array()
);
if (!empty($topicList)) {
    foreach ($topicList as $__k => $__v) {
        if ($__v['parentid'] == '0') $_otherTopic['main'][] = $topicList[$__k];
    }
}

$pgnum = 0;
if (isset($array_op[0]) and !empty($array_op[0])) {
    unset($matches);
    if (preg_match("/^page\-(\d+)$/", $array_op[0], $matches))
        $pgnum = (int) $matches[1];
    else {
        $_tempUrl = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
        $_tempUrl = nv_url_rewrite($_tempUrl, 1);
        header('Location: ' . $_tempUrl, true, 301);
        exit();
    }
}

$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULECONFIG', $configMods);

if (isset($configMods['idhomeclips']) and $configMods['idhomeclips'] > 0) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_clip a, ' . NV_PREFIXLANG . '_' . $module_data . '_hit b WHERE id=' . intval($configMods['idhomeclips']) . ' AND a.status=1 AND a.id=b.cid LIMIT 1';
    $result = $db->query($sql);
    $clip = $result->fetch();
    if (!empty($clip)) {
        $clip['filepath'] = urlencode(!empty($clip['internalpath']) ? NV_BASE_SITEURL . $clip['internalpath'] : $clip['externalpath']);
        $clip['url'] = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video-" . $clip['alias'], 1);
        $clip['editUrl'] = nv_url_rewrite(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&op=main&edit&id=" . $clip['id'] . "&redirect=1", 1);
        
        // kiểm tra có phải youtube
        if (preg_match("/(http(s)?\:)?\/\/([w]{3})?\.youtube[^\/]+\/watch\?v\=([^\&]+)$/isU", $clip['externalpath'], $m)) {
            $xtpl->assign('CODE', $m[4]);
            if ($configMods['playerAutostart'] == 1) $xtpl->assign('autoplay', '&amp;autoplay=1');
            $xtpl->parse('main.video_youtube');
            $xtpl->assign('DETAILCONTENT', $clip);
        } else {
            $xtpl->assign('DETAILCONTENT', $clip);
            $xtpl->parse('main.video_flash');
            
            $contents = "<div id=\"videoDetail\">" . $contents . "</div>\n";
            
            $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/jquery.autoresize.js\"></script>\n";
            $my_head .= "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js\"></script>\n";
            $my_head .= '<script type="text/javascript">
			function explode(a,d,b){if(2>arguments.length||"undefined"==typeof a||"undefined"==typeof d)return null;if(""===a||!1===a||null===a)return!1;if("function"==typeof a||"object"==typeof a||"function"==typeof d||"object"==typeof d)return{"0":""};!0===a&&(a="1");var a=a+"",c=(d+"").split(a);if("undefined"===typeof b)return c;0===b&&(b=1);if(0<b)return b>=c.length?c:c.slice(0,b-1).concat([c.slice(b-1).join(a)]);if(-b>=c.length)return[];c.splice(c.length+b);return c};
			function videoPlay(d,c){var a=$("#"+c).outerWidth(),b;' . $configMods['playerMaxWidth'] . '<a&&(a=' . $configMods['playerMaxWidth'] . ');b=a;a=Math.ceil(45*a/80)+4;$("#"+c).parent().css({width:b,height:a,margin:"0 auto"});swfobject.embedSWF("' . NV_BASE_SITEURL . 'modules/' . $module_file . '/js/player.swf",c,b,a,"10.0.0.0",!1,{file:d,backcolor:"0x000000",frontcolor:"0x666666",lightcolor:"0xFF6600",width:b,height:a,controlbar:"over",autostart:' . $configMods['playerAutostart'] . ',smoothing:1,autoscroll:1,stretching:"fill",volume:100,largecontrols:1' . $configMods['playerSkin'] . '},{bgcolor:"#000000",wmode:"window",allowFullScreen:"true",allowScriptAccess:"always"});return!1};
			function videoPlayList(a,b){swfobject.embedSWF("' . NV_BASE_SITEURL . 'modules/' . $module_file . '/js/player.swf",b,"640","393","10.0.0.0",!1,{playlistfile:a,backcolor:"0x000000",frontcolor:"0x666666",lightcolor:"0xFF6600",width:"640",height:"392",controlbar:"over",autostart:' . $configMods['playerAutostart'] . ',smoothing:1,autoscroll:1,stretching:"fill",volume:100,largecontrols:1' . $configMods['playerSkin'] . '},{bgcolor:"#000000",wmode:"window",allowFullScreen:"true",allowScriptAccess:"always"});return!1};
			function elementSupportsAttribute(a,b){var c=document.createElement(a);return b in c};
			</script>' . "\n";
        }
    }
}

$base_url = array();
$base_url['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$base_url['amp'] = '/page-';

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` a, 
    `" . NV_PREFIXLANG . "_" . $module_data . "_hit` b 
    WHERE a.id=b.cid 
    AND a.status=1 
    ORDER BY a.id DESC 
    LIMIT " . $pgnum . "," . $configMods['otherClipsNum'];

if (!empty($_otherTopic['main'])) {
    $xtpl->assign('OTHETP', $lang_module['topic']);
    foreach ($_otherTopic['main'] as $_ottp) {
        $href = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $_ottp['alias'];
        $xtpl->assign('OTHERTOPIC', array(
            'href' => $href,
            'title' => $_ottp['title'],
            'img' => $_ottp['img']
        ));
        if (!empty($_ottp['img'])) {
            $xtpl->parse('main.topicList.row1.img1');
        }
        if (!empty($_ottp['subcats'])) {
            $xtpl->parse('main.topicList.row1.iss1');
        }
        $xtpl->parse('main.topicList.row1');
    }
    $xtpl->parse('main.topicList');
}

$result = $db->query($sql);
$res = $db->query("SELECT FOUND_ROWS()");
$all_page = $res->fetchColumn();
$all_page = intval($all_page);
if ($all_page) {
    $i = 1;
    while ($row = $result->fetch()) {
        if (!empty($row['img'])) {
            $imageinfo = nv_ImageInfo(NV_ROOTDIR . '/' . $row['img'], 120, true, NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name);
            $row['img'] = $imageinfo['src'];
        } else {
            $row['img'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/video.png";
        }
        $row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video-" . $row['alias'];
        $row['sortTitle'] = nv_clean60($row['title'], 20);
        $xtpl->assign('OTHERCLIPSCONTENT', $row);
        if ($i == 4) {
            $i = 0;
            $xtpl->parse('main.otherClips.otherClipsContent.clearfix');
        }
        $xtpl->parse('main.otherClips.otherClipsContent');
        ++$i;
    }
    
    $generate_page = nv_generate_page($base_url, $all_page, $configMods['otherClipsNum'], $pgnum);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.otherClips.nv_generate_page');
    }
    
    $xtpl->parse('main.otherClips');
}

$xtpl->parse('main');
$contents = $xtpl->text("main");

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';