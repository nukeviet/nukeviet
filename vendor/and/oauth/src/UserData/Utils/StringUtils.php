<?php

/*
 * This file is part of the php-oauth package <https://github.com/logical-and/php-oauth>.
 *
 * (c) Oryzone, developed by Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OAuth\UserData\Utils;

/**
 * Class StringUtils
 *
 * @package OAuth\UserData\Utils
 */
class StringUtils
{

    /**
     * Extract urls from a string
     *
     * @param  string $string
     *
     * @return array
     */
    public static function extractUrls($string)
    {
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|(?:[^[:punct:]\s]|/))#', $string, $match);

        return isset($match[ 0 ]) ? $match[ 0 ] : [];
    }
}
