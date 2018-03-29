<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 29-03-2012 03:29
 */

define('NV_SYSTEM', true);
define('NV_IS_UPDATE', true);

//Xac dinh thu muc goc cua site
define('NV_ROOTDIR', str_replace('\\', '/', realpath(pathinfo(__file__, PATHINFO_DIRNAME) . '/../')));

require NV_ROOTDIR .'/includes/mainfile.php';

// Kiem tra tu cach admin
if (!defined('NV_IS_GODADMIN')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}

// Kiem tra ton tai goi update
if (!file_exists(NV_ROOTDIR . '/install/update_data.php')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}
require NV_ROOTDIR . '/install/update_data.php';
if (empty($nv_update_config)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}

// Ham cua admin
define('NV_ADMIN', true);
include_once NV_ROOTDIR . '/includes/core/admin_functions.php' ;

// Xac dinh ngon ngu cap nhat
$dirs = nv_scandir(NV_ROOTDIR . '/includes/language', '/^([a-z]{2})/');
$languageslist = array();

foreach ($dirs as $file) {
    if (is_file(NV_ROOTDIR . '/includes/language/' . $file . '/install.php')) {
        $languageslist[] = $file;
    }
}
$data_update_lang = array_keys($nv_update_config['lang']);
$array_lang_update = array_intersect($data_update_lang, $languageslist);
$nv_update_config['allow_lang'] = $array_lang_update;

$cookie_lang = $nv_Request->get_string('update_lang', 'cookie', '');
$update_lang = $nv_Request->get_string(NV_LANG_VARIABLE, 'get,post', '');

if (!empty($update_lang) and (in_array($update_lang, $array_lang_update)) and file_exists(NV_ROOTDIR . '/includes/language/' . $update_lang . '/global.php')) {
    if ($update_lang != $cookie_lang) {
        $nv_Request->set_Cookie('update_lang', $update_lang, NV_LIVE_COOKIE_TIME);
    }
} elseif (preg_match('/^[a-z]{2}$/', $cookie_lang) and (in_array($cookie_lang, $array_lang_update)) and file_exists(NV_ROOTDIR . '/includes/language/' . $cookie_lang . '/global.php')) {
    $update_lang = $cookie_lang;
} elseif (in_array(NV_LANG_DATA, $array_lang_update)) {
    $update_lang = NV_LANG_DATA;
    $nv_Request->set_Cookie('update_lang', $update_lang, NV_LIVE_COOKIE_TIME);
} else {
    $update_lang = $array_lang_update[0];
    $nv_Request->set_Cookie('update_lang', $update_lang, NV_LIVE_COOKIE_TIME);
}

define('NV_LANG_UPDATE', $update_lang);

unset($dirs, $languageslist, $file, $data_update_lang, $array_lang_update, $cookie_lang, $update_lang);
if (NV_LANG_UPDATE != NV_LANG_DATA) {
    unset($lang_module, $lang_global);
}

require NV_ROOTDIR . '/includes/language/' . NV_LANG_UPDATE . '/global.php';
require NV_ROOTDIR . '/includes/language/' . NV_LANG_UPDATE . '/admin_global.php';
require NV_ROOTDIR . '/includes/language/' . NV_LANG_UPDATE . '/install.php';

$lang_module = array_merge($lang_module, $nv_update_config['lang'][NV_LANG_UPDATE]);
unset($nv_update_config['lang']);

/**
 * NvUpdate
 *
 * @package NukeViet
 * @author VINADES.,JSC
 * @copyright VINADES.,JSC
 * @version 2012
 * @access public
 */
class NvUpdate
{
    private $db;
    private $lang;
    private $glang;
    private $config;

    /**
     * NvUpdate::__construct()
     *
     * @param mixed $nv_update_config
     * @return
     */
    public function __construct($nv_update_config)
    {
        global $db, $lang_module, $lang_global;

        $this->db = $db;
        $this->lang = $lang_module;
        $this->glang = $lang_global;
        $this->config = $nv_update_config;
    }

    /**
     * NvUpdate::check_package()
     *
     * @return
     */
    public function check_package()
    {
        if (!isset($this->config['release_date'])) {
            return false;
        } elseif (!isset($this->config['author'])) {
            return false;
        } elseif (!isset($this->config['support_website'])) {
            return false;
        } elseif (!isset($this->config['to_version'])) {
            return false;
        } elseif (!isset($this->config['allow_old_version'])) {
            return false;
        } elseif (!isset($this->config['type'])) {
            return false;
        } elseif (!isset($this->config['packageID'])) {
            return false;
        } elseif (!isset($this->config['tasklist'])) {
            return false;
        }
        return true;
    }

    /**
     * NvUpdate::list_data_update()
     *
     * @return
     */
    public function list_data_update()
    {
        if (empty($this->config['tasklist'])) {
            return array();
        }

        global $nv_update_config;

        $tasklist = array();

        foreach ($this->config['tasklist'] as $task) {
            if (nv_version_compare($task['r'], $nv_update_config['updatelog']['old_version']) > 0) {
                $tasklist[$task['f']] = array( 'langkey' => $task['l'], 'require' => $task['rq'] );
            }
        }

        return $tasklist;
    }

    /**
     * NvUpdate::list_all_file()
     *
     * @param string $dir
     * @param string $base_dir
     * @return
     */
    public function list_all_file($dir = '', $base_dir = '')
    {
        if (empty($dir)) {
            $dir = NV_ROOTDIR . '/install/update';
        }

        $file_list = array();

        if (is_dir($dir)) {
            $array_filedir = scandir($dir);

            foreach ($array_filedir as $v) {
                if ($v == '.' or $v == '..') {
                    continue;
                }

                if (is_dir($dir . '/' . $v)) {
                    foreach ($this->list_all_file($dir . '/' . $v, $base_dir . '/' . $v) as $file) {
                        $file_list[] = $file;
                    }
                } else {
                    // if( $base_dir == '' and ( $v == 'index.html' or $v == 'index.htm' ) ) continue; // Khong di chuyen index.html
                    $file_list[] = preg_replace('/^\//', '', $base_dir . '/' . $v);
                }
            }
        }

        return $file_list;
    }

