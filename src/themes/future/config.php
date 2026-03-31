<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

/**
 * Tệp xử lý thiết lập giao diện trong quản trị.
 * Xóa nếu bạn không có nhu cầu thiết lập các thông số trong giao diện của mình.
 */

$border_styles = ['none', 'solid', 'dashed', 'dotted', 'double', 'groove', 'ridge', 'inset', 'outset'];
$text_decorations = ['none', 'underline', 'overline', 'line-through'];

require NV_ROOTDIR . '/themes/' . $selectthemes . '/config_default.php';
$variables = $default_config_theme['variables'];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $selectthemes . '/system');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('TEMPLATE', $selectthemes);
$tpl->assign('BORDER_STYLES', $border_styles);
$tpl->assign('TEXT_DECORATIONS', $text_decorations);

$tpl->assign('VARIABLES', $variables);

$tabs = ['color', 'variables', 'css', 'gfonts'];
$tab = $nv_Request->get_title('tab', 'post,get', $tabs[0]);
if (!in_array($tab, $tabs, true)) {
    $tab = $tabs[0];
}

$error = $config_theme = $warning = [];
$error_tab = '';
$clean_tab = $success = 0;

// Lưu các thiết lập
if ($nv_Request->get_title('checkss', 'post', '') === NV_CHECK_SESSION) {
    // Chế độ màu
    $value = $nv_Request->get_title('color_mode', 'post', '');
    if (in_array($value, ['light', 'dark', 'auto'], true)) {
        $config_theme['color']['mode'] = $value;
    }

    // Giao diện sáng
    $value = $nv_Request->get_title('light_theme', 'post', '');
    if (in_array($value, ['light'], true)) {
        $config_theme['color']['light'] = $value;
    }

    $clean = $nv_Request->get_absint('clean_variables', 'post', 0);
    if ($clean != 1) {
        // Thiết lập các biến
        foreach ($variables as $var_group => $vars) {
            foreach ($vars as $var => $types) {
                if ($types['type'] == 'color') {
                    $value = $nv_Request->get_title($var_group . '_' . $var, 'post', '');
                    $rgb = hex2rgb($value);
                    if (!empty($value)) {
                        if (empty($rgb)) {
                            $error[] = $nv_Lang->getModule('tconf_error_color', $nv_Lang->getModule('tconf_var_' . $var_group), $nv_Lang->getModule('tconf_vari_' . $var));
                        }
                        $config_theme['variables'][$var_group][$var] = $value;
                    }
                } elseif ($types['type'] == 'text') {
                    // Text thuần, cần chuyển ' (&#039;) và " (&quot;) bị mã hóa trước khi đưa ra CSS
                    $value = nv_substr($nv_Request->get_title($var_group . '_' . $var, 'post', ''), 0, 500);
                    if (!empty($value)) {
                        $config_theme['variables'][$var_group][$var] = $value;
                    }
                } elseif ($types['type'] == 'number') {
                    // Số
                    $value = $nv_Request->get_title($var_group . '_' . $var, 'post', '');
                    $number = floatval($value);
                    if (strlen($value) > 0 and $value == $number) {
                        $config_theme['variables'][$var_group][$var] = $value;
                    }
                } elseif ($types['type'] == 'size') {
                    // Kích thước
                    $value = $nv_Request->get_title($var_group . '_' . $var, 'post', '');
                    $unit = $nv_Request->get_title('unit_' . $var_group . '_' . $var, 'post', '');
                    if (!in_array($unit, ['px', 'rem'], true)) {
                        $unit = 'px';
                    }
                    $number = floatval($value);
                    if (strlen($value) > 0 and $value == $number) {
                        $config_theme['variables'][$var_group][$var] = $value . $unit;
                    }
                } elseif ($types['type'] == 'font_weight') {
                    // Độ đậm của chữ
                    $value = $nv_Request->get_absint($var_group . '_' . $var, 'post', 0);
                    if ($value >= 100 and $value <= 900 and $value % 100 == 0) {
                        $config_theme['variables'][$var_group][$var] = $value;
                    }
                } elseif ($types['type'] == 'border_style') {
                    // Kiểu viền
                    $value = $nv_Request->get_title($var_group . '_' . $var, 'post', '');
                    if (in_array($value, $border_styles, true)) {
                        $config_theme['variables'][$var_group][$var] = $value;
                    }
                } elseif ($types['type'] == 'text_decoration') {
                    // Kiểu viền
                    $value = $nv_Request->get_title($var_group . '_' . $var, 'post', '');
                    if (in_array($value, $text_decorations, true)) {
                        $config_theme['variables'][$var_group][$var] = $value;
                    }
                }
            }
        }
    } else {
        $clean_tab = 1;
    }

    // Thiết lập CSS
    $clean = $nv_Request->get_absint('clean_css', 'post', 0);
    if ($clean != 1) {
        $css = nv_unhtmlspecialchars(trim($nv_Request->get_textarea('css', 'post', '')));
        if (!empty($css) and !is_safe_css($css)) {
            $error[] = $nv_Lang->getModule('tconf_error_css');
            empty($error_tab) && $error_tab = 'css';
        }
        !empty($css) && $config_theme['css'] = $css;
    } else {
        $clean_tab = 1;
    }

    // Google fonts
    $clean = $nv_Request->get_absint('clean_gfonts', 'post', 0);
    if ($clean != 1) {
        $family = $nv_Request->get_title('gfonts_family', 'post', '');
        $styles = [];

        for ($i = 100; $i <= 900; $i += 100) {
            $normal = (int) $nv_Request->get_bool('gfonts_n' . $i, 'post', false);
            $italic = (int) $nv_Request->get_bool('gfonts_i' . $i, 'post', false);
            if ($normal or $italic) {
                $normal && $styles[$i]['n'] = true;
                $italic && $styles[$i]['i'] = true;
            }
        }
        !empty($styles) && $config_theme['gfont']['styles'] = $styles;

        if (!empty($family)) {
            $config_theme['gfont']['family'] = $family;
            if (empty($styles)) {
                $error[] = $nv_Lang->getModule('tconf_error_gfonts_styles');
                empty($error_tab) && $error_tab = 'gfonts';
            }
        }
    } else {
        $clean_tab = 1;
    }

    if (empty($error)) {
        $success = 1;
        $config_value = json_encode($config_theme, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (isset($module_config['themes'], $module_config['themes'][$selectthemes])) {
            $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = :config_name AND lang = '" . NV_LANG_DATA . "' AND module='themes'");
        } else {
            $sth = $db->prepare('INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . NV_LANG_DATA . "', 'themes', :config_name, :config_value)");
        }

        $sth->bindParam(':config_name', $selectthemes, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR, strlen($config_value));
        $sth->execute();

        if (isset($global_config['sitetimestamp'])) {
            $sitetimestamp = (int) ($global_config['sitetimestamp']) + 1;
            $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $sitetimestamp . "' WHERE lang = 'sys' AND module = 'site' AND config_name = 'sitetimestamp'");
        } else {
            try {
                $db->query('INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'sitetimestamp', '1')");
            } catch (Throwable $e) {
                trigger_error(print_r($e, true));
            }
        }

        // Xây dựng tệp CSS trước
        $css_contents = [];
        $prefix = 'bs-';
        if (!empty($config_theme['variables'])) {
            foreach ($config_theme['variables'] as $var_group => $vars) {
                foreach ($vars as $var => $value) {
                    $types = $default_config_theme['variables'][$var_group][$var];
                    if ($var_group == 'colors') {
                        // Biến màu sắc
                        $css_contents[] = '--' . $prefix . $var . ': ' . $value;
                        if (!empty($types['rgb'])) {
                            $css_contents[] = '--' . $prefix . $var . '-rgb: ' . hex2rgb($value);
                        }
                    } else {
                        // Các biến còn lại
                        $value = str_replace(['&quot;', '&#039;'], ['"', "'"], $value);
                        $var_name = '--' . $prefix . str_replace('_', '-', $var_group) . '-' . str_replace('_', '-', $var);
                        $css_contents[] = $var_name . ': ' . $value;
                        if (!empty($types['rgb'])) {
                            $css_contents[] = $var_name . '-rgb: ' . hex2rgb($value);
                        }
                    }
                }
            }
        }
        if (!empty($css_contents)) {
            $css_contents = [('body{' . implode('; ', $css_contents) . ';}')];
        }

        // Tải tệp font trước
        $gfonts = new NukeViet\Client\Gfonts2($selectthemes);
        if (isset($config_theme['gfont']) and !empty($config_theme['gfont']['family']) and !empty($config_theme['gfont']['styles'])) {
            $status = $gfonts->save($config_theme['gfont']);
            if ($status > 0) {
                $warning[] = $nv_Lang->getModule('tconf_gfonts_error', $status);
            } else {
                $css_contents[] = ('body{--' . $prefix . 'body-font-family: "' . $config_theme['gfont']['family'] . '", sans-serif;}');
            }
        } else {
            $gfonts->destroyAll();
        }

        if (!empty($config_theme['css'])) {
            $css_contents[] = $config_theme['css'];
        }
        $css_file = NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/css/' . $selectthemes . '.' . NV_LANG_DATA . '.' . $global_config['idsite'] . '.css';
        if (!empty($css_contents)) {
            $status = file_put_contents($css_file, implode("\n", $css_contents) . "\n", LOCK_EX);
            if ($status <= 0) {
                $warning[] = $nv_Lang->getModule('tconf_error_savecss', NV_ASSETS_DIR . '/css');
            }
        } elseif (file_exists($css_file)) {
            nv_deletefile($css_file);
        }

        $nv_Cache->delMod('settings');
    }
} elseif (isset($module_config['themes'][$selectthemes])) {
    $config_theme = json_decode($module_config['themes'][$selectthemes], true);
    if (!is_array($config_theme)) {
        $config_theme = [];
    }
}

$tpl->assign('TAB', $error_tab ?: $tab);
$tpl->assign('ERROR', $error);
$tpl->assign('CLEAN_TAB', $clean_tab);
$tpl->assign('SUCCESS', $success);
$tpl->assign('WARNING', $warning);

$config_theme['css'] = nv_htmlspecialchars($config_theme['css'] ?? '');
$tpl->assign('CONFIG', $config_theme);

$contents = $tpl->fetch('config.tpl');

/**
 * @param string $hex
 * @return boolean|string
 */
function hex2rgb(string $hex)
{
    $hex = ltrim($hex, '#');
    if (!preg_match('/^([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/', $hex)) {
        return false;
    }
    if (strlen($hex) == 3) {
        $hex = str_repeat($hex[0], 2) . str_repeat($hex[1], 2) . str_repeat($hex[2], 2);
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    return implode(', ', [$r, $g, $b]);
}

/**
 * @param string $css
 * @return boolean
 */
function is_safe_css($css)
{
    $patterns = [
        '/expression\s*\(/i', // Phát hiện expression()
        '/url\s*\(\s*["\']?javascript\s*:/i', // Phát hiện url("javascript:")
        '/url\s*\(\s*data:/i', // Phát hiện url("data:")
        '/behavior\s*:/i', // Phát hiện behavior:
        '/@import\s+["\']?(http|https|ftp):/i', // Phát hiện @import với URL
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $css)) {
            return false;
        }
    }

    return true;
}
