<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 12 Sep 2013 04:07:53 GMT
 */

namespace NukeViet\Core;

class Language
{
    public static $lang_global = array();
    public static $lang_module = array();
    public static $lang_block = array();

    private $lang = 'vi';
    private $defaultLang = 'vi';
    private $defaultFiles = array();
    private $defaultIsLoaded = false;

    public function __construct()
    {
        $this->lang = NV_LANG_INTERFACE;
    }

    /**
     * Language::loadModule()
     *
     * @param mixed $modfile
     * @param bool $admin
     * @param bool $modadmin
     * @return
     */
    public function loadModule($modfile, $admin = false, $modadmin = false)
    {
        if ($admin and !defined('NV_ADMIN')) {
            return false;
        }
        if ($modadmin and $admin) {
            $file = NV_ROOTDIR . '/includes/language/' . $this->lang . '/admin_' . $modfile . '.php';
            if ($this->lang != $this->defaultLang) {
                $this->defaultFiles[] = NV_ROOTDIR . '/includes/language/' . $this->defaultLang . '/admin_' . $modfile . '.php';
            }
        } else {
            $file = NV_ROOTDIR . '/modules/' . $modfile . '/language/' . ($admin ? 'admin_' : '') . $this->lang . '.php';
            if ($this->lang != $this->defaultLang) {
                $this->defaultFiles[] = NV_ROOTDIR . '/modules/' . $modfile . '/language/' . ($admin ? 'admin_' : '') . $this->defaultLang . '.php';
            }
        }
        $this->load($file);
    }

    public function loadBlock($admin = false)
    {
        $lang_translator = $lang_global = array($admin = false);
    }

    /**
     * Language::loadTheme()
     *
     * @param mixed $theme
     * @param bool $admin
     * @return
     */
    public function loadTheme($theme, $admin = false)
    {
        if ($admin and !defined('NV_ADMIN')) {
            return false;
        }
        $file = NV_ROOTDIR . '/themes/' . $theme . '/language/' . ($admin ? 'admin_' : '') . $this->lang . '.php';
        if ($this->lang != $this->defaultLang) {
            $this->defaultFiles[] = NV_ROOTDIR . '/themes/' . $theme . '/language/' . ($admin ? 'admin_' : '') . $this->defaultLang . '.php';
        }
        $this->load($file);
    }

    /**
     * Language::loadGlobal()
     *
     * @param bool $admin
     * @return void
     */
    public function loadGlobal($admin = false)
    {
        if ($admin and !defined('NV_ADMIN')) {
            return false;
        }
        $fileName = ($admin ? 'admin_' : '') . 'global.php';
        $file = NV_ROOTDIR . '/includes/language/' . $this->lang . '/' . $fileName;
        if ($this->lang != $this->defaultLang) {
            $this->defaultFiles[] = NV_ROOTDIR . '/includes/language/' . $this->defaultLang . '/' . $fileName;
        }
        $this->load($file);
    }

    private function load($file)
    {
        $lang_translator = $lang_global = $lang_module = $lang_block = array();

        if (file_exists($file)) {
            require $file;
        }

        if (!empty($lang_global)) {
            self::$lang_global = array_merge(self::$lang_global, $lang_global);
        }
        if (!empty($lang_module)) {
            self::$lang_module = array_merge(self::$lang_module, $lang_module);
        }
        if (!empty($lang_block)) {
            self::$lang_block = array_merge(self::$lang_block, $lang_block);
        }

        unset($lang_translator, $lang_global, $lang_module, $lang_block);
    }

    /**
     * Language::get()
     *
     * @return
     */
    public function get()
    {
        $funcArgs = func_get_args();
        $funcNum = func_num_args();
        if ($funcNum < 1) {
            return '';
        }

        $langkey = $funcArgs[0];
        unset($funcArgs[0]);
        $args = $funcArgs;

        $langvalue = '';
        if (isset(self::$lang_global[$langkey])) {
            $langvalue = self::$lang_global[$langkey];
        } elseif (isset(self::$lang_module[$langkey])) {
            $langvalue = self::$lang_module[$langkey];
        } elseif (isset(self::$lang_block[$langkey])) {
            $langvalue = self::$lang_block[$langkey];
        }
        if (empty($langvalue)) {
            return $langkey;
        }
        return (empty($args) ? $langvalue : vsprintf($langvalue, $args));
    }
}
