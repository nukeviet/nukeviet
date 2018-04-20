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

    private $tmplang_global = array();
    private $tmplang_module = array();
    private $tmplang_block = array();

    private $lang = 'vi';
    private $defaultLang = 'vi';
    private $defaultFiles = array();
    private $defaultIsLoaded = false;
    private $isTmpLoaded = false;

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
     * @param bool $loadtmp
     * @return
     */
    public function loadModule($modfile, $admin = false, $modadmin = false, $loadtmp = false)
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
        $this->load($file, $loadtmp);
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

    /**
     * Language::load()
     *
     * @param mixed $file
     * @param bool $loadtmp
     * @return void
     */
    private function load($file, $loadtmp = false)
    {
        $lang_translator = $lang_global = $lang_module = $lang_block = array();

        if (file_exists($file)) {
            require $file;
        }

        if (!empty($lang_global)) {
            if ($loadtmp) {
                $this->isTmpLoaded = true;
                $this->tmplang_global = array_merge($this->tmplang_global, $lang_global);
            } else {
                self::$lang_global = array_merge(self::$lang_global, $lang_global);
            }
        }
        if (!empty($lang_module)) {
            if ($loadtmp) {
                $this->isTmpLoaded = true;
                $this->tmplang_module = array_merge($this->tmplang_module, $lang_module);
            } else {
                self::$lang_module = array_merge(self::$lang_module, $lang_module);
            }
        }
        if (!empty($lang_block)) {
            if ($loadtmp) {
                $this->isTmpLoaded = true;
                $this->tmplang_block = array_merge($this->tmplang_block, $lang_block);
            } else {
                self::$lang_block = array_merge(self::$lang_block, $lang_block);
            }
        }

        unset($lang_translator, $lang_global, $lang_module, $lang_block);
    }

    private function _get($funcArgs, $funcNum)
    {
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
        } elseif ($this->isTmpLoaded) {
            if (isset($this->tmplang_global[$langkey])) {
                $langvalue = $this->tmplang_global[$langkey];
            } elseif (isset($this->tmplang_module[$langkey])) {
                $langvalue = $this->tmplang_module[$langkey];
            } elseif (isset($this->tmplang_block[$langkey])) {
                $langvalue = $this->tmplang_block[$langkey];
            }
        }
        if (empty($langvalue)) {
            return $langkey;
        }
        return (empty($args) ? $langvalue : vsprintf($langvalue, $args));
    }

    /**
     * Language::get()
     *
     * @return
     */
    public function get()
    {
        return $this->_get(func_get_args(), func_num_args());
    }

    /**
     * Language::getModule()
     *
     * @return
     */
    public function getModule()
    {
        return $this->_get(func_get_args(), func_num_args());
    }

    /**
     * Language::getBlock()
     *
     * @return
     */
    public function getBlock()
    {
        return $this->_get(func_get_args(), func_num_args());
    }

    /**
     * Language::getGlobal()
     *
     * @return
     */
    public function getGlobal()
    {
        return $this->_get(func_get_args(), func_num_args());
    }
}
