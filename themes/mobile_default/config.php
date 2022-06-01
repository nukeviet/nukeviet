<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$config_theme = [];
$propety = [];

$selectedtab = $nv_Request->get_int('selectedtab', 'get,post', 0);

if ($nv_Request->isset_request('save', 'post')) {
    $css = '';

    // Css property for body
    $property['color'] = $nv_Request->get_title('body_color', 'post', '');
    $property['font_size'] = $nv_Request->get_title('body_font_size', 'post', '');
    $property['font_family'] = $nv_Request->get_title('body_font_family', 'post', '');
    $property['font_weight'] = $nv_Request->get_bool('body_font_weight', 'post', 0);
    $property['font_weight'] = $property['font_weight'] ? 'bold' : '';
    $property['font_style'] = $nv_Request->get_bool('body_font_italic', 'post', 0);
    $property['font_style'] = $property['font_style'] ? 'italic' : '';
    $property['background_color'] = $nv_Request->get_title('body_background_color', 'post', '');
    $property['background_image'] = $nv_Request->get_title('body_background_image', 'post', '');
    $property['background_repeat'] = $nv_Request->get_title('body_background_repeat', 'post', '');
    $property['background_position'] = $nv_Request->get_title('body_background_position', 'post', '');
    $property['margin'] = $nv_Request->get_title('body_margin', 'post', '');
    $property['margin_top'] = $nv_Request->get_title('body_margin_top', 'post', '');
    $property['margin_bottom'] = $nv_Request->get_title('body_margin_bottom', 'post', '');
    $property['margin_left'] = $nv_Request->get_title('body_margin_left', 'post', '');
    $property['margin_right'] = $nv_Request->get_title('body_margin_right', 'post', '');
    $property['padding'] = $nv_Request->get_title('body_padding', 'post', '');
    $property['padding_top'] = $nv_Request->get_title('body_padding_top', 'post', '');
    $property['padding_bottom'] = $nv_Request->get_title('body_padding_bottom', 'post', '');
    $property['padding_left'] = $nv_Request->get_title('body_padding_left', 'post', '');
    $property['padding_right'] = $nv_Request->get_title('body_padding_right', 'post', '');
    $property['customcss'] = $nv_Request->get_textarea('body_customcss', 'post', '');
    $config_theme['body'] = array_filter($property);
    if (!empty($config_theme['body'])) {
        $css .= nv_css_setproperties('[body]', $config_theme['body']);
    }
    $property = [];

    // Css property for link
    $property['color'] = $nv_Request->get_title('link_a_color', 'post', '');
    $property['font_weight'] = $nv_Request->get_bool('link_a_font_weight', 'post', 0);
    $property['font_weight'] = $property['font_weight'] ? 'bold' : '';
    $property['font_style'] = $nv_Request->get_bool('link_a_font_italic', 'post', 0);
    $property['font_style'] = $property['font_style'] ? 'italic' : '';
    $config_theme['a_link'] = array_filter($property);
    if (!empty($config_theme['a_link'])) {
        $css .= nv_css_setproperties('[a_link]', $config_theme['a_link']);
    }
    $property = [];

    // Css property for link (hover)
    $property['color'] = $nv_Request->get_title('link_a_hover_color', 'post', '');
    $property['font_weight'] = $nv_Request->get_bool('link_a_hover_font_weight', 'post', 0);
    $property['font_weight'] = $property['font_weight'] ? 'bold' : '';
    $property['font_style'] = $nv_Request->get_bool('link_a_hover_font_italic', 'post', 0);
    $property['font_style'] = $property['font_style'] ? 'italic' : '';
    $config_theme['a_link_hover'] = array_filter($property);
    if (!empty($config_theme['a_link_hover'])) {
        $css .= nv_css_setproperties('[a_link_hover]', $config_theme['a_link_hover']);
    }
    $property = [];

    // Css property for content
    $property['margin'] = $nv_Request->get_title('content_margin', 'post', '');
    $property['margin_top'] = $nv_Request->get_title('content_margin_top', 'post', '');
    $property['margin_bottom'] = $nv_Request->get_title('content_margin_bottom', 'post', '');
    $property['margin_left'] = $nv_Request->get_title('content_margin_left', 'post', '');
    $property['margin_right'] = $nv_Request->get_title('content_margin_right', 'post', '');
    $property['padding'] = $nv_Request->get_title('content_padding', 'post', '');
    $property['padding_top'] = $nv_Request->get_title('content_padding_top', 'post', '');
    $property['padding_bottom'] = $nv_Request->get_title('content_padding_bottom', 'post', '');
    $property['padding_left'] = $nv_Request->get_title('content_padding_left', 'post', '');
    $property['padding_right'] = $nv_Request->get_title('content_padding_right', 'post', '');
    $property['width'] = $nv_Request->get_title('content_width', 'post', '');
    $property['height'] = $nv_Request->get_title('content_height', 'post', '');
    $property['customcss'] = $nv_Request->get_textarea('content_customcss', 'post', '');
    $config_theme['content'] = array_filter($property);
    if (!empty($config_theme['content'])) {
        $css .= nv_css_setproperties('[content]', $config_theme['content']);
    }
    $property = [];

    // Css property for header
    $property['background_color'] = $nv_Request->get_title('header_background_color', 'post', '');
    $property['background_image'] = $nv_Request->get_title('header_background_image', 'post', '');
    $property['background_repeat'] = $nv_Request->get_title('header_background_repeat', 'post', '');
    $property['background_position'] = $nv_Request->get_title('header_background_position', 'post', '');
    $property['margin'] = $nv_Request->get_title('header_margin', 'post', '');
    $property['margin_top'] = $nv_Request->get_title('header_margin_top', 'post', '');
    $property['margin_bottom'] = $nv_Request->get_title('header_margin_bottom', 'post', '');
    $property['margin_left'] = $nv_Request->get_title('header_margin_left', 'post', '');
    $property['margin_right'] = $nv_Request->get_title('header_margin_right', 'post', '');
    $property['padding'] = $nv_Request->get_title('header_padding', 'post', '');
    $property['padding_top'] = $nv_Request->get_title('header_padding_top', 'post', '');
    $property['padding_bottom'] = $nv_Request->get_title('header_padding_bottom', 'post', '');
    $property['padding_left'] = $nv_Request->get_title('header_padding_left', 'post', '');
    $property['padding_right'] = $nv_Request->get_title('header_padding_right', 'post', '');
    $property['width'] = $nv_Request->get_title('header_width', 'post', '');
    $property['height'] = $nv_Request->get_title('header_height', 'post', '');
    $property['customcss'] = $nv_Request->get_textarea('header_customcss', 'post', '');
    $config_theme['header'] = array_filter($property);
    if (!empty($config_theme['header'])) {
        $css .= nv_css_setproperties('[header]', $config_theme['header']);
    }
    $property = [];

    // Css property for footer
    $property['background_color'] = $nv_Request->get_title('footer_background_color', 'post', '');
    $property['background_image'] = $nv_Request->get_title('footer_background_image', 'post', '');
    $property['background_repeat'] = $nv_Request->get_title('footer_background_repeat', 'post', '');
    $property['background_position'] = $nv_Request->get_title('footer_background_position', 'post', '');
    $property['margin'] = $nv_Request->get_title('footer_margin', 'post', '');
    $property['margin_top'] = $nv_Request->get_title('footer_margin_top', 'post', '');
    $property['margin_bottom'] = $nv_Request->get_title('footer_margin_bottom', 'post', '');
    $property['margin_left'] = $nv_Request->get_title('footer_margin_left', 'post', '');
    $property['margin_right'] = $nv_Request->get_title('footer_margin_right', 'post', '');
    $property['padding'] = $nv_Request->get_title('footer_padding', 'post', '');
    $property['padding_top'] = $nv_Request->get_title('footer_padding_top', 'post', '');
    $property['padding_bottom'] = $nv_Request->get_title('footer_padding_bottom', 'post', '');
    $property['padding_left'] = $nv_Request->get_title('footer_padding_left', 'post', '');
    $property['padding_right'] = $nv_Request->get_title('footer_padding_right', 'post', '');
    $property['width'] = $nv_Request->get_title('footer_width', 'post', '');
    $property['height'] = $nv_Request->get_title('footer_height', 'post', '');
    $property['customcss'] = $nv_Request->get_textarea('footer_customcss', 'post', '');
    $config_theme['footer'] = array_filter($property);
    if (!empty($config_theme['footer'])) {
        $css .= nv_css_setproperties('[footer]', $config_theme['footer']);
    }
    $property = [];

    // Css property for footer
    $property['background_color'] = $nv_Request->get_title('block_background_color', 'post', '');
    $property['background_image'] = $nv_Request->get_title('block_background_image', 'post', '');
    $property['background_repeat'] = $nv_Request->get_title('block_background_repeat', 'post', '');
    $property['background_position'] = $nv_Request->get_title('block_background_position', 'post', '');
    $property['margin'] = $nv_Request->get_title('block_margin', 'post', '');
    $property['margin_top'] = $nv_Request->get_title('block_margin_top', 'post', '');
    $property['margin_bottom'] = $nv_Request->get_title('block_margin_bottom', 'post', '');
    $property['margin_left'] = $nv_Request->get_title('block_margin_left', 'post', '');
    $property['margin_right'] = $nv_Request->get_title('block_margin_right', 'post', '');
    $property['padding'] = $nv_Request->get_title('block_padding', 'post', '');
    $property['padding_top'] = $nv_Request->get_title('block_padding_top', 'post', '');
    $property['padding_bottom'] = $nv_Request->get_title('block_padding_bottom', 'post', '');
    $property['padding_left'] = $nv_Request->get_title('block_padding_left', 'post', '');
    $property['padding_right'] = $nv_Request->get_title('block_padding_right', 'post', '');
    $property['border_color'] = $nv_Request->get_title('block_border_color', 'post', '');
    $property['border_style'] = $nv_Request->get_title('block_border_style', 'post', '');
    $property['border_width'] = $nv_Request->get_title('block_border_width', 'post', '');
    $property['border_radius'] = $nv_Request->get_title('block_border_radius', 'post', '');
    $property['customcss'] = $nv_Request->get_textarea('block_customcss', 'post', '');
    $config_theme['block'] = array_filter($property);
    if (!empty($config_theme['block'])) {
        $css .= nv_css_setproperties('[block]', $config_theme['block']);
    }
    $property = [];

    $property['background_color'] = $nv_Request->get_title('block_heading_background_color', 'post', '');
    $property['background_image'] = $nv_Request->get_title('block_heading_background_image', 'post', '');
    $property['background_repeat'] = $nv_Request->get_title('block_heading_background_repeat', 'post', '');
    $property['background_position'] = $nv_Request->get_title('block_heading_background_position', 'post', '');
    $config_theme['block_heading'] = array_filter($property);
    if (!empty($config_theme['block_heading'])) {
        $css .= nv_css_setproperties('[block_heading]', $config_theme['block_heading']);
    }
    $property = [];

    $property['family'] = $nv_Request->get_title('gfont_family', 'post', '');
    $property['styles'] = $nv_Request->get_title('gfont_styles', 'post', '');
    $property['subset'] = $nv_Request->get_title('gfont_subset', 'post', '');
    empty($property['family']) and ($property['styles'] = $property['subset'] = '');
    $config_theme['gfont'] = array_filter($property);

    // General css
    if (($generalcss = nv_unhtmlspecialchars($nv_Request->get_textarea('generalcss', 'post', ''))) != '') {
        $config_theme['generalcss'] = $generalcss;
        $css .= nv_css_setproperties('[generalcss]', $config_theme['generalcss']);
    }

    $config_value = array_filter($config_theme);
    !empty($css) and $config_value['css_content'] = $css;

    $config_value = serialize($config_value);

    if (isset($module_config['themes'][$selectthemes])) {
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
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }

    $nv_Cache->delMod('settings');

    if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/css/' . $selectthemes . '.' . NV_LANG_DATA . '.' . $global_config['idsite'] . '.css')) {
        nv_deletefile(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/css/' . $selectthemes . '.' . NV_LANG_DATA . '.' . $global_config['idsite'] . '.css');
    }

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&selectthemes=' . $selectthemes . '&selectedtab=' . $selectedtab . '&rand=' . nv_genpass());
} else {
    $default_config_theme = '';
    require NV_ROOTDIR . '/themes/' . $selectthemes . '/config_default.php';
    if (isset($module_config['themes'][$selectthemes])) {
        $config_theme = unserialize($module_config['themes'][$selectthemes]);
        $config_theme = array_replace_recursive($default_config_theme, $config_theme);
    } else {
        $config_theme = $default_config_theme;
    }
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $selectthemes . '/system');
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);
$xtpl->assign('SELECTTHEMES', $selectthemes);
$xtpl->assign('UPLOADS_DIR', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('SELECTEDTAB', $selectedtab);

for ($i = 0; $i <= 6; ++$i) {
    $xtpl->assign('TAB' . $i . '_ACTIVE', $i == $selectedtab ? ' active' : '');
}

// List style border
$boder_style = [
    'none' => 'None',
    'solid' => 'Solid',
    'dotted' => 'Dotted',
    'dashed' => 'Dashed',
    'double' => 'Double',
    'groove' => 'Groove',
    'ridge' => 'Ridge',
    'inset' => 'Inset',
    'outset' => 'Outset',
    'hidden' => 'Hidden'
];

if (isset($module_config['themes'][$selectthemes])) {
    foreach ($boder_style as $key => $value) {
        $xtpl->assign(
            'BLOCK_BORDER_STYLE',
            [
                'key' => $key,
                'value' => $value,
                'selected' => (isset($config_theme['block']['border_style']) and $config_theme['block']['border_style'] == $key) ? ' selected="selected"' : '']
        );
        $xtpl->parse('main.block_border_style');
    }

    $config_theme['body']['font_weight'] = !empty($config_theme['body']['font_weight']) ? ' checked="checked"' : '';
    $config_theme['body']['font_style'] = !empty($config_theme['body']['font_style']) ? ' checked="checked"' : '';
    $config_theme['a_link']['font_weight'] = !empty($config_theme['a_link']['font_weight']) ? ' checked="checked"' : '';
    $config_theme['a_link']['font_style'] = !empty($config_theme['a_link']['font_style']) ? ' checked="checked"' : '';
    $config_theme['a_link_hover']['font_weight'] = !empty($config_theme['a_link_hover']['font_weight']) ? ' checked="checked"' : '';
    $config_theme['a_link_hover']['font_style'] = !empty($config_theme['a_link_hover']['font_style']) ? ' checked="checked"' : '';

    $xtpl->assign('CONFIG_THEME_BODY', $config_theme['body']);
    $xtpl->assign('CONFIG_THEME_A_LINK', $config_theme['a_link']);
    $xtpl->assign('CONFIG_THEME_A_LINK_HOVER', $config_theme['a_link_hover']);
    $xtpl->assign('CONFIG_THEME_CONTENT', $config_theme['content']);
    $xtpl->assign('CONFIG_THEME_HEADER', $config_theme['header']);
    $xtpl->assign('CONFIG_THEME_FOOTER', $config_theme['footer']);
    $xtpl->assign('CONFIG_THEME_BLOCK', $config_theme['block']);
    $xtpl->assign('CONFIG_THEME_BLOCK_HEADING', $config_theme['block_heading']);
    $xtpl->assign('CONFIG_THEME_GENERCSS', $config_theme['generalcss']);
    $xtpl->assign('CONFIG_THEME_GFONT', $config_theme['gfont']);
}

$xtpl->parse('main');
$contents = $xtpl->text('main');
