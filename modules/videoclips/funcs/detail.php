<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_IS_MOD_VIDEOCLIPS')) die('Stop!!!');

if (isset($array_op[1])) {
    $_tempUrl = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $array_op[0];
    $_tempUrl = nv_url_rewrite($_tempUrl, 1);
    header('Location: ' . $_tempUrl, true, 301);
    exit();
}

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a,
" . NV_PREFIXLANG . "_" . $module_data . "_hit b
WHERE a.alias=" . $db->quote($alias_url) . "
AND a.status=1 AND a.id=b.cid LIMIT 1";
$result = $db->query($sql);
$num = $result->rowCount();
if (!$num) {
    $headerStatus = substr(php_sapi_name(), 0, 3) == 'cgi' ? "Status:" : $_SERVER['SERVER_PROTOCOL'];
    header($headerStatus . " 404 Not Found");
    nv_info_die($lang_global['error_404_title'], $lang_global['site_info'], $lang_global['error_404_title']);
    die();
}

$clip = $result->fetch();

// comment
if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
    define('NV_COMM_ID', $clip['id']); //ID bài viết
    define('NV_COMM_AREA', $module_info['funcs'][$op]['func_id']); //để đáp ứng comment ở bất cứ đâu không cứ là bài viết
    //check allow comemnt
    $allowed = $module_config[$module_name]['allowed_comm']; //tuy vào module để lấy cấu hình. Nếu là module news thì có cấu hình theo bài viết
    if ($allowed == '-1') {
        $allowed = $clip['comm'];
    }
    define('NV_PER_PAGE_COMMENT', 5); //Số bản ghi hiển thị bình luận
    require_once NV_ROOTDIR . '/modules/comment/comment.php';
    $area = (defined('NV_COMM_AREA')) ? NV_COMM_AREA : 0;
    $checkss = md5($module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX);
    
    //get url comment
    $url_info = parse_url($client_info['selfurl']);
    $content_comment = nv_comment_module($module_name, $checkss, $area, NV_COMM_ID, $allowed, 1);
} else {
    $content_comment = '';
}

//Tang viewHits
$listRes = isset($_SESSION[$module_data . '_ViewList']) ? $_SESSION[$module_data . '_ViewList'] : "";
$listRes = !empty($listRes) ? explode(",", $listRes) : array();

if (empty($listRes) or !in_array($clip['id'], $listRes)) {
    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_hit SET view=view+1 WHERE cid=" . $clip['id'];
    $db->query($query);
    array_unshift($listRes, $clip['id']);
    $_SESSION[$module_data . '_ViewList'] = implode(",", $listRes);
    ++$clip['view'];
}

//Nut like, unlike, broken
if ($nv_Request->isset_request('aj', 'post') and in_array(($aj = $nv_Request->get_title('aj', 'post', '')), array(
    'like',
    'unlike',
    'broken'
))) {
    if ($aj == "like") $aj = "liked";
    $sessionName = $aj == "broken" ? "broken" : "like";
    $listLike = isset($_SESSION[$module_data . '_' . $sessionName]) ? $_SESSION[$module_data . '_' . $sessionName] : "";
    $listLike = !empty($listLike) ? explode(",", $listLike) : array();
    if (empty($listLike) or !in_array($clip['id'], $listLike)) {
        $set = $aj == "broken" ? $aj . "=1" : $aj . "=" . ($clip[$aj] + 1);
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_hit SET " . $set . " WHERE cid=" . $clip['id'];
        $db->query($query);
        array_unshift($listLike, $clip['id']);
        $_SESSION[$module_data . '_' . $sessionName] = implode(",", $listLike);
        ++$clip[$aj];
    }
    die($aj . "_" . $clip[$aj]);
}