    /**
     * NvUpdate::set_data_log()
     *
     * @param mixed $data
     * @return
     */
    public function set_data_log($data)
    {
        $content_config = "<?php\n\n";
        $content_config .= NV_FILEHEAD . "\n\n";
        $content_config .= "if( !defined( 'NV_IS_UPDATE' ) ) die( 'Stop!!!' );\n\n";
        $content_config .= "\$nv_update_config['updatelog'] = " . var_export($data, true) . ";";
        $content_config .= "\n\n";

        $return = file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/config_update_' . $this->config['packageID'] . '.php', $content_config, LOCK_EX);

        if ($return === false) {
            $message = sprintf($this->lang['update_error_log_data'], NV_DATADIR);
            $contents = $this->call_error($message);

            include NV_ROOTDIR . '/includes/header.php';
            echo $this->template($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }

        // Resets the contents of the opcode cache
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }

    /**
     * NvUpdate::move_file()
     *
     * @param mixed $nv_update_config
     * @param mixed $files
     * @return
     */
    public function move_file($nv_update_config, $files)
    {
        if (empty($files)) {
            return true;
        }

        global $global_config;

        $is_ftp = false;
        if ($global_config['ftp_check_login'] == 1) {
            $ftp_server = nv_unhtmlspecialchars($global_config['ftp_server']);
            $ftp_port = intval($global_config['ftp_port']);
            $ftp_user_name = nv_unhtmlspecialchars($global_config['ftp_user_name']);
            $ftp_user_pass = nv_unhtmlspecialchars($global_config['ftp_user_pass']);
            $ftp_path = nv_unhtmlspecialchars($global_config['ftp_path']);

            $ftp = new NukeViet\Ftp\Ftp($ftp_server, $ftp_user_name, $ftp_user_pass, array( 'timeout' => 20 ), $ftp_port);

            if (empty($ftp->error) and $ftp->chdir($ftp_path)) {
                $is_ftp = true;
            }
        }

        // Nhat ki
        $logs_message = array();
        $logs_status = array();

        // Bat dau tao thu muc
        foreach ($files as $file_i) {
            $cp = '';
            $e = explode('/', $file_i);
            foreach ($e as $p) {
                if (!empty($p) and is_dir(NV_ROOTDIR . '/install/update/' . $cp . $p) and !is_dir(NV_ROOTDIR . '/' . $cp . $p)) {
                    // Neu khong tao thu muc theo cach thong thuong thi tao bang FTP (neu co)
                    if ((@mkdir(NV_ROOTDIR . '/' . $cp . $p) == false) and $is_ftp === true) {
                        $ftp->mkdir($cp . $p);
                    }

                    if (!is_dir(NV_ROOTDIR . '/' . $cp . $p)) {
                        // Nhat ki that bai
                        $logs_message[] = $this->lang['update_log_creat_dir'] . ' ' . $cp . $p;
                        $logs_status[] = false;

                        // Luu nhat ki
                        $this->log($nv_update_config, $logs_message, $logs_status);

                        if ($is_ftp === true) {
                            $ftp->close();
                        }
                        return $this->lang['update_error_creat_dir'] . ' ' . $cp . $p;
                    }

                    // Nhat ki thanh cong
                    $logs_message[] = $this->lang['update_log_creat_dir'] . ' ' . $cp . $p;
                    $logs_status[] = true;
                }
                $cp .= $p . '/';
            }
        }

        // Di chuyen cac file
        foreach ($files as $file_i) {
            if (is_file(NV_ROOTDIR . '/install/update/' . $file_i)) {
                // Neu ton tai thi xoa truoc
                if (file_exists(NV_ROOTDIR . '/' . $file_i)) {
                    if (@unlink(NV_ROOTDIR . '/' . $file_i) == false and $is_ftp === true) {
                        // Dung ftp de xoa
                        $ftp->unlink($file_i);
                    }
                }

                // Di chuyen bang cach doi ten duong dan
                if (@rename(NV_ROOTDIR . '/install/update/' . $file_i, NV_ROOTDIR . '/' . $file_i) == false and $is_ftp === true) {
                    // Dung ftp di chuyen
                    $ftp->rename('install/update/' . $file_i, $file_i);
                }

                if (file_exists(NV_ROOTDIR . '/install/update/' . $file_i)) {
                    // Nhat ki that bai
                    $logs_message[] = $this->lang['update_log_move_file'] . ' ' . $file_i;
                    $logs_status[] = false;

                    // Luu nhat ki
                    $this->log($nv_update_config, $logs_message, $logs_status);

                    if ($is_ftp === true) {
                        $ftp->close();
                    }

                    return $this->lang['update_error_move_file'] . ' ' . $file_i;
                }

                // Nhat ki thanh cong
                $logs_message[] = $this->lang['update_log_move_file'] . ' ' . $file_i;
                $logs_status[] = true;
            }
        }

        // Resets the contents of the opcode cache
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        // Luu nhat ki
        $this->log($nv_update_config, $logs_message, $logs_status);

        return true;
    }

    /**
     * NvUpdate::getsysinfo()
     *
     * @return
     */
    private function getsysinfo()
    {
        global $sys_info;
        return $sys_info;
    }

    /**
     * NvUpdate::checksys()
     * Kiểm tra hệ thống hỗ trợ các thư viện yêu cầu hay không
     * Nếu hỗ trợ trả về array rỗng
     * Nếu không hỗ trợ trả về mảng chứ các thư viện không hỗ trợ
     * @return
     */
    public function checksys()
    {
        if (!empty($this->config['formodule'])) {
            return array();
        }

        $file_ini = NV_ROOTDIR . '/install/update/install/ini.php';
        $file_lang = NV_ROOTDIR . '/install/update/includes/language/' . NV_LANG_UPDATE . '/install.php';
        if (!file_exists($file_ini)) {
            return array();
        }
        $my_sys_info = $this->getsysinfo();
        $sys_info = $lang_module = array();
        $sys_info['ini_set_support'] = false;
        $sys_info['disable_functions'] = $my_sys_info['disable_functions'];

        include $file_ini;

        if (file_exists($file_lang)) {
            include $file_lang;
        }

        if (empty($nv_resquest_serverext_key)) {
            return array();
        }

        $result = array();
        foreach ($nv_resquest_serverext_key as $key) {
            if (empty($sys_info[$key])) {
                if (isset($lang_module[$key])) {
                    $langkey = $lang_module[$key];
                } elseif (isset($this->lang[$key])) {
                    $langkey = $this->lang[$key];
                } else {
                    $langkey = str_replace('_', ' ', $key);
                }
                if ($key == 'php_support') {
                    $langkey .= ' &gt;= ' . preg_replace('/\.([0-9]+)$/', '', $sys_info['php_required_min']) . ', &lt;= ' . preg_replace('/\.([0-9]+)$/', '', $sys_info['php_allowed_max']);
                }
                $result[$key] = array($langkey, $this->lang['not_compatible']);
            }
        }

        return $result;
    }

    /**
     * NvUpdate::template()
     *
     * @param mixed $contents
     * @return
     */
    public function template($contents)
    {
        global $language_array;

        $xtpl = new XTemplate('updatetheme.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
        $xtpl->assign('NV_LANG_UPDATE', NV_LANG_UPDATE);
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('CONFIG', $this->config);

        if (!empty($this->config['formodule'])) {
            // Lay module_file lam tieu de luon

            $xtpl->assign('SITE_TITLE', $this->config['type'] == 1 ? sprintf($this->lang['updatemod_title_update'], $this->config['formodule']) : sprintf($this->lang['updatemod_title_upgrade'], $this->config['formodule']));
        } else {
            $xtpl->assign('SITE_TITLE', $this->config['type'] == 1 ? $this->lang['update_site_title_update'] : $this->lang['update_site_title_upgrade']);
        }

        $xtpl->assign('CONTENT_TITLE', $this->lang['update_step_title_' . $this->config['step']]);

        $xtpl->assign('MODULE_CONTENT', $contents);

        $xtpl->assign('LANGTYPESL', NV_LANG_UPDATE);
        $langname = $language_array[NV_LANG_UPDATE]['name'];
        $xtpl->assign('LANGNAMESL', $langname);

        foreach ($this->config['allow_lang'] as $languageslist_i) {
            if (!empty($languageslist_i) and (NV_LANG_UPDATE != $languageslist_i)) {
                $xtpl->assign('LANGTYPE', $languageslist_i);
                $langname = $language_array[$languageslist_i]['name'];
                $xtpl->assign('LANGNAME', $langname);
                $xtpl->parse('main.looplang');
            }
        }

        $step_bar = array( $this->lang['update_step_1'], $this->lang['update_step_2'], $this->lang['update_step_3'] );

        foreach ($step_bar as $i => $step_bar_i) {
            $n = $i + 1;
            $class = '';

            if ($this->config['step'] >= $n) {
                $class = " class=\"";
                $class .= ($this->config['step'] > $n) ? 'passed_step' : '';
                $class .= ($this->config['step'] == $n) ? 'current_step' : '';
                $class .= "\"";
            }

            $xtpl->assign('CLASS_STEP', $class);
            $xtpl->assign('STEP_BAR', $step_bar_i);
            $xtpl->assign('NUM', $n);
            $xtpl->parse('main.step_bar.loop');
        }

        $xtpl->parse('main.step_bar');
        $xtpl->parse('main');
        return $xtpl->text('main');
    }

    /**
     * NvUpdate::step1()
     *
     * @param mixed $array
     * @return
     */
    public function step1($array)
    {

        $xtpl = new XTemplate('updatestep1.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('CONFIG', $this->config);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        $xtpl->assign('URL_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=deleteupdate&amp;checksess=' . NV_CHECK_SESSION);
        $xtpl->assign('URL_RETURN', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=siteinfo');

        $xtpl->assign('RELEASE_DATE', !empty($this->config['release_date']) ? nv_date('d/m/Y H:i:s', $this->config['release_date']) : 'N/A');
        $xtpl->assign('ALLOW_OLD_VERSION', !empty($this->config['allow_old_version']) ? implode(', ', $this->config['allow_old_version']) : 'N/A');
        $xtpl->assign('UPDATE_AUTO_TYPE', isset($this->config['update_auto_type']) ? $this->lang['update_auto_type_' . $this->config['update_auto_type']] : 'N/A');

        $array['ability_class'] = $array['isupdate_allow'] ? 'highlight_green' : 'highlight_red';
        $xtpl->assign('DATA', $array);

        if (!empty($this->config['formodule']) and empty($array['module_exist'])) {
            $xtpl->parse('main.notexistmod');
        } else {
            if (!empty($array['sysnotsupport'])) {
                foreach ($array['sysnotsupport'] as $ext) {
                    $xtpl->assign('EXTINFO', $ext);
                    $xtpl->parse('main.infoupdate.sysnotsupport.loop');
                }
                $xtpl->parse('main.infoupdate.sysnotsupport');
            }

            if ($array['isupdate_allow']) {
                $xtpl->parse('main.infoupdate.canupdate');
            } else {
                $xtpl->parse('main.infoupdate.cannotupdate');
            }

            $xtpl->parse('main.infoupdate');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }

    /**
     * NvUpdate::step2()
     *
     * @param mixed $array
     * @param mixed $substep
     * @return
     */
    public function step2($array, $substep)
    {
        global $global_config;

        $xtpl = new XTemplate('updatestep2.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('CONFIG', $this->config);
        $xtpl->assign('DATA', $array);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        if ($substep == 1) {
            // Back up cac file de phong bat trac

            if ($array['data_backuped']) {
                // Thong bao da backup CSDL vao luc

                $xtpl->assign('DATA_MESSAGE', sprintf($this->lang['update_data_backuped'], nv_date('H:i d/m/Y', $array['data_backuped'])));
                $xtpl->parse('main.step1.data_backuped');
            }

            if ($array['is_data_backup']) {
                // Cho phep backup CSDL

                $xtpl->assign('URL_DUMP_DB_BACKUP', NV_BASE_SITEURL . 'install/update.php?step=' . $this->config['step'] . '&amp;substep=' . $substep . '&amp;dump&amp;checksess=' . NV_CHECK_SESSION);
                $xtpl->parse('main.step1.is_data_backup');
            } else {
                // Thong bao khong cho backup CSDL nua

                $xtpl->parse('main.step1.no_data_backup');
            }

            if ($array['file_backuped']) {
                // Thong bao da backup CODE vao luc

                $xtpl->assign('FILE_MESSAGE', sprintf($this->lang['update_file_backuped'], nv_date('H:i d/m/Y', $array['file_backuped'])));
                $xtpl->parse('main.step1.file_backuped');
            }

            if ($array['is_file_backup']) {
                // Cho phep backup CODE

                $xtpl->assign('URL_DUMP_FILE_BACKUP', NV_BASE_SITEURL . 'install/update.php?step=' . $this->config['step'] . '&substep=' . $substep . '&amp;dumpfile&amp;checksess=' . NV_CHECK_SESSION);
                $xtpl->parse('main.step1.is_file_backup');
            }

            $xtpl->parse('main.step1');
        } elseif ($substep == 2) {
            if ($this->config['update_auto_type'] == 0) {
                $xtpl->parse('main.step2.manual');
            } else {
                if ($array['is_move_file']) {
                    $xtpl->parse('main.step2.automatic.semiautomatic');
                } elseif ($this->config['update_auto_type'] == 1) {
                    $xtpl->parse('main.step2.automatic.fullautomatic');
                } else {
                    $xtpl->parse('main.step2.automatic.info');
                }

                // Cong viec lien quan CSDL
                if (!empty($array['data_list'])) {
                    foreach ($array['data_list'] as $w) {
                        $w['title'] = isset($this->lang[$w['langkey']]) ? $this->lang[$w['langkey']] : 'N/A';

                        $xtpl->assign('ROW', $w);
                        $xtpl->parse('main.step2.automatic.data.loop');
                    }

                    $xtpl->parse('main.step2.automatic.data');
                } else {
                    $xtpl->parse('main.step2.automatic.nodata');
                }

                // Cong viec lien quan cac file
                if (!empty($array['file_list'])) {
                    foreach ($array['file_list'] as $w) {
                        $xtpl->assign('ROW', $w);
                        $xtpl->parse('main.step2.automatic.file.loop');
                    }

                    $xtpl->parse('main.step2.automatic.file');
                } else {
                    $xtpl->parse('main.step2.automatic.nofile');
                }

                $xtpl->parse('main.step2.automatic');
            }

            $xtpl->parse('main.step2');
        } elseif ($substep == 3) {
            if (!empty($array['errorStepMoveFile'])) {
                $xtpl->parse('main.step3.error');
            } else {
                // Viet cac tien trinh
                foreach ($array['task'] as $task) {
                    $xtpl->assign('ROW', $task);
                    $xtpl->parse('main.step3.data.loop');
                }

                if (!empty($array['stopprocess'])) {
                    // Dung cong viec do loi
                    global $nv_update_config;
                    $xtpl->assign('ERROR_MESSAGE', sprintf($this->lang['update_task_error_message'], $array['stopprocess']['title'], $nv_update_config['support_website']));
                    $xtpl->parse('main.step3.data.errorProcess');
                } elseif ($array['AllPassed'] == true) {
                    // Hoan tat cong viec va chuyen sang buoc tiep theo

                    $xtpl->parse('main.step3.data.AllPassed');
                } else {
                    // Tiep tuc khoi chay tien trinh

                    $xtpl->parse('main.step3.data.ConStart');
                }

                if ($array['AllPassed'] == true and empty($array['stopprocess'])) {
                    $xtpl->parse('main.step3.data.next_step');
                }

                $xtpl->parse('main.step3.data');
            }

            $xtpl->parse('main.step3');
        } elseif ($substep == 4) {
            global $sys_info, $nv_update_config;

            if (!$array['getcomplete'] and !empty($array['file_list'])) {
                if (substr($sys_info['os'], 0, 3) == 'WIN') {
                    $xtpl->parse('main.step4.win');
                }

                if ($array['FTP_nosupport']) {
                    $xtpl->parse('main.step4.FTP_nosupport');
                } elseif ($array['check_FTP']) {
                    $xtpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/update.php?step=' . $this->config['step'] . '&amp;substep=' . $substep);

                    if (!empty($array['ftpdata']['error']) and $array['ftpdata']['show_ftp_error']) {
                        $xtpl->parse('main.step4.check_FTP.errorftp');
                    }

                    $xtpl->parse('main.step4.check_FTP');
                }
            }

            $xtpl->assign('OK_MESSAGE', sprintf($this->lang['update_move_complete'], sizeof($nv_update_config['updatelog']['file_list'])));

            if (empty($array['file_list'])) {
                $xtpl->parse('main.step4.complete');
                $xtpl->parse('main.step4.next_step');
            } else {
                $xtpl->assign('PROCESS_MESSAGE', sprintf($this->lang['update_move_num'], sizeof($array['file_list']), sizeof($nv_update_config['updatelog']['file_list'])));
                $xtpl->parse('main.step4.process');
            }

            // Danh sach cac file se bi tac dong
            foreach ($nv_update_config['updatelog']['file_list'] as $fileID => $fileName) {
                $xtpl->assign('ROW', array(
                    'id' => $fileID,
                    'name' => $fileName,
                    'status' => in_array($fileName, $array['file_list']) ? '' : ' iok'
                ));
                $xtpl->parse('main.step4.loop');
            }

            $xtpl->parse('main.step4');
        } elseif ($substep == 5) {
            if ($array['error']) {
                $xtpl->parse('main.step5.error');
            } else {
                $xtpl->parse('main.step5.guide');
            }

            $xtpl->parse('main.step5');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }

    /**
     * NvUpdate::step3()
     *
     * @param mixed $array
     * @return
     */
    public function step3($array)
    {
        global $global_config;

        $xtpl = new XTemplate('updatestep3.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('CONFIG', $this->config);
        $xtpl->assign('DATA', $array);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        $xtpl->assign('URL_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=deleteupdate&amp;checksess=' . NV_CHECK_SESSION);
        $xtpl->assign('URL_GOHOME', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true));
        $xtpl->assign('URL_GOADMIN', NV_BASE_ADMINURL);

        if (empty($this->config['formodule'])) {
            $xtpl->parse('main.typefull');
        } else {
            $xtpl->parse('main.typemodule');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }

    /**
     * NvUpdate::PackageErrorTheme()
     *
     * @return
     */
    public function PackageErrorTheme()
    {
        global $global_config;

        $xtpl = new XTemplate('packageerror.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('CONFIG', $this->config);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        $xtpl->assign('URL_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=deleteupdate&amp;checksess=' . NV_CHECK_SESSION);
        $xtpl->assign('URL_RETURN', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=siteinfo');

        $xtpl->parse('main');
        return $xtpl->text('main');
    }

    /**
     * NvUpdate::version_info()
     *
     * @param mixed $array
     * @return
     */
    public function version_info($array)
    {
        $xtpl = new XTemplate('updatestep3.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('CONFIG', $this->config);
        $xtpl->assign('DATA', $array);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        if ($array['checkversion']) {
            $xtpl->parse('version_info.checkversion');
        }

        $xtpl->parse('version_info');
        $_info = $xtpl->text('version_info');
        exit($_info);
    }

    /**
     * NvUpdate::module_info()
     *
     * @param mixed $exts
     * @return
     */
    public function module_info($exts)
    {
        global $global_config;

        $xtpl = new XTemplate('updatestep3.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('CONFIG', $this->config);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        $i = 0;
        foreach ($exts as $mod) {
            if (($mod['type'] == 'module' and in_array($mod['name'], array('banners', 'comment', 'contact', 'feeds', 'freecontent', 'menu', 'news', 'page', 'seek', 'statistics', 'users', 'voting', 'two-step-verification'))) or ($mod['type'] == 'theme' and in_array($mod['name'], array('default', 'mobile_default')))) {
                $mod['note'] = $this->lang['update_mod_uptodate'];
            } else {
                $mod['note'] = $this->lang['update_mod_othermod'];
            }

            $mod['class'] = $i++ % 2 ? 'specalt' : 'spec';
            $mod['time'] = $mod['date'] ? nv_date('d/m/y H:i', strtotime($mod['date'])) : 'N/A';

            $xtpl->assign('ROW', $mod);
            $xtpl->parse('module_info.loop');
        }

        $xtpl->parse('module_info');
        $_info = $xtpl->text('module_info');
        exit($_info);
    }

    /**
     * NvUpdate::module_com_info()
     *
     * @param mixed $onlineModules
     * @return
     */
    public function module_com_info($onlineModules)
    {
        global $global_config;

        $xtpl = new XTemplate('updatestep3.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('CONFIG', $this->config);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        $lastest_version = 'N/A';
        if (!isset($onlineModules[$this->config['formodule']])) {
            $xtpl->parse('commodule.notcertified');
        } else {
            $lastest_version = isset($onlineModules[$this->config['formodule']]['version']) ? ( string )$onlineModules[$this->config['formodule']]['version'] : 'N/A';

            if (nv_version_compare($lastest_version, $this->config['to_version']) > 0) {
                $xtpl->parse('commodule.checkversion');
            }
        }

        $xtpl->assign('LASTEST_VERSION', $lastest_version);

        $xtpl->parse('commodule');
        $_info = $xtpl->text('commodule');
        exit($_info);
    }

    /**
     * NvUpdate::log()
     *
     * @param mixed $nv_update_config
     * @param mixed $content
     * @param mixed $status
     * @return
     */
    public function log($nv_update_config, $content, $status)
    {
        global $client_info, $admin_info;

        // Danh dau phien bat dau khoi tao
        if (!isset($nv_update_config['updatelog']['starttime'])) {
            $nv_update_config['updatelog']['starttime'] = NV_CURRENTTIME;
            $this->set_data_log($nv_update_config['updatelog']);
        }

        $file_log = 'log-update-' . nv_date('H-i-s-d-m-Y', $nv_update_config['updatelog']['starttime']) . '-' . NV_CHECK_SESSION . '.log';
        $time = nv_date('H:i:s_d-m-Y');

        if (!is_array($content)) {
            $content = array( 0 => $content );
            $status = array( 0 => $status );
        }

        $contents = '';
        if (!file_exists(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $file_log)) {
            $contents .= $this->lang['update_log_start'] . ': ' . $time . "\n";
            nv_insert_logs(NV_LANG_UPDATE, 'update', $this->lang['update_log_start'], $time, $admin_info['userid']);
        }

        foreach ($content as $key => $mess) {
            $st = empty($status[$key]) ? 'FAILURE' : 'SUCCESS';
            $contents .= $time . ' | ' . $client_info['ip'] . ' | ' . $mess . ' | ' . $st . "\n";
            nv_insert_logs(NV_LANG_UPDATE, 'update', $mess, $st, $admin_info['userid']);
        }

        file_put_contents(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $file_log, $contents, FILE_APPEND);
    }

    /**
     * NvUpdate::call_error()
     *
     * @param mixed $message
     * @return
     */
    public function call_error($message)
    {
        $xtpl = new XTemplate('updateerror.tpl', NV_ROOTDIR . '/install/tpl');
        $xtpl->assign('LANG', $this->lang);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('MESSAGE', $message);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }

    /**
     * NvUpdate::trigger_error()
     *
     * @param mixed $message
     * @return void
     */
    public function trigger_error($message)
    {
        $_info = $this->call_error($message);
        die($_info);
    }
}

// Load lai phien lam viec
$nv_update_config['updatelog'] = array();
if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/config_update_' . $nv_update_config['packageID'] . '.php')) {
    include NV_ROOTDIR . '/' . NV_DATADIR . '/config_update_' . $nv_update_config['packageID'] . '.php' ;
}

// Buoc nang cap
$nv_update_config['step'] = $nv_Request->get_int('step', 'get', 1);
if ($nv_update_config['step'] < 1 or !isset($nv_update_config['updatelog']['step']) or $nv_update_config['step'] > 3 or $nv_update_config['updatelog']['step'] < ($nv_update_config['step'] - 1)) {
    $nv_update_config['step'] = 1;
}

$NvUpdate = new NvUpdate($nv_update_config);

// Goi $site_mod neu cap nhat module
if (!empty($nv_update_config['formodule'])) {
    $site_mods = nv_site_mods();
}

// Trang chinh
$contents = '';

if ($nv_update_config['step'] == 1) {
    // Kiem tra phien ban va tuong thich du lieu
    if ($NvUpdate->check_package() === false) {
        // Kiem tra chuan goi cap nhat

        $contents = $NvUpdate->PackageErrorTheme();
    } else {
        $array = array();

        // Kiem tra ton tai module can nang cap neu kieu nang cap module
        if (!empty($nv_update_config['formodule'])) {
            $array['module_exist'] = false;

            foreach ($site_mods as $mod) {
                if ($mod['module_file'] == $nv_update_config['formodule']) {
                    $array['module_exist'] = true;
                    break;
                }
            }

            if ($array['module_exist']) {
                // Lay phien ban module
                $sth = $db->prepare('SELECT version FROM ' . $db_config['prefix'] . '_setup_extensions WHERE basename= :basename');
                $sth->bindParam(':basename', $nv_update_config['formodule'], PDO::PARAM_STR);
                $sth->execute();
                $row = $sth->fetch();

                $v = '';
                $d = 0;
                if (preg_match("/^([^\s]+)\s+([\d]+)$/", $row['version'], $matches)) {
                    $v = ( string )$matches[1];
                    $d = ( int )$matches[2];
                }

                $array['current_version'] = trim($v);
            } else {
                $array['current_version'] = '';
            }
        } else {
            $array['current_version'] = $global_config['version'];
        }

        $array['sysnotsupport'] = $NvUpdate->checksys();

        // Kiem tra ho tro phien ban nang cap
        if (in_array($array['current_version'], $nv_update_config['allow_old_version'])) {
            if (!empty($array['sysnotsupport'])) {
                $array['ability'] = $lang_module['update_ability_2'];
                $array['isupdate_allow'] = false;
            } else {
                $array['ability'] = $lang_module['update_ability_1'];
                $array['isupdate_allow'] = true;
            }
        } else {
            if (!empty($array['sysnotsupport'])) {
                $array['ability'] = $lang_module['update_ability_3'];
            } else {
                $array['ability'] = $lang_module['update_ability_0'];
            }
            $array['isupdate_allow'] = false;
        }

        // Kiem tra va ghi log data
        $step = ($array['isupdate_allow']) ? 1 : 0;
        if ($step == 0 or !isset($nv_update_config['updatelog']['step']) or $nv_update_config['updatelog']['step'] < $step) {
            $nv_update_config['updatelog']['step'] = $step;
            $nv_update_config['updatelog']['old_version'] = $array['current_version'];
            $NvUpdate->set_data_log($nv_update_config['updatelog']);
        }
        unset($step);

        $contents = $NvUpdate->step1($array);
    }
} elseif ($nv_update_config['step'] == 2) {// Buoc nang cap: Backup => List cong viec => Cap nhat CSDL => Di chuyen file => Nang cap bang tay.
    $array = array();
    $set_log = false;

    // Kiem tra thu tu cac buoc con
    $nv_update_config['substep'] = $nv_Request->get_int('substep', 'get', 1);
    if ($nv_update_config['substep'] < 1 or !isset($nv_update_config['updatelog']['substep']) or $nv_update_config['substep'] > 5 or $nv_update_config['updatelog']['substep'] < ($nv_update_config['substep'] - 1)) {
        $nv_update_config['substep'] = 1;
    }

    if ($nv_update_config['substep'] == 1) {
        // Backup CSDL va CODE

        // Backup CSDL
        if ($nv_Request->isset_request('dump', 'get')) {
            $checksess = $nv_Request->get_title('checksess', 'get', '');
            if ($checksess != NV_CHECK_SESSION) {
                die('Error!!!');
            }

            $type = $nv_Request->get_title('type', 'get', '');

            $current_day = mktime(0, 0, 0, date('n', NV_CURRENTTIME), date('j', NV_CURRENTTIME), date('Y', NV_CURRENTTIME));

            $contents = array();
            $contents['savetype'] = ($type == 'sql') ? 'sql' : 'gz';
            $file_ext = ($contents['savetype'] == 'sql') ? 'sql' : 'sql.gz';
            $log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup';

            $contents['filename'] = $log_dir . '/' . md5(nv_genpass(10) . NV_CHECK_SESSION) . '_' . $current_day . '.' . $file_ext;

            if (!file_exists($contents['filename'])) {
                $contents['tables'] = array();
                $res = $db->query("SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'");
                while ($item = $res->fetch(3)) {
                    $contents['tables'][] = $item[0];
                }
                $res->closeCursor();

                $contents['type'] = 'all';

                include NV_ROOTDIR . '/includes/core/dump.php' ;

                $dump = nv_dump_save($contents);

                // Ghi log
                $NvUpdate->log($nv_update_config, $lang_module['update_dump'] . ' ' . $contents['savetype'], $dump);

                if ($dump == false) {
                    die($lang_module['update_dump_error']);
                } else {
                    $file = str_replace(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup/', '', $dump[0]);

                    // Danh dau da sao luu CSDL
                    $nv_update_config['updatelog']['data_backuped'] = NV_CURRENTTIME;
                    $NvUpdate->set_data_log($nv_update_config['updatelog']);

                    die($lang_module['update_dump_ok'] . ' ' . nv_convertfromBytes($dump[1]) . '<br /><a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=database&amp;" . NV_OP_VARIABLE . "=getfile&amp;filename=" . $file . "&amp;checkss=" . md5($file . NV_CHECK_SESSION) . '" title="' . $lang_module['update_dump_download'] . '">' . $lang_module['update_dump_download'] . '</a>');
                }
            } else {
                die($lang_module['update_dump_exist']);
            }
        }

        // Download CODE thay doi
        if ($nv_Request->isset_request('downfile', 'get')) {
            $checksess = $nv_Request->get_title('checksess', 'get', '');
            if ($checksess != NV_CHECK_SESSION) {
                die('Error!!!');
            }

            $file = $nv_Request->get_title('downfile', 'get', '');

            if (!file_exists(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $file)) {
                $NvUpdate->log($nv_update_config, $lang_module['update_log_dump_file_down'], false);
                die('Error Access!!!');
            } else {
                $NvUpdate->log($nv_update_config, $lang_module['update_log_dump_file_down'], true);

                //Download file
                $download = new NukeViet\Files\Download(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $file, NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs', 'backup_update_' . date('Y_m_d') . '.zip');
                $download->download_file();
                exit();
            }
        }

        // Sao luu file thay doi
        if ($nv_Request->isset_request('dumpfile', 'get')) {
            $zip_file_backup = array();

            // Sao luu file thay doi
            if (!empty($nv_update_config['updatelog']['file_list'])) {
                foreach ($nv_update_config['updatelog']['file_list'] as $file_i) {
                    if (is_file(NV_ROOTDIR . '/' . $file_i)) {
                        $zip_file_backup[] = NV_ROOTDIR . '/' . $file_i;
                    }
                }
            }

            // Sao luu tat ca cac file | Cu de nhung tam thoi co le khong dung duoc
            if (empty($zip_file_backup)) {
                $file_list = $NvUpdate->list_all_file(NV_ROOTDIR);

                foreach ($file_list as $file_i) {
                    if (!preg_match('/^install\/update\/(.*)$/', $file_i)) {
                        $zip_file_backup[] = NV_ROOTDIR . '/' . $file_i;
                    }
                }
            }

            if (!empty($zip_file_backup)) {
                $file_src = 'backup_update_' . date('Y_m_d') . '_' . NV_CHECK_SESSION . '.zip';

                // Kiem tra file ton tai
                $filename2 = $file_src;
                $i = 1;
                while (file_exists(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $filename2)) {
                    $filename2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $file_src);
                    $i++;
                }

                $zip = new PclZip(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $filename2);
                $return = $zip->add($zip_file_backup, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR);

                if (empty($return)) {
                    // Ghi Log
                    $NvUpdate->log($nv_update_config, $lang_module['update_log_dump_file'], false);

                    die($lang_module['update_file_backup_error']);
                } else {
                    // Ghi log
                    $NvUpdate->log($nv_update_config, $lang_module['update_log_dump_file'], true);

                    // Danh dau da sao luu
                    $nv_update_config['updatelog']['file_backuped'] = NV_CURRENTTIME;
                    $NvUpdate->set_data_log($nv_update_config['updatelog']);

                    die('<a href="' . NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=4&downfile=' . $filename2 . '&checksess=' . NV_CHECK_SESSION . '" title="' . $lang_module['update_log_dump_file_down'] . '">' . $lang_module['update_file_backup_ok'] . '</a>');
                }
            }
        }

        // Cong viec di chuyen file
        if (!isset($nv_update_config['updatelog']['file_list'])) {
            $file_list = $NvUpdate->list_all_file();
            $nv_update_config['updatelog']['file_list'] = $file_list;
            $set_log = true;
        }

        // Cong viec nang cap CSDL
        if (!isset($nv_update_config['updatelog']['data_list'])) {
            $data_list = $NvUpdate->list_data_update();
            $nv_update_config['updatelog']['data_list'] = $data_list;
            $set_log = true;
        }

        // Kiem tra va backup
        $array['is_file_backup'] = true;
        $array['file_backuped'] = isset($nv_update_config['updatelog']['file_backuped']) ? $nv_update_config['updatelog']['file_backuped'] : 0;
        if (isset($nv_update_config['updatelog']['is_start_move_file'])) {
            // Bat dau di chuyen file roi thi khong backup

            $array['is_file_backup'] = false;
        } elseif (empty($nv_update_config['updatelog']['file_list'])) {
            $array['is_file_backup'] = false;
        }

        $array['is_data_backup'] = true;
        $array['data_backuped'] = isset($nv_update_config['updatelog']['data_backuped']) ? $nv_update_config['updatelog']['data_backuped'] : 0;
        if (isset($nv_update_config['updatelog']['is_start_up_db'])) {
            // Da cap nhat CSDL roi thi khong backup

            $array['is_data_backup'] = false;
        }

        // Luu lai cong viec se thuc hien
        if (!isset($nv_update_config['updatelog']['substep']) or $nv_update_config['updatelog']['substep'] < 1) {
            $nv_update_config['updatelog']['substep'] = 1;
            // Buoc nay co the bo qua do do luu de sang buoc 2
            $set_log = true;
        }

        // Ghi data log
        if ($set_log) {
            $NvUpdate->set_data_log($nv_update_config['updatelog']);
        }

        // Kiem tra va chuyen huong neu khong co thay doi CSDL va FILE
        if (empty($nv_update_config['updatelog']['file_list']) and empty($nv_update_config['updatelog']['data_list'])) {
            if ($nv_update_config['update_auto_type'] == 0) {
                // Neu la nang cap thu cong thi kiem tra file va dieu huong

                if (file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                    // Chuyen buoc 2/2 - Nang cap thu cong
                    nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=2&substep=2');
                } else {
                    // Chuyen buoc 3
                    $nv_update_config['updatelog']['step'] = 2;
                    $NvUpdate->set_data_log($nv_update_config['updatelog']);

                    nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=3');
                }
            } elseif (file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                // Neu nguoc lai kiem tra file ton tai chuyen buoc 5/2

                $nv_update_config['updatelog']['substep'] = 4;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);

                nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=2&substep=5');
            } else {
                // Chuyen buoc 3

                $nv_update_config['updatelog']['step'] = 2;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);

                nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=3');
            }
        }
    } elseif ($nv_update_config['substep'] == 2) {
        // Kiem tra va thong ke cac cong viec se thuc hien

        // Nang cap bang tay
        if ($nv_update_config['update_auto_type'] == 0) {
            $array['guide'] = 'N/A';

            // Co file huong dan thi goi ra
            if (file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                $array['guide'] = file_get_contents(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html');
            }
            // Khong co file huong dan thi chuyen sang buoc 3
            else {
                $nv_update_config['updatelog']['step'] = 2;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);

                nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=3');
                exit();
            }

            // Ghi lai data log
            if ($nv_update_config['updatelog']['step'] < 2) {
                $nv_update_config['updatelog']['step'] = 2;
                $set_log = true;
            }
        }
        // Nang cap tu dong/Nua tu dong
        else {
            // De den duoc day thi bat buoc phai co mot trong hai cong viec ben duoi
            // Danh dau buoc nay de den buoc tiep theo
            if ($nv_update_config['updatelog']['substep'] < 2) {
                $nv_update_config['updatelog']['substep'] = 2;
                $set_log = true;
            }

            // Xac dinh phai di chuyen cac file thu cong
            $array['is_move_file'] = false;
            if ($nv_update_config['update_auto_type'] == 2 and !empty($nv_update_config['updatelog']['file_list'])) {
                $array['is_move_file'] = true;
            }

            $array['file_list'] = $nv_update_config['updatelog']['file_list'];
            $array['data_list'] = $nv_update_config['updatelog']['data_list'];
        }

        if ($set_log === true) {
            $NvUpdate->set_data_log($nv_update_config['updatelog']);
        }
    } elseif ($nv_update_config['substep'] == 3) {
        // Buoc cap nhat CSDL

        // Kiem tra loi neu buoc cap nhat nua tu dong
        $array['errorStepMoveFile'] = false;
        if ($nv_update_config['update_auto_type'] == 2) {
            $check_list_file = $NvUpdate->list_all_file();
            if (!empty($check_list_file)) {
                $array['errorStepMoveFile'] = true;
            } elseif (!isset($nv_update_config['updatelog']['is_start_move_file'])) {
                // Danh dau da di chuyen cac file roi
                $nv_update_config['updatelog']['is_start_move_file'] = NV_CURRENTTIME;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);
            }

            // Neu khong co cong viec nang cap CSDL nao va kieu nang cap nua tu dong thi chuyen den buoc 3 neu khong co huong dan nang cap
            if (empty($nv_update_config['updatelog']['data_list']) and !file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                $nv_update_config['updatelog']['step'] = 2;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);

                nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=3');
            }
            // Chuyen den buoc 5 de xem huong dan nang cap
            elseif (empty($nv_update_config['updatelog']['data_list'])) {
                $nv_update_config['updatelog']['substep'] = 4;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);

                nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=2&substep=5');
            }
        }

        // Neu khong co cong viec nang cap CSDL nao thi chuyen den buoc 4
        if (empty($nv_update_config['updatelog']['data_list'])) {
            $nv_update_config['updatelog']['substep'] = 3;
            $NvUpdate->set_data_log($nv_update_config['updatelog']);

            nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=2&substep=4');
        }

        if (!$array['errorStepMoveFile']) {
            // Tien trinh bat dau chay
            if ($nv_Request->isset_request('load', 'get')) {
                $func = $nv_Request->get_title('load', 'get', '');

                $nv_update_baseurl = NV_BASE_SITEURL . 'install/update.php?step=2&substep=3&load=' . $func;
                $old_module_version = $nv_update_config['updatelog']['old_version'];

                /*
                 * Chuan hoa tra ve cho Ajax status|funcname|functitle|url|lang|message|stop|allcomplete status: - 0: That bai - 1: Thanh cong funcname: Ten ham tiep theo thuc hien functitle: Ten cong viec tiep theo se thuc hien url: Duong dan tiep theo duoc load lang: Cac ngon ngu bi loi message: Thong tin (duoc add vao functitle sau dau -) stop: Dung tien trinh allcomplete: Hoan tat tat ca tien trinh
                 */

                $return = array(
                    'status' => '0',
                    'funcname' => 'NO',
                    'functitle' => 'NO',
                    'url' => 'NO',
                    'lang' => 'NO',
                    'message' => 'NO',
                    'stop' => '1',
                    'allcomplete' => '0'
                );

                if (!isset($nv_update_config['updatelog']['data_list'][$func])) {
                    $return['stop'] = '1';
                }
                if (!nv_function_exists($func)) {
                    $return['stop'] = '1';
                }

                $check_return = call_user_func($func);
                // Goi ham thuc hien nang cap

                // Trang thai thuc hien
                $return['status'] = $check_return['status'] ? '1' : '0';
                $return['stop'] = ($check_return['status'] == 0 and $nv_update_config['updatelog']['data_list'][$func]['require'] == 2) ? '1' : '0';
                $return['message'] = $check_return['message'];

                $last_task = end($nv_update_config['updatelog']['data_list']);
                $last_task_key = key($nv_update_config['updatelog']['data_list']);
                // Kiem tra ket thuc tien trinh
                if ($last_task_key == $func and $return['stop'] == '0') {
                    $return['allcomplete'] = '1';

                    // Ghi lai de chuyen sang buoc tiep theo
                    if ($nv_update_config['update_auto_type'] == 2) {
                        if (file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                            $nv_update_config['updatelog']['substep'] = 4;
                            $NvUpdate->set_data_log($nv_update_config['updatelog']);
                        } else {
                            $nv_update_config['updatelog']['step'] = 2;
                            $NvUpdate->set_data_log($nv_update_config['updatelog']);
                        }
                    } else {
                        $nv_update_config['updatelog']['substep'] = 3;
                        $NvUpdate->set_data_log($nv_update_config['updatelog']);
                    }
                }

                if ($return['allcomplete'] != '1' and $return['stop'] != '1') {
                    // Kiem tra tiep tuc, neu tiep tuc thi khong can URL
                    if ($check_return['next']) {
                        $is_get_next = false;
                        foreach ($nv_update_config['updatelog']['data_list'] as $k => $v) {
                            if ($is_get_next == true) {
                                $return['funcname'] = $k;
                                $v['title'] = isset($lang_module[$v['langkey']]) ? $lang_module[$v['langkey']] : 'N/A';
                                $return['functitle'] = $v['title'];
                                break;
                            }
                            if ($k == $func) {
                                $is_get_next = true;
                            }
                        }
                        unset($is_get_next, $k, $v);
                    } else {
                        $return['url'] = $check_return['link'];
                        $return['funcname'] = $func;
                        $langkey = $nv_update_config['updatelog']['data_list'][$func]['langkey'];
                        $return['functitle'] = isset($lang_module[$langkey]) ? $lang_module[$langkey] : 'N/A';
                        unset($langkey);
                    }
                }

                // Danh dau log passed
                if ($check_return['complete'] == 1) {
                    $nv_update_config['updatelog']['data_passed'][$func] = $check_return['status'] ? 1 : 2;
                }

                if (!isset($nv_update_config['updatelog']['is_start_up_db'])) {
                    $nv_update_config['updatelog']['is_start_up_db'] = NV_CURRENTTIME;
                }

                // Ghi log data
                $NvUpdate->set_data_log($nv_update_config['updatelog']);

                // Ghi logs
                $langkey = $nv_update_config['updatelog']['data_list'][$func]['langkey'];
                $functitle = isset($lang_module[$langkey]) ? $lang_module[$langkey] : 'N/A';
                $log_message = $functitle . ($check_return['message'] ? (' - ' . $check_return['message']) : '');
                $NvUpdate->log($nv_update_config, $log_message, $check_return['status']);

                die(implode('|', $return));
            }

            $array['task'] = array();
            $array['started'] = false;
            // Da bat dau chua
            $array['nextfunction'] = '';
            // Ham tiep theo se thuc hien
            $array['nextftitle'] = '';
            // Ten cong viec tiep theo se thuc hien
            $array['stopprocess'] = array();
            // Dung tien trinh
            $array['AllPassed'] = false;
            // Da hoan tat toan bo cac cong viec

            $get_next_func = false;
            $num_passed = 0;

            foreach ($nv_update_config['updatelog']['data_list'] as $funcsname => $task) {
                // Xuat tieu de
                $task['title'] = isset($lang_module[$task['langkey']]) ? $lang_module[$task['langkey']] : 'N/A';

                // Khoi tao ham tiep theo thuc hien
                if (empty($array['nextfunction'])) {
                    $array['nextfunction'] = $funcsname;
                    $array['nextftitle'] = $task['title'];
                }

                // Danh dau ham tiep theo se thuc hien
                if ($get_next_func == true) {
                    $array['nextfunction'] = $funcsname;
                    $array['nextftitle'] = $task['title'];
                    $get_next_func = false;
                }

                // $passed:
                //	- 0: Chua thuc hien
                //	- 1: Da hoan thanh
                //	- 2: That bai
                $passed = isset($nv_update_config['updatelog']['data_passed'][$funcsname]) ? $nv_update_config['updatelog']['data_passed'][$funcsname] : 0;
                switch ($passed) {
                    case 0:
                        $class = '';
                        break;
                    case 1:
                        $class = ' iok';
                        break;
                    default:
                        $class = (($task['require'] == 0) ? ' iok' : (($task['require'] == 1) ? ' iwarn' : ' ierror'));
                }
                $class_trim = trim($class);

                // Da thuc hien thi danh dau da thuc hien
                if ($array['started'] == false and $passed > 0) {
                    $array['started'] = true;
                }

                // Tinh toan ham tiep theo se thuc hien, them vao danh dach cac cong viec da thuc hien (du thanh cong hay that bai)
                if ($passed > 0) {
                    $get_next_func = true;
                    $num_passed = $num_passed + 1;
                }

                // Dung tien trinh
                if ($class_trim == 'ierror' and empty($array['stopprocess'])) {
                    $array['stopprocess'] = array( 'id' => $funcsname, 'title' => $task['title'] );
                }

                $status_title = $lang_module['update_task' . $class_trim];

                $array['task'][$funcsname] = array(
                    'id' => $funcsname,
                    'title' => $task['title'],
                    'require' => $task['require'],
                    'class' => $class,
                    'status' => $status_title
                );
            }

            // Kiem tra hoan tat
            if ($num_passed == sizeof($array['task'])) {
                $array['AllPassed'] = true;

                // Danh dau hoan tat de tiep tuc buoc di chuyen file (neu KHONG xay ra loi)
                if (empty($array['stopprocess'])) {
                    if ($nv_update_config['update_auto_type'] == 2) {
                        if (file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                            $nv_update_config['updatelog']['substep'] = 4;
                            $NvUpdate->set_data_log($nv_update_config['updatelog']);
                        } else {
                            $nv_update_config['updatelog']['step'] = 2;
                            $NvUpdate->set_data_log($nv_update_config['updatelog']);
                        }
                    } else {
                        $nv_update_config['updatelog']['substep'] = 3;
                        $NvUpdate->set_data_log($nv_update_config['updatelog']);
                    }
                }
            }

            // Kiem tra buoc tiep theo
            if ($nv_update_config['update_auto_type'] == 2) {
                if (file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                    $array['NextStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=5';
                } else {
                    $array['NextStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=3';
                }
            } else {
                $array['NextStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=4';
            }
        }
    } elseif ($nv_update_config['substep'] == 4) {
        // Di chuyen cac file

        // Neu khong co file can di chuyen thi chuyen sang buoc 2/5 hoac buoc 3
        if (empty($nv_update_config['updatelog']['file_list'])) {
            // Chuyen den buoc 3 neu khong co huong dan nang cap khac bang tay
            if (!file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                $nv_update_config['updatelog']['step'] = 2;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);

                nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=3');
            } else {
                // Chuyen den buoc 2/5

                $nv_update_config['updatelog']['substep'] = 4;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);

                nv_redirect_location(NV_BASE_SITEURL . 'install/update.php?step=2&substep=5');
            }
        }

        // Tu dong nhan dien remove_path
        if ($nv_Request->isset_request('tetectftp', 'post')) {
            $ftp_server = nv_unhtmlspecialchars($nv_Request->get_title('ftp_server', 'post', '', 1));
            $ftp_port = intval($nv_Request->get_title('ftp_port', 'post', '21', 1));
            $ftp_user_name = nv_unhtmlspecialchars($nv_Request->get_title('ftp_user_name', 'post', '', 1));
            $ftp_user_pass = nv_unhtmlspecialchars($nv_Request->get_title('ftp_user_pass', 'post', '', 1));

            if (!$ftp_server or !$ftp_user_name or !$ftp_user_pass) {
                die('ERROR|' . $lang_module['ftp_error_empty']);
            }

            $ftp = new NukeViet\Ftp\Ftp($ftp_server, $ftp_user_name, $ftp_user_pass, array( 'timeout' => 10 ), $ftp_port);

            if (!empty($ftp->error)) {
                $ftp->close();
                die('ERROR|' . ( string )$ftp->error);
            } else {
                $list_valid = array( NV_ASSETS_DIR, 'includes', 'index.php', 'modules', 'themes', 'vendor' );

                $ftp_root = $ftp->detectFtpRoot($list_valid, NV_ROOTDIR);

                if ($ftp_root === false) {
                    $ftp->close();
                    die('ERROR|' . (empty($ftp->error) ? $lang_module['ftp_error_detect_root'] : ( string )$ftp->error));
                }

                $ftp->close();
                die('OK|' . $ftp_root);
            }

            $ftp->close();
            die('ERROR|' . $lang_module['ftp_error_detect_root']);
        }

        // Danh sach cac file con lai
        $array['file_list'] = $NvUpdate->list_all_file();

        // Buoc tiep theo
        if (!file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
            $array['NextStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=3';
        } else {
            $array['NextStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=5';
        }

        // Di chuyen cac file
        if ($nv_Request->isset_request('move', 'get')) {
            if (!isset($nv_update_config['updatelog']['is_start_move_file'])) {
                // Danh dau da di chuyen cac file roi
                $nv_update_config['updatelog']['is_start_move_file'] = NV_CURRENTTIME;
                $NvUpdate->set_data_log($nv_update_config['updatelog']);
            }

            $check = $NvUpdate->move_file($nv_update_config, $array['file_list']);
            if ($check === true) {
                if (!file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
                    $nv_update_config['updatelog']['step'] = 2;
                    $NvUpdate->set_data_log($nv_update_config['updatelog']);
                } else {
                    $nv_update_config['updatelog']['substep'] = 4;
                    $NvUpdate->set_data_log($nv_update_config['updatelog']);
                }

                die('OK');
            } else {
                die($check);
            }
        }

        // Khong co file nao nua thi luu lai va chuyen buoc 3
        if (empty($array['file_list']) and $nv_update_config['updatelog']['step'] < 2) {
            $nv_update_config['updatelog']['step'] = 2;
            $NvUpdate->set_data_log($nv_update_config['updatelog']);
        }

        // Di chuyen thu cong
        $array['getcomplete'] = false;
        $array['iscomplete'] = false;

        if ($nv_Request->isset_request('complete', 'get')) {
            $array['getcomplete'] = true;
            if (empty($array['file_list'])) {
                $array['iscomplete'] = true;
            }
        }

        if (!$array['getcomplete']) {
            $ftp_check_login = intval($global_config['ftp_check_login']);
            $show_ftp_error = false;
            // Luu thong tin cau hinh FTP
            if ($nv_Request->isset_request('modftp', 'post')) {
                // Cau hinh FTP
                $ftp_check_login = 1;
                $global_config['ftp_server'] = $nv_Request->get_string('ftp_server', 'post', 'localhost');
                $global_config['ftp_port'] = $nv_Request->get_int('ftp_port', 'post', 21);
                $global_config['ftp_user_name'] = $nv_Request->get_string('ftp_user_name', 'post', '');
                $global_config['ftp_user_pass'] = $nv_Request->get_string('ftp_user_pass', 'post', '');
                $global_config['ftp_path'] = $nv_Request->get_string('ftp_path', 'post', '/');

                $show_ftp_error = true;
            }

            $array['ftpdata'] = array(
                'ftp_server' => nv_htmlspecialchars($global_config['ftp_server']),
                'ftp_port' => $global_config['ftp_port'],
                'ftp_user_name' => nv_htmlspecialchars($global_config['ftp_user_name']),
                'ftp_user_pass' => nv_htmlspecialchars($global_config['ftp_user_pass']),
                'ftp_path' => nv_htmlspecialchars($global_config['ftp_path']),
                'error' => '',
                'show_ftp_error' => $show_ftp_error
            );

            // Kiem tra FTP
            $array['check_FTP'] = false;
            $array['FTP_nosupport'] = false;
            if ($sys_info['ftp_support']) {
                if ($ftp_check_login == 1) {
                    // Dang nhap FTP

                    $ftp_server = nv_unhtmlspecialchars($global_config['ftp_server']);
                    $ftp_port = intval($global_config['ftp_port']);
                    $ftp_user_name = nv_unhtmlspecialchars($global_config['ftp_user_name']);
                    $ftp_user_pass = nv_unhtmlspecialchars($global_config['ftp_user_pass']);
                    $ftp_path = nv_unhtmlspecialchars($global_config['ftp_path']);

                    $ftp = new NukeViet\Ftp\Ftp($ftp_server, $ftp_user_name, $ftp_user_pass, array( 'timeout' => 20 ), $ftp_port);

                    if (!empty($ftp->error)) {
                        $array['check_FTP'] = true;
                        $array['ftpdata']['error'] = $ftp->error;
                    } elseif ($ftp->chdir($ftp_path) === false) {
                        $array['check_FTP'] = true;
                        $array['ftpdata']['error'] = $lang_module['ftp_error_path'];
                    } else {
                        // Ghi nhat ki
                        $NvUpdate->log($nv_update_config, $lang_module['update_log_ftp'], true);

                        $array_config = array(
                            'ftp_server' => $global_config['ftp_server'],
                            'ftp_port' => $global_config['ftp_port'],
                            'ftp_user_name' => $global_config['ftp_user_name'],
                            'ftp_user_pass' => $global_config['ftp_user_pass'],
                            'ftp_path' => $global_config['ftp_path'],
                            'ftp_check_login' => 1
                        );

                        // Luu lai cau hinh FTP
                        foreach ($array_config as $config_name => $config_value) {
                            $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = :config_name AND lang = 'sys' AND module='global'");
                            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
                            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
                            $sth->execute();
                        }

                        $nv_update_config['updatelog']['ftp_check_login'] = 1;
                        $NvUpdate->set_data_log($nv_update_config['updatelog']);
                    }

                    $ftp->close();
                } else {
                    $array['check_FTP'] = true;
                }
            } else {
                $array['FTP_nosupport'] = true;
            }
        }

        // Buoc truoc
        if (empty($nv_update_config['updatelog']['data_list'])) {
            // Khong co nang cap CSDL chuyen buoc 2/2

            $array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=2';
        } else {
            // Co nang cap CSDL chuyen buoc 3/2

            $array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=3';
        }
    } elseif ($nv_update_config['substep'] == 5) {
        // Huong dan nang cap giao dien bang tay

        $array['guide'] = '';
        $array['error'] = false;
        if (file_exists(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html')) {
            $array['guide'] = file_get_contents(NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html');
        } else {
            $array['error'] = true;
        }

        // Buoc truoc
        // Neu nang cap nua tu dong
        if ($nv_update_config['update_auto_type'] == 2) {
            if (empty($nv_update_config['updatelog']['data_list'])) {
                // Khong co nang cap CSDL chuyen buoc 2/2

                $array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=2';
            } else {
                // Co nang cap CSDL chuyen buoc 3/2

                $array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=3';
            }
        } elseif (empty($nv_update_config['updatelog']['file_list'])) {
            // Khong co chuyen file

            if (empty($nv_update_config['updatelog']['data_list'])) {
                // Khong co chuyen CSDL luon

                $array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=1';
            } else {
                $array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=3';
            }
        } else {
            $array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=4';
        }

        if ($nv_update_config['updatelog']['step'] < 2) {
            $nv_update_config['updatelog']['step'] = 2;
            $NvUpdate->set_data_log($nv_update_config['updatelog']);
        }
    }

    $contents = $NvUpdate->step2($array, $nv_update_config['substep']);
} elseif ($nv_update_config['step'] == 3) {// Hoan tat nang cap
    $array = array();

    // Lay thong tin phien ban va module
    if ($nv_Request->isset_request('load', 'get')) {
        $type = $nv_Request->get_title('load', 'get', '');

        if ($type == 'ver') {
            $version = nv_geVersion(0);

            if ($version === false or is_string($version)) {
                $NvUpdate->trigger_error($lang_module['update_error_check_version_sys']);
            }

            $array['current_version'] = $global_config['version'];
            $array['newVersionCode'] = ( string )$version->version;
            $array['newVersion'] = $array['newVersionCode'] . ' - ' . ( string )$version->name;

            $array['checkversion'] = false;
            if (nv_version_compare($global_config['version'], $array['newVersionCode']) < 0) {
                $array['checkversion'] = true;
            }

            $NvUpdate->version_info($array);
        } elseif ($type == 'mod') {
            $XML_exts = nv_getExtVersion(0);

            if ($XML_exts === false or is_string($XML_exts)) {
                $NvUpdate->trigger_error($lang_module['update_error_check_version_sys']);
            }

            $XML_exts = $XML_exts->xpath('extension');

            $exts = array();
            $i = 0;
            foreach ($XML_exts as $extname => $values) {
                $exts[$i] = array(
                    'id' => ( int ) $values->id,
                    'type' => ( string ) $values->type,
                    'name' => ( string ) $values->name,
                    'version' => ( string ) $values->version,
                    'date' => ( string ) $values->date,
                    'new_version' => ( string ) $values->new_version,
                    'new_date' => ( string ) $values->new_date,
                    'author' => ( string ) $values->author,
                    'license' => ( string ) $values->license,
                    'mode' => ( string ) $values->mode,
                    'message' => ( string ) $values->message,
                    'link' => ( string ) $values->link,
                    'support' => ( string ) $values->support,
                    'updateable' => array(),
                    'origin' => (( string ) $values->origin) == 'true' ? true : false,
                );

                // Xu ly update
                $updateables = $values->xpath('updateable/upds/upd');

                if (!empty($updateables)) {
                    foreach ($updateables as $updateable) {
                        $exts[$i]['updateable'][] = array(
                            'fid' => ( string ) $updateable->upd_fid,
                            'old' => explode(',', ( string ) $updateable->upd_old),
                            'new' => ( string ) $updateable->upd_new,
                        );
                    }
                }

                $i ++;
                unset($updateables, $updateable);
            }

            $NvUpdate->module_info($exts);
        } elseif ($type == 'module') {
            $XML_exts = nv_getExtVersion(0);

            if ($XML_exts === false or is_string($XML_exts)) {
                $NvUpdate->trigger_error($lang_module['update_error_check_version_ext']);
            }

            $XML_exts = $XML_exts->xpath('extension');

            $onlineModules = array();
            foreach ($XML_exts as $extname => $values) {
                $exts_type = trim((string)$values->type);
                $exts_name = trim((string)$values->name);
                if ($exts_type == 'module') {
                    $onlineModules[$exts_name] = array(
                        'id' => (int)$values->id,
                        'type' => (string)$values->type,
                        'name' => (string)$values->name,
                        'version' => (string)$values->version,
                        'date' => (string)$values->date,
                        'new_version' => (string)$values->new_version,
                        'new_date' => (string)$values->new_date,
                        'author' => (string)$values->author,
                        'license' => (string)$values->license,
                        'mode' => (string)$values->mode,
                        'message' => (string)$values->message,
                        'link' => (string)$values->link,
                        'support' => (string)$values->support,
                        'updateable' => array(),
                        'origin' => ((string)$values->origin) == 'true' ? true : false,
                    );

                    $onlineModules[$exts_name]['pubtime'] = strtotime($onlineModules[$exts_name]['date']);

                    // Xu ly update
                    $updateables = $values->xpath('updateable/upds/upd');

                    if (!empty($updateables)) {
                        foreach ($updateables as $updateable) {
                            $onlineModules[$exts_name]['updateable'][] = array(
                                'fid' => (string)$updateable->upd_fid,
                                'old' => explode(',', (string)$updateable->upd_old),
                                'new' => (string)$updateable->upd_new,
                            );
                        }
                    }

                    unset($updateables, $updateable);
                }
            }

            $NvUpdate->module_com_info($onlineModules);
        } else {
            die('&nbsp;');
        }
    } else {
        // Xoa toan bo cache
        $nv_Cache->delAll();

        // Tao lai file cau hinh
        nv_save_file_config_global();
    }

    $contents = $NvUpdate->step3($array);
}

include NV_ROOTDIR . '/includes/header.php';
echo $NvUpdate->template($contents);
include NV_ROOTDIR . '/includes/footer.php';