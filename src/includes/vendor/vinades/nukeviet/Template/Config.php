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

/**
 * Class này mặc định sửa dụng sau khi core được load
 * Các hằng, tài nguyên nghiễm nhiên dùng được
 */
class Config
{
    /**
     * Có sử dụng chế độ RTL hay không
     * @var bool
     */
    public static bool $isRtl = false;

    /**
     * Dùng các core CSS mặc định
     * @var bool
     */
    public static bool $loadCoreCss = true;

    /**
     * Có đang chế độ RTL hay không
     * @return bool
     */
    public static function isRtl(): bool
    {
        return self::$isRtl;
    }

    /**
     * Thiết đặt chế độ RTL
     * @param bool $isRtl
     * @return void
     */
    public static function setRtl(bool $isRtl = true): void
    {
        self::$isRtl = $isRtl;
    }

    /**
     * Có load core CSS hay không
     * @return bool
     */
    public static function isLoadCoreCss(): bool
    {
        return self::$loadCoreCss;
    }

    /**
     * Nếu giao diện tùy biến các core CSS thì set false
     * @param bool $loadCoreCss
     * @return void
     */
    public static function setLoadCoreCss(bool $loadCoreCss = true): void
    {
        self::$loadCoreCss = $loadCoreCss;
    }
}