$topic = $topicList[$clip['tid']];
$clip['filepath'] = urlencode(!empty($clip['internalpath']) ? NV_BASE_SITEURL . $clip['internalpath'] : $clip['externalpath']);
$clip['url'] = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video-" . $clip['alias'], 1);
$clip['editUrl'] = nv_url_rewrite(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&op=main&edit&id=" . $clip['id'] . "&redirect=1", 1);

$page_title = $clip['title'] . " - " . $page_title;
if (!empty($clip['keywords'])) $key_words = nv_extKeywords($clip['keywords'] . (!empty($key_words) ? "," . $key_words : ""));
$description = !empty($clip['hometext']) ? $clip['hometext'] : $clip['title'] . " - " . $module_info['custom_title'];

if ($topic['parentid']) {
    $array_mod_title[] = array( //
        'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $topicList[$topic['parentid']]['alias'], //
        'title' => $topicList[$topic['parentid']]['title']
    ) //
;
}

$array_mod_title[] = array( //
    'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $topic['alias'], //
    'title' => $topic['title']
) //
;

$cpgnum = 0;

$xtpl = new XTemplate("detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULECONFIG', $configMods);
$xtpl->assign('MODULEURL', nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $clip['alias'], 1));
$xtpl->assign('SELFURL', $client_info['selfurl']);
$lang = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';
$xtpl->assign('FACEBOOK_LANG', $lang);
$meta_property['og:type'] = "website";
$meta_property['og:url'] = $client_info['selfurl'];
$meta_property['og:image'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/video.png";

// kiểm tra có phải youtube
if (preg_match("/^(http(s)?\:)?\/\/([w]{3})?\.youtube[^\/]+\/watch\?v\=([^\&]+)\&?(.*?)$/is", $clip['externalpath'], $m)) {
    $xtpl->assign('CODE', $m[4]);
    if ($configMods['playerAutostart'] == 1) $xtpl->assign('autoplay', '&amp;autoplay=1');
    $xtpl->parse('main.video_youtube');
    $xtpl->assign('DETAILCONTENT', $clip);
} else if (preg_match("/(http(s)?\:)?\/\/youtu?\.be[^\/]?\/([^\&]+)$/isU", $clip['externalpath'], $m)) {
    $xtpl->assign('CODE', $m[3]);
    if ($configMods['playerAutostart'] == 1) $xtpl->assign('autoplay', '&amp;autoplay=1');
    $xtpl->parse('main.video_youtube');
    $xtpl->assign('DETAILCONTENT', $clip);
} else {
    $xtpl->assign('DETAILCONTENT', $clip);
    $xtpl->parse('main.video_flash');
}

if (defined('NV_IS_MODADMIN')) $xtpl->parse('main.isAdmin');
if (!empty($clip['bodytext'])) $xtpl->parse('main.bodytext');

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a,
    " . NV_PREFIXLANG . "_" . $module_data . "_hit b
    WHERE a.id!=" . $db->quote($clip['id']) . "
    AND a.tid=" . $db->quote($topic['id']) . "
    AND a.status=1
    AND a.id=b.cid
    ORDER BY RAND()
    LIMIT 12";
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
        $row['href'] = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video-" . $row['alias'], 1);
        $row['sortTitle'] = nv_clean60($row['title'], 20);
        $xtpl->assign('OTHERCLIPSCONTENT', $row);
        if ($i == 4) {
            $i = 0;
            $xtpl->parse('main.otherClips.otherClipsContent.clearfix');
        }
        $xtpl->parse('main.otherClips.otherClipsContent');
        ++$i;
    }
    
    $xtpl->parse('main.otherClips');
}

if (!empty($content_comment)) {
    $xtpl->assign('CONTENT_COMMENT', $content_comment);
    $xtpl->parse('main.comment');
}

$xtpl->parse('main');
$contents = $xtpl->text("main");

$contents = "<div id=\"videoDetail\">" . $contents . "</div>\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/js/" . $module_file . "_jquery.autoresize.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js\"></script>\n";
$my_head .= '<script type="text/javascript">
function explode(a,d,b){if(2>arguments.length||"undefined"==typeof a||"undefined"==typeof d)return null;if(""===a||!1===a||null===a)return!1;if("function"==typeof a||"object"==typeof a||"function"==typeof d||"object"==typeof d)return{"0":""};!0===a&&(a="1");var a=a+"",c=(d+"").split(a);if("undefined"===typeof b)return c;0===b&&(b=1);if(0<b)return b>=c.length?c:c.slice(0,b-1).concat([c.slice(b-1).join(a)]);if(-b>=c.length)return[];c.splice(c.length+b);return c};
function videoPlay(d,c){var a=$("#"+c).outerWidth(),b;' . $configMods['playerMaxWidth'] . '<a&&(a=' . $configMods['playerMaxWidth'] . ');b=a;a=Math.ceil(45*a/80)+4;$("#"+c).parent().css({width:b,height:a,margin:"0 auto"});swfobject.embedSWF("' . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/player.swf",c,b,a,"10.0.0.0",!1,{file:d,backcolor:"0x000000",frontcolor:"0x666666",lightcolor:"0xFF6600",width:b,height:a,controlbar:"over",autostart:' . $configMods['playerAutostart'] . ',smoothing:1,autoscroll:1,stretching:"fill",volume:100,largecontrols:1' . $configMods['playerSkin'] . '},{bgcolor:"#000000",wmode:"window",allowFullScreen:"true",allowScriptAccess:"always"});return!1};
function videoPlayList(a,b){swfobject.embedSWF("' . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/player.swf",b,"640","393","10.0.0.0",!1,{playlistfile:a,backcolor:"0x000000",frontcolor:"0x666666",lightcolor:"0xFF6600",width:"640",height:"392",controlbar:"over",autostart:' . $configMods['playerAutostart'] . ',smoothing:1,autoscroll:1,stretching:"fill",volume:100,largecontrols:1' . $configMods['playerSkin'] . '},{bgcolor:"#000000",wmode:"window",allowFullScreen:"true",allowScriptAccess:"always"});return!1};
function elementSupportsAttribute(a,b){var c=document.createElement(a);return b in c};
</script>' . "\n";

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';