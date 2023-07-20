<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

class Language
{
    public static $lang_global = [];
    public static $lang_module = [];

    public static $tmplang_global = [];
    public static $tmplang_module = [];

    private $lang = 'vi';
    private $defaultLang = 'vi';
    private $isTmpLoaded = false;

    const TYPE_LANG_ALL = 0;
    const TYPE_LANG_GLOBAL = 1;
    const TYPE_LANG_MODULE = 2;

    /**
     * Language::__construct()
     *
     * @param string $defaultLang
     */
    public function __construct($defaultLang = '')
    {
        $this->lang = NV_LANG_INTERFACE;
        if (!empty($defaultLang)) {
            $this->defaultLang = $defaultLang;
        }
    }

    /**
     * Language::setLang()
     *
     * @param mixed $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        self::$lang_global = [];
        self::$lang_module = [];
        self::$tmplang_global = [];
        self::$tmplang_module = [];
    }

    /**
     * Language::changeLang()
     *
     * @param string $lang
     */
    public function changeLang($lang = '')
    {
        if (!empty($lang)) {
            $this->lang = $lang;
        }
        self::$tmplang_global = [];
        self::$tmplang_module = [];
        $this->isTmpLoaded = false;
    }

    /**
     * Language::loadModule()
     *
     * @param mixed $modfile
     * @param bool  $modadmin
     * @param bool  $loadtmp
     */
    public function loadModule($modfile, $modadmin = false, $loadtmp = false)
    {
        if ($modadmin and !defined('NV_ADMIN')) {
            return false;
        }
        if ($modadmin) {
            if ($this->lang != $this->defaultLang) {
                $this->load(NV_ROOTDIR . '/includes/language/' . $this->defaultLang . '/admin_' . $modfile . '.php', $loadtmp);
            }
            $this->load(NV_ROOTDIR . '/includes/language/' . $this->lang . '/admin_' . $modfile . '.php', $loadtmp);
        } else {
            if ($this->lang != $this->defaultLang) {
                $this->load(NV_ROOTDIR . '/modules/' . $modfile . '/language/' . $this->defaultLang . '.php', $loadtmp);
            }
            $this->load(NV_ROOTDIR . '/modules/' . $modfile . '/language/' . $this->lang . '.php', $loadtmp);
        }
    }

    /**
     * Đọc một file ngôn ngữ bất kỳ
     *
     * @param string $file
     * @param bool   $loadtmp
     */
    public function loadFile($file, $loadtmp = false)
    {
        $this->load($file, $loadtmp);
    }

    /**
     * Language::loadInstall()
     *
     * @param mixed $lang
     */
    public function loadInstall($lang)
    {
        if (!defined('NV_ADMIN')) {
            return false;
        }
        if ($lang != $this->defaultLang) {
            $this->load(NV_ROOTDIR . '/includes/language/' . $this->defaultLang . '/install.php');
        }
        $this->load(NV_ROOTDIR . '/includes/language/' . $lang . '/install.php');
    }

    /**
     * Language::loadTheme()
     *
     * @param mixed $theme
     * @param bool  $loadtmp
     */
    public function loadTheme($theme, $loadtmp = false)
    {
        if ($this->lang != $this->defaultLang) {
            $this->load(NV_ROOTDIR . '/themes/' . $theme . '/language/' . $this->defaultLang . '.php', $loadtmp);
        }
        $this->load(NV_ROOTDIR . '/themes/' . $theme . '/language/' . $this->lang . '.php', $loadtmp);
    }

    /**
     * Language::loadGlobal()
     *
     * @param bool $admin
     */
    public function loadGlobal($admin = false)
    {
        if ($admin and !defined('NV_ADMIN')) {
            return false;
        }
        $fileName = ($admin ? 'admin_' : '') . 'global.php';
        if ($this->lang != $this->defaultLang) {
            $this->load(NV_ROOTDIR . '/includes/language/' . $this->defaultLang . '/' . $fileName);
        }
        $this->load(NV_ROOTDIR . '/includes/language/' . $this->lang . '/' . $fileName);
    }

    /**
     * Language::load()
     *
     * @param mixed $file
     * @param bool  $loadtmp
     */
    private function load($file, $loadtmp = false)
    {
        if (!$loadtmp) {
            if (file_exists($file)) {
                $lang_global = $lang_module = [];
                require $file;

                if (!empty($lang_global)) {
                    self::$lang_global = array_merge(self::$lang_global, $lang_global);
                }
                if (!empty($lang_module)) {
                    self::$lang_module = array_merge(self::$lang_module, $lang_module);
                }
            }
        } elseif ($loadtmp) {
            if (file_exists($file)) {
                $lang_global = $lang_module = [];
                require $file;

                if (!empty($lang_global)) {
                    $this->isTmpLoaded = true;
                    self::$tmplang_global = array_merge(self::$tmplang_global, $lang_global);
                }
                if (!empty($lang_module)) {
                    $this->isTmpLoaded = true;
                    self::$tmplang_module = array_merge(self::$tmplang_module, $lang_module);
                }
            }
        }
    }

