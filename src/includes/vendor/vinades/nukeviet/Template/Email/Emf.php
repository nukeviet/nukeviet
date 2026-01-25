<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Template\Email;

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 */
class Emf
{
    /**
     * @var integer Plugin all fields
     */
    public const P_ALL = 5;

    /**
     * @var string kiểu dữ liệu của trường dữ liệu chuỗi
     */
    public const T_STRING = 'string';

    /**
     * @var string kiểu dữ liệu của trường dữ liệu số
     */
    public const T_NUMBER = 'number';

    /**
     * @var string kiểu dữ liệu của trường dữ liệu mảng một chiều gồm key và value
     */
    public const T_ARRAY = 'array';

    /**
     * @var string kiểu dữ liệu của trường dữ liệu mảng có số thứ tự phần tử liên tục
     */
    public const T_LIST = 'list';
}
