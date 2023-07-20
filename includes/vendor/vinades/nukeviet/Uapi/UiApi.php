<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Uapi;

/**
 * NukeViet\Uapi\UiApi
 *
 * @package NukeViet\Uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
interface UiApi
{
    /**
     * getCat()
     * Danh mục, cũng là khóa ngôn ngữ của API
     * Nếu không có danh mục thì trả về chuỗi rỗng
     *
     * @return mixed
     */
    public static function getCat();

    /**
     * setResultHander()
     * Thiết lập trình xử lý kết quả
     *
     * @return mixed
     */
    public function setResultHander(UapiResult $result);

    /**
     * execute()
     * Thực thi API
     *
     * @return mixed
     */
    public function execute();
}
