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

// Ket noi ngon ngu
if (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_seotools.php')) {
    require NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_seotools.php';
} elseif (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_seotools.php')) {
    require NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_seotools.php';
} elseif (file_exists(NV_ROOTDIR . '/includes/language/en/admin_seotools.php')) {
    require NV_ROOTDIR . '/includes/language/en/admin_seotools.php';
}

$page_title = $lang_module['rpc'];
if (nv_function_exists('curl_init') and nv_function_exists('curl_exec')) {
    $id = $nv_Request->get_int('id', 'post,get', '');
    if ($id > 0) {
        $query = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $id);
        $news_contents = $query->fetch();
        $nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
        $nv_redirect2 = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $id . '&checkss=' . md5($id . NV_CHECK_SESSION) . '&rand=' . nv_genpass();

        $prcservice = (isset($module_config['seotools']['prcservice'])) ? $module_config['seotools']['prcservice'] : '';
        $prcservice = (!empty($prcservice)) ? explode(',', $prcservice) : [];

        if ($news_contents['id'] > 0 and !empty($prcservice)) {
            if ($news_contents['status'] == 1 and $news_contents['publtime'] < NV_CURRENTTIME + 1 and ($news_contents['exptime'] == 0 or $news_contents['exptime'] > NV_CURRENTTIME + 1)) {
                if ($nv_Request->get_string('checkss', 'post,get', '') == md5($id . NV_CHECK_SESSION)) {
                    $services_active = [];
                    require NV_ROOTDIR . '/' . NV_DATADIR . '/rpc_services.php';
                    foreach ($services as $key => $service) {
                        if (in_array($service[1], $prcservice, true)) {
                            $services_active[] = $service;
                        }
                    }

                    $getdata = $nv_Request->get_int('getdata', 'post,get', '0');
                    if (empty($getdata)) {
                        $page_title = $lang_module['rpc'] . ': ' . $news_contents['title'];
                        $xtpl = new XTemplate('rpc_ping.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/seotools');
                        $xtpl->assign('LANG', $lang_module);
                        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
                        $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
                        $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
                        $xtpl->assign('MODULE_NAME', $module_name);
                        $xtpl->assign('OP', $op);
                        $xtpl->assign('LOAD_DATA', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $id . '&checkss=' . md5($id . NV_CHECK_SESSION) . '&getdata=1');

                        $xtpl->assign('HOME', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                        foreach ($services_active as $key => $service) {
                            $xtpl->assign('SERVICE', [
                                'id' => $key,
                                'title' => $service[1],
                                'icon' => (isset($service[3]) ? $service[3] : '')
                            ]);

                            if (isset($service[3]) and !empty($service[3])) {
                                $xtpl->parse('main.service.icon');
                            } else {
                                $xtpl->parse('main.service.noticon');
                            }
                            $xtpl->parse('main.service');
                        }
                        $xtpl->parse('main');
                        $contents = $xtpl->text('main');
                    } else {
                        $xml2 = new DOMDocument('1.0', 'UTF-8');
                        $xml2->formatOutput = true;
                        $xml2->preserveWhiteSpace = false;
                        $xml2->substituteEntities = false;
                        $rs = $xml2->appendChild($xml2->createElement('pingResult'));
                        $finish = $rs->appendChild($xml2->createElement('finish'));

                        $timeout = $nv_Request->get_int('rpct', 'cookie', 0);
                        $timeout = NV_CURRENTTIME - $timeout;
                        if (($timeout != 0) and ($timeout < 60)) {
                            $timeout = 60 - $timeout;
                            $timeout = nv_convertfromSec($timeout);
                            $finish->nodeValue = 'glb|' . sprintf($lang_module['rpc_error_timeout'], $timeout);
                            $content = $xml2->saveXML();
                            @header('Content-Type: text/xml; charset=utf-8');
                            print_r($content);
                            exit();
                        }

                        $listcatid_arr = explode(',', $news_contents['listcatid']);
                        $catid_i = $listcatid_arr[0];

                        $webtitle = htmlspecialchars(nv_unhtmlspecialchars($news_contents['title']), ENT_QUOTES);

                        $webhome = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
                        if (!str_starts_with($webhome, NV_MY_DOMAIN)) {
                            $webhome = NV_MY_DOMAIN . $webhome;
                        }

                        $linkpage = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], 1);
                        if (!str_starts_with($linkpage, NV_MY_DOMAIN)) {
                            $linkpage = NV_MY_DOMAIN . $linkpage;
                        }

                        $webrss = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss'] . '/' . $global_array_cat[$catid_i]['alias'], 1);
                        if (!str_starts_with($webrss, NV_MY_DOMAIN)) {
                            $webrss = NV_MY_DOMAIN . $webrss;
                        }

                        $pingtotal = $nv_Request->get_int('total', 'post', 0);
                        if ($sys_info['allowed_set_time_limit']) {
                            set_time_limit(0);
                        }
                        if ($sys_info['ini_set_support']) {
                            ini_set('default_socket_timeout', 200);
                        }

                        $sCount = count($services_active);

                        if ($pingtotal > $sCount) {
                            $finish->nodeValue = 'OK';
                        } else {
                            require NV_ROOTDIR . '/includes/core/rpc.php';

                            for ($i = $pingtotal, $a = 0; $i <= $sCount, $a <= 5; $i++, $a++) {
                                if ($a == 5 or $i == $sCount) {
                                    $servicebreak = $rs->appendChild($xml2->createElement('break'));
                                    $servicebreak->nodeValue = $i;

                                    if ($i == $sCount) {
                                        $nv_Request->set_Cookie('rpct', NV_CURRENTTIME);
                                        $finish->nodeValue = 'OK';
                                    } else {
                                        $finish->nodeValue = 'WAIT';
                                    }

                                    break;
                                }

                                $data = nv_rpcXMLCreate($webtitle, $webhome, $linkpage, $webrss, $services[$i][0]);
                                $results = nv_getRPC($services[$i][2], $data);

                                $service = $rs->appendChild($xml2->createElement('service'));
                                $serviceID = $service->appendChild($xml2->createElement('id'));
                                $serviceID->nodeValue = $i;
                                $flerrorCode = $service->appendChild($xml2->createElement('flerrorCode'));
                                $flerrorCode->nodeValue = $results[0];
                                $flerrorMes = $service->appendChild($xml2->createElement('message'));
                                $flerrorMes->nodeValue = $results[1];
                            }
                        }

                        $content = $xml2->saveXML();

                        @header('Content-Type: text/xml; charset=utf-8');
                        print_r($content);
                        exit();
                    }
                } else {
                    $msg1 = $lang_module['content_saveok'];
                    $msg2 = $lang_module['content_main'] . ' ' . $module_info['custom_title'];

                    $contents .= '<div align="center">';
                    $contents .= '<strong>' . $msg1 . "</strong><br /><br />\n";
                    $contents .= '<img border="0" src="' . NV_STATIC_URL . NV_ASSETS_DIR . "/images/load_bar.gif\" /><br /><br />\n";
                    $contents .= '<strong><a href="' . $nv_redirect2 . '">' . $lang_module['rpc_ping_page'] . '</a></strong>';
                    $contents .= ' - <strong><a href="' . $nv_redirect . '">' . $msg2 . '</a></strong>';
                    $contents .= '</div>';
                    $contents .= '<meta http-equiv="refresh" content="3;url=' . $nv_redirect2 . '" />';
                }
            } else {
                $contents = '<meta http-equiv="refresh" content="1;url=' . $nv_redirect . '" />';
            }
        } else {
            $contents = '<meta http-equiv="refresh" content="1;url=' . $nv_redirect . '" />';
        }
    }
} else {
    $contents = 'System not support function php "curl_init" !';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
