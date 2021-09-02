<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Api;

/**
 * NukeViet\Api\IApi
 *
 * @package NukeViet\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
interface IApi
{
    /**
     * getAdminLev()
     * Lấy được quyền hạn sử dụng của admin
     * Admin tối cao, điều hành chung hay quản lý module được sử dụng
     *
     * @return mixed
     */
    public static function getAdminLev();

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
    public function setResultHander(ApiResult $result);

    /**
     * execute()
     * Thực thi API
     *
     * @return mixed
     */
    public function execute();
}
