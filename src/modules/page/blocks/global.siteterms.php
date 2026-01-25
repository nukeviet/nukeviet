<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('site_terms')) {
    function site_terms_config($module, $data_block)
    {
        global $nv_Cache, $global_config, $site_mods, $db, $nv_Lang;

        $db->sqlreset()->select('id, title, alias, description')->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'])->where('status = 1')->order('weight ASC');
        $list = $nv_Cache->db($db->sql(), 'id', $module);

        $term_names = !empty($data_block['term_names']) ? array_map('trim', explode('|', $data_block['term_names'])) : [''];
        $term_queries = !empty($data_block['term_queries']) ? array_map('trim', explode('|', $data_block['term_queries'])) : [''];

        $html = '<div class="row mb-2">';
        $html .= '<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('links') . ':</label>';
        $html .= '<div class="col-sm-9 list">';
        foreach ($term_names as $key => $term_name) {
            empty($term_queries[$key]) && $term_queries[$key] = '';
            $html .= '<div class="input-group mb-2 item">';
            if (!empty($list)) {
                $html .= '<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>';
                $html .= '<ul class="dropdown-menu">';
                foreach ($list as $item) {
                    $html .= '<li><a class="dropdown-item" href="#" data-url="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $item['alias'] . $global_config['rewrite_exturl'] . '" data-toggle="sample_term">' . nv_clean60($item['title'], 60) . '</a></li>';
                }
                $html .= '</ul>';
            }
            $html .= '<input type="text" name="term_names[]" value="' . $term_name . '" placeholder="' . $nv_Lang->getModule('term_name') . '" class="form-control">';
            $html .= '<input type="text" name="term_queries[]" value="' . $term_queries[$key] . '" placeholder="' . $nv_Lang->getModule('term_query') . '" class="form-control">';
            $html .= '<button class="btn btn-default" type="button" data-toggle="del_term">x</button><button class="btn btn-default" type="button" data-toggle="add_term">+</button>';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= "<script>
$(function() {
    $('body').on('click', '[data-toggle=del_term]', function() {
        var item = $(this).parents('.item'),
            list = $(this).parents('.list');
        if ($('.item', list).length > 1) {
            item.remove()
        } else {
            $('input', item).val('')
        }
    });

    $('body').on('click', '[data-toggle=add_term]', function() {
        var item = $(this).parents('.item'),
            newitem = item.clone();
        $('input', newitem).val('');
        item.after(newitem)
    });

    $('body').on('click', '[data-toggle=sample_term]', function() {
        var item = $(this).parents('.item'),
            name = $(this).text(),
            url = $(this).data('url');
        if ($('[name^=term_names]', item).val() == '') {
            $('[name^=term_names]', item).val(name)
        }
        $('[name^=term_queries]', item).val(url)
    })
})
</script>";

        return $html;
    }

    function site_terms_submit($module)
    {
        global $nv_Request;

        $return = [];
        $return['error'] = [];
        $return['config']['term_names'] = $nv_Request->get_typed_array('term_names', 'post', 'title', []);
        $return['config']['term_queries'] = $nv_Request->get_typed_array('term_queries', 'post', 'title', []);
        $return['config']['term_names'] = !empty($return['config']['term_names']) ? implode('|', $return['config']['term_names']) : '';
        $return['config']['term_queries'] = !empty($return['config']['term_queries']) ? implode('|', $return['config']['term_queries']) : '';

        return $return;
    }

    function site_terms($block_config)
    {
        global $global_config;

        $term_names = !empty($block_config['term_names']) ? array_map('trim', explode('|', $block_config['term_names'])) : [''];
        $term_queries = !empty($block_config['term_queries']) ? array_map('trim', explode('|', $block_config['term_queries'])) : [''];

        if (!empty($term_names)) {
            $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/page/block.siteterms.tpl');
            $xtpl = new XTemplate('block.siteterms.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/page');

            foreach ($term_names as $key => $name) {
                empty($term_queries[$key]) && $term_queries[$key] = '';
                $row = [
                    'name' => $name,
                    'url' => $term_queries[$key]
                ];
                $xtpl->assign('ROW', $row);
                $xtpl->parse('main.loop');
            }

            $xtpl->parse('main');

            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $content = site_terms($block_config);
}
