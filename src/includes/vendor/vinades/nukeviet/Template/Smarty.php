<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Template;

/**
 * Class này mặc định sửa dụng sau khi core được load
 * Các hằng, tài nguyên nghiễm phiên dùng được
 */
class Smarty extends \Smarty
{
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
         * - Biên dịch tpl mỗi khi truy vấn
         * - Kiểm tra thay đổi của file tpl để biên dịch lại mỗi truy vấn
         */
        if (NV_DEBUG) {
            $this->force_compile = true;
            $this->debugging = Smarty::DEBUG_ON;
            $this->compile_check = Smarty::COMPILECHECK_ON;
        } else {
            $this->force_compile = false;
            $this->debugging = Smarty::DEBUG_OFF;
            $this->compile_check = Smarty::COMPILECHECK_OFF;
        }

        $this->enableSecurity();
        $this->setCompileDir(NV_ROOTDIR . '/' . NV_CACHEDIR . '/smarty-compile');
        $this->setCacheDir(NV_ROOTDIR . '/' . NV_CACHEDIR . '/smarty-cache');

        /*
         * Xuất luôn một số hằng có sẵn trong hệ thống
         * Các hằng này viết hoa hết để phân biệt với biến thường
         */
        $this->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $this->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
        $this->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
        $this->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
        $this->assign('NV_LANG_DATA', NV_LANG_DATA);
        $this->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
        $this->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
        $this->assign('NV_FILES_DIR', NV_FILES_DIR);
    }
}
