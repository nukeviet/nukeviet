<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Template;

use Smarty\Smarty;

/**
 * Class này mặc định sửa dụng sau khi core được load
 * Các hằng, tài nguyên nghiễm phiên dùng được
 */
class NVSmarty extends Smarty
{
    const COMPILEDIR = 'smarty-compile';
    const CACHEDIR = 'smarty-cache';

    /**
     * Smarty::__construct()
     *
     * @param mixed $config
     */
    public function __construct($config = [])
    {
        if (empty($config)) {
            global $global_config;
            $config = $global_config;
        }

        parent::__construct();

        /*
         * Bật chế độ nhà phát triển thì
         * - Bật debug
         * - //Biên dịch tpl mỗi khi truy vấn
         * - Kiểm tra thay đổi của file tpl để biên dịch lại mỗi truy vấn
         */
        if (NV_DEBUG and defined('NV_IS_ADMIN')) {
            //$this->force_compile = true;
            $this->debugging = true;
            $this->compile_check = Smarty::COMPILECHECK_ON;
        } else {
            //$this->force_compile = false;
            $this->debugging = false;
            $this->compile_check = NV_DEBUG ? Smarty::COMPILECHECK_ON : Smarty::COMPILECHECK_OFF;
        }

        $this->enableSecurity();
        $this->merge_compiled_includes = true;
        $this->setCompileDir(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . self::COMPILEDIR);
        $this->setCacheDir(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . self::CACHEDIR);

        /*
         * Xuất luôn một số hằng có sẵn trong hệ thống
         * Các hằng này viết hoa hết để phân biệt với biến thường
         */
        $this->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $this->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
        $this->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
        $this->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
        $this->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
        $this->assign('NV_LANG_DATA', NV_LANG_DATA);
        $this->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
        $this->assign('NV_ADMINDIR', NV_ADMINDIR);
        $this->assign('NV_EDITORSDIR', NV_EDITORSDIR);
        $this->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
        $this->assign('ASSETS_STATIC_URL', ASSETS_STATIC_URL);
        $this->assign('ASSETS_LANG_STATIC_URL', ASSETS_LANG_STATIC_URL);
        $this->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
        $this->assign('NV_FILES_DIR', NV_FILES_DIR);
        $this->assign('NV_CLIENT_IP', NV_CLIENT_IP);
        $this->assign('NV_MY_DOMAIN', NV_MY_DOMAIN);
        $this->assign('NV_STATIC_URL', NV_STATIC_URL);
        $this->assign('NV_GFX_WIDTH', NV_GFX_WIDTH);
        $this->assign('NV_GFX_HEIGHT', NV_GFX_HEIGHT);
        $this->assign('NV_GFX_NUM', NV_GFX_NUM);
        $this->assign('AUTO_MINIFIED', AUTO_MINIFIED);
    }
}
