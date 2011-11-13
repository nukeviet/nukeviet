<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 5/12/2010, 1:34
 */

if (!defined('NV_IS_FILE_SETTINGS'))
    die('Stop!!!');

$xtpl = new XTemplate("robots.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$cache_file = NV_ROOTDIR . "/" . NV_DATADIR . "/robots.php";

if ($nv_Request->isset_request('submit', 'post'))
{
    $robots_data = $nv_Request->get_array('filename', 'post');

    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if ( ! defined( 'NV_MAINFILE' ) )\n";
    $content_config .= "{\n";
    $content_config .= "    die( 'Stop!!!' );\n";
    $content_config .= "}\n\n";
    $content_config .= "\$cache='" . serialize($robots_data) . "';\n";
    $content_config .= "\n\n";
    $content_config .= "?>";

    file_put_contents($cache_file, $content_config, LOCK_EX);

    $check_rewrite_file = nv_check_rewrite_file();
    if (!$check_rewrite_file)
    {
        if (is_writable(NV_ROOTDIR . "/robots.txt"))
        {
            $contents = array();
            $contents[] = "User-agent: *";

            foreach ($robots_data as $key => $value)
            {
                if ($value == 0)
                {
                    $contents[] = "Disallow: " . $key;
                }
            }

            if ($global_config['is_url_rewrite'])
            {
                $contents[] = "Sitemap: " . $global_config['site_url'] . "/index.php/SitemapIndex" . $global_config['rewrite_endurl'];
            }
            else
            {
                $contents[] = "Sitemap: " . $global_config['site_url'] . "/index.php?" . NV_NAME_VARIABLE . "=SitemapIndex";
            }
            $contents = implode("\n", $contents);
            file_put_contents(NV_ROOTDIR . "/robots.txt", $contents, LOCK_EX);
            unset($contents);
        }
        else
        {
            trigger_error($lang_module['robots_error_writable'], E_USER_WARNING);
        }
    }
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    exit();
}

$robots_data = array();
if (file_exists($cache_file))
{
    include $cache_file;
    $robots_data = unserialize($cache);
}
else
{
    $robots_data['/' . NV_CACHEDIR . '/'] = 0;
    $robots_data['/' . NV_DATADIR . '/'] = 0;
    $robots_data['/' . NV_EDITORSDIR . '/'] = 0;
    $robots_data['/includes/'] = 0;
    $robots_data['/install/'] = 0;
    $robots_data['/language/'] = 0;
    $robots_data['/' . NV_LOGS_DIR . '/'] = 0;
    $robots_data['/mainfile.php'] = 0;
    $robots_data['/modules/'] = 0;
    $robots_data['/robots.php'] = 0;
    $robots_data['/' . NV_SESSION_SAVE_PATH . '/'] = 0;
    $robots_data['/tmp/'] = 0;
    $robots_data['/web.config'] = 0;
}

$files = scandir(NV_ROOTDIR, true);
sort($files);
$contents = array();
$contents[] = "User-agent: *";
$number = 0;
foreach ($files as $file)
{
    if (!preg_match("/^\.(.*)$/", $file))
    {
        if (is_dir(NV_ROOTDIR . '/' . $file))
            $file = "/" . $file . "/";
        else
            $file = "/" . $file;

        $data = array('number' => ++$number, 'filename' => $file, 'class' => ($number % 2 == 0) ? ' class="second"' : '');
        $type = isset($robots_data[$file]) ? $robots_data[$file] : 1;
        for ($i = 0; $i < 2; $i++)
        {
            $option = array('value' => $i, 'title' => $lang_module['robots_type_' . $i], 'selected' => ($type == $i) ? ' selected="selected"' : '');
            $xtpl->assign('OPTION', $option);
            $xtpl->parse('main.loop.option');

        }
        $xtpl->assign('DATA', $data);
        $xtpl->parse('main.loop');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['robots'];

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>