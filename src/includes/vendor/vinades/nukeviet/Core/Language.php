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
    private $isTmpLoaded = false;

    const TYPE_LANG_ALL = 0;
    const TYPE_LANG_GLOBAL = 1;
    const TYPE_LANG_MODULE = 2;
    const TYPE_LANG_BLOCK = 3;

    /**
     * Language::__construct()
     *
     * @return void
     */
    public function __construct()
    {
        $this->lang = NV_LANG_INTERFACE;
    }

    /**
     * Language::setLang()
     *
     * @param mixed $lang
     * @return void
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        self::$lang_block = array();
        self::$lang_module = array();
        self::$lang_global = array();
        $this->tmplang_global = array();
        $this->tmplang_module = array();
        $this->tmplang_block = array();
    }

    /**
     * Language::changeLang()
     *
     * @param string $lang
     * @return void
     */
    public function changeLang($lang = '')
    {
        if (!empty($lang)) {
            $this->lang = $lang;
        }
        $this->tmplang_global = array();
        $this->tmplang_module = array();
        $this->tmplang_block = array();
        $this->isTmpLoaded = false;
    }

    /**
     * Language::loadModule()
     *
     * @param mixed $modfile
     * @param bool $modadmin
     * @param bool $loadtmp
     * @return
     */
    public function loadModule($modfile, $modadmin = false, $loadtmp = false)
    {
        if ($modadmin and !defined('NV_ADMIN')) {
            return false;
        }
        if ($modadmin) {
            if ($this->lang != $this->defaultLang) {
                $file = NV_ROOTDIR . '/includes/language/' . $this->defaultLang . '/admin_' . $modfile . '.php';
                $this->load($file, $loadtmp);
            }
            $file = NV_ROOTDIR . '/includes/language/' . $this->lang . '/admin_' . $modfile . '.php';
        } else {
            if ($this->lang != $this->defaultLang) {
                $file = NV_ROOTDIR . '/modules/' . $modfile . '/language/' . $this->defaultLang . '.php';
                $this->load($file, $loadtmp);
            }
            $file = NV_ROOTDIR . '/modules/' . $modfile . '/language/' . $this->lang . '.php';
        }
        $this->load($file, $loadtmp);
    }

    /**
     * Đọc một file ngôn ngữ bất kỳ
     *
     * @param string $file
     * @param boolean $loadtmp
     */
    public function loadFile($file, $loadtmp = false)
    {
        $this->load($file, $loadtmp);
    }

    /**
     * Language::loadInstall()
     *
     * @param mixed $lang
     * @return
     */
    public function loadInstall($lang)
    {
        if (!defined('NV_ADMIN')) {
            return false;
        }
        if ($lang != $this->defaultLang) {
            $file = NV_ROOTDIR . '/includes/language/' . $this->defaultLang . '/install.php';
            $this->load($file);
        }
        $file = NV_ROOTDIR . '/includes/language/' . $lang . '/install.php';
        $this->load($file);
    }

    /**
     * Language::loadTheme()
     *
     * @param mixed $theme
     * @param boolean $loadtmp
     * @return
     */
    public function loadTheme($theme, $loadtmp = false)
    {
        if ($this->lang != $this->defaultLang) {
            $file = NV_ROOTDIR . '/themes/' . $theme . '/language/' . $this->defaultLang . '.php';
            $this->load($file, $loadtmp);
        }
        $file = NV_ROOTDIR . '/themes/' . $theme . '/language/' . $this->lang . '.php';
        $this->load($file, $loadtmp);
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
        if ($this->lang != $this->defaultLang) {
            $file = NV_ROOTDIR . '/includes/language/' . $this->defaultLang . '/' . $fileName;
            $this->load($file);
        }
        $file = NV_ROOTDIR . '/includes/language/' . $this->lang . '/' . $fileName;
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

    /**
     * Language::_get()
     *
     * @param mixed $funcArgs
     * @param mixed $funcNum
     * @param mixed $type
     * @return
     */
    private function _get($funcArgs, $funcNum, $type)
    {
        if ($funcNum < 1) {
            return '';
        }

        $langkey = $funcArgs[0];
        unset($funcArgs[0]);
        $args = $funcArgs;

        $langvalue = '';
        if ($this->isTmpLoaded) {
            if (($type == self::TYPE_LANG_GLOBAL or $type == self::TYPE_LANG_ALL) and isset($this->tmplang_global[$langkey])) {
                $langvalue = $this->tmplang_global[$langkey];
            } elseif (($type == self::TYPE_LANG_MODULE or $type == self::TYPE_LANG_ALL) and isset($this->tmplang_module[$langkey])) {
                $langvalue = $this->tmplang_module[$langkey];
            } elseif (($type == self::TYPE_LANG_BLOCK or $type == self::TYPE_LANG_ALL) and isset($this->tmplang_block[$langkey])) {
                $langvalue = $this->tmplang_block[$langkey];
            }
        }
        if (!$langvalue) {
            if (($type == self::TYPE_LANG_GLOBAL or $type == self::TYPE_LANG_ALL) and isset(self::$lang_global[$langkey])) {
                $langvalue = self::$lang_global[$langkey];
            } elseif (($type == self::TYPE_LANG_MODULE or $type == self::TYPE_LANG_ALL) and isset(self::$lang_module[$langkey])) {
                $langvalue = self::$lang_module[$langkey];
            } elseif (($type == self::TYPE_LANG_BLOCK or $type == self::TYPE_LANG_ALL) and isset(self::$lang_block[$langkey])) {
                $langvalue = self::$lang_block[$langkey];
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
        return $this->_get(func_get_args(), func_num_args(), self::TYPE_LANG_ALL);
    }

    /**
     * Language::getModule()
     *
     * @return
     */
    public function getModule()
    {
        return $this->_get(func_get_args(), func_num_args(), self::TYPE_LANG_MODULE);
    }

    /**
     * Language::getBlock()
     *
     * @return
     */
    public function getBlock()
    {
        return $this->_get(func_get_args(), func_num_args(), self::TYPE_LANG_BLOCK);
    }

    /**
     * Language::getGlobal()
     *
     * @return
     */
    public function getGlobal()
    {
        return $this->_get(func_get_args(), func_num_args(), self::TYPE_LANG_GLOBAL);
    }

    /**
     * Language::setModule()
     *
     * @param mixed $langkey
     * @param string $langvalue
     * @return void
     */
    public function setModule($langkey, $langvalue = '')
    {
        if (is_array($langkey)) {
            self::$lang_module = array_merge(self::$lang_module, $langkey);
        } else {
            self::$lang_module[$langkey] = $langvalue;
        }
    }

    /**
     * Language::setGlobal()
     *
     * @param mixed $langkey
     * @param string $langvalue
     * @return void
     */
    public function setGlobal($langkey, $langvalue = '')
    {
        if (is_array($langkey)) {
            self::$lang_global = array_merge(self::$lang_global, $langkey);
        } else {
            self::$lang_global[$langkey] = $langvalue;
        }
    }

    /**
     * Language::setBlock()
     *
     * @param mixed $langkey
     * @param string $langvalue
     * @return void
     */
    public function setBlock($langkey, $langvalue = '')
    {
        if (is_array($langkey)) {
            self::$lang_block = array_merge(self::$lang_block, $langkey);
        } else {
            self::$lang_block[$langkey] = $langvalue;
        }
    }

    /**
     * Language::existsGlobal()
     *
     * @param mixed $langkey
     * @return
     */
    public function existsGlobal($langkey)
    {
        return isset(self::$lang_global[$langkey]);
    }

    /**
     * Language::existsModule()
     *
     * @param mixed $langkey
     * @return
     */
    public function existsModule($langkey)
    {
        return isset(self::$lang_module[$langkey]);
    }

    /**
     * @param string $langkey
     * @return boolean
     */
    public function existsTmpModule($langkey)
    {
        return isset($this->tmplang_module[$langkey]);
    }

    /**
     * Language::existsBlock()
     *
     * @param mixed $langkey
     * @return
     */
    public function existsBlock($langkey)
    {
        return isset(self::$lang_block[$langkey]);
    }
}
