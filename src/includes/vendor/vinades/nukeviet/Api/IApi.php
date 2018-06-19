<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/3/2012, 9:10
 */

namespace NukeViet\Api;

interface IApi
{
    /**
     * Lấy được quyền hạn sử dụng của admin
     * Admin tối cao, điều hành chung hay quản lý module được sử dụng
     */
    public static function getAdminLev();

    /**
     * Danh mục, cũng là khóa ngôn ngữ của API
     * Nếu không có danh mục thì trả về chuỗi rỗng
     */
    public static function getCat();

    /**
     * Thiết lập trình xử lý kết quả
     *
     * @param ApiResult $result
     */
    public function setResultHander(ApiResult $result);

    /**
     * Thực thi API
     */
    public function execute();
}