    /**
     * Language::_get()
     *
     * @param mixed $funcArgs
     * @param mixed $funcNum
     * @param mixed $type
     */
    private function _get($funcArgs, $funcNum, $type)
    {
        if ($funcNum < 1) {
            return '';
        }

        $langkey = $funcArgs[0];
        $args = $funcArgs;
        unset($args[0]);

        $langvalue = '';
        if ($this->isTmpLoaded) {
            if (($type == self::TYPE_LANG_GLOBAL or $type == self::TYPE_LANG_ALL) and isset(self::$tmplang_global[$langkey])) {
                $langvalue = self::$tmplang_global[$langkey];
            } elseif (($type == self::TYPE_LANG_MODULE or $type == self::TYPE_LANG_ALL) and isset(self::$tmplang_module[$langkey])) {
                $langvalue = self::$tmplang_module[$langkey];
            }
        }
        if (empty($langvalue)) {
            if (($type == self::TYPE_LANG_GLOBAL or $type == self::TYPE_LANG_ALL) and isset(self::$lang_global[$langkey])) {
                $langvalue = self::$lang_global[$langkey];
            } elseif (($type == self::TYPE_LANG_MODULE or $type == self::TYPE_LANG_ALL) and isset(self::$lang_module[$langkey])) {
                $langvalue = self::$lang_module[$langkey];
            }
        }

        if (empty($langvalue)) {
            return $langkey;
        }
        /*
         * Khi bật chế độ debug, hiển thị cảnh cáo nếu như đọc ngôn ngữ module
         * Mà key lang tồn tại ở ngôn ngữ global và giá trị là như nhau
         */
        /*if (NV_DEBUG and $type == self::TYPE_LANG_MODULE and isset(self::$lang_global[$langkey]) and $this->_get($funcArgs, $funcNum, self::TYPE_LANG_GLOBAL) == $langvalue) {
            trigger_error('You are using a language key available in lang global &gt;&gt;&gt;&gt; ' . $langkey);
        }*/

        return empty($args) ? $langvalue : vsprintf($langvalue, $args);
    }

    /**
     * Language::get()
     */
    public function get()
    {
        return $this->_get(func_get_args(), func_num_args(), self::TYPE_LANG_ALL);
    }

    /**
     * Language::getModule()
     */
    public function getModule()
    {
        return $this->_get(func_get_args(), func_num_args(), self::TYPE_LANG_MODULE);
    }

    /**
     * Language::getGlobal()
     */
    public function getGlobal()
    {
        return $this->_get(func_get_args(), func_num_args(), self::TYPE_LANG_GLOBAL);
    }

    /**
     * Language::setModule()
     *
     * @param mixed  $langkey
     * @param string $langvalue
     * @param bool   $loadtmp
     */
    public function setModule($langkey, $langvalue = '', $loadtmp = false)
    {
        if (is_array($langkey)) {
            if ($loadtmp) {
                self::$tmplang_module = array_merge(self::$tmplang_module, $langkey);
            } else {
                self::$lang_module = array_merge(self::$lang_module, $langkey);
            }
        } else {
            if ($loadtmp) {
                self::$tmplang_module[$langkey] = $langvalue;
            } else {
                self::$lang_module[$langkey] = $langvalue;
            }
        }
    }

    /**
     * Language::setGlobal()
     *
     * @param mixed  $langkey
     * @param string $langvalue
     * @param bool   $loadtmp
     */
    public function setGlobal($langkey, $langvalue = '', $loadtmp = false)
    {
        if (is_array($langkey)) {
            if ($loadtmp) {
                self::$tmplang_global = array_merge(self::$tmplang_global, $langkey);
            } else {
                self::$lang_global = array_merge(self::$lang_global, $langkey);
            }
        } else {
            if ($loadtmp) {
                self::$tmplang_global[$langkey] = $langvalue;
            } else {
                self::$lang_global[$langkey] = $langvalue;
            }
        }
    }

    /**
     * Language::existsGlobal()
     *
     * @param mixed $langkey
     */
    public function existsGlobal($langkey)
    {
        return isset(self::$lang_global[$langkey]);
    }

    /**
     * Language::existsModule()
     *
     * @param mixed $langkey
     */
    public function existsModule($langkey)
    {
        return isset(self::$lang_module[$langkey]);
    }

    /**
     * @param string $langkey
     * @return bool
     */
    public function existsTmpModule($langkey)
    {
        return isset($this->tmplang_module[$langkey]);
    }
}
