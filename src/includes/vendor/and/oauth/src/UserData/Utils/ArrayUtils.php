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
 * Class ArrayUtils
 *
 * @package OAuth\Utils
 */
class ArrayUtils
{

    /**
     * Utility method to convert an object to an array
     *
     * @param  object $object
     *
     * @return array
     */
    public static function objectToArray($object)
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }

        return array_map('self::objectToArray', (array) $object);
    }

    /**
     * Utility method that allow to remove a list of keys from a given array.
     * This method does not modify the passed array but builds a new one.
     *
     * @param  array $array
     * @param  string[] $keys
     *
     * @return array
     */
    public static function removeKeys($array, $keys)
    {
        return array_diff_key($array, array_flip($keys));
    }

    /**
     * Retrieves a nested element from an array or $default if it doesn't exist
     * <code>
     * $friends = [
     *      'Alice' => ['age' => 33, 'hobbies' => ['biking', 'skiing']],
     *      'Bob' => ['age' => 29],
     * ];
     * Arr::getNested($friends, 'Alice.hobbies.1'); //=> 'skiing'
     * Arr::getNested($friends, ['Alice', 'hobbies', 1]); //=> 'skiing'
     * Arr::getNested($friends, 'Bob.hobbies.0', 'none'); //=> 'none'
     * </code>
     *
     * @param array $array
     * @param string|array $keys The key path as either an array or a dot-separated string
     * @param mixed $default
     *
     * @return mixed
     */
    public static function getNested($array, $keys, $default = null)
    {
        if (is_string($keys) and $keys) {
            $keys = explode('.', $keys);
        } else {
            if ($keys === null) {
                return $array;
            }
        }
        if ($keys) {
            foreach ($keys as $key) {
                if (is_array($array) && array_key_exists($key, $array)) {
                    $array = $array[ $key ];
                } else {
                    return $default;
                }
            }
        }

        return $array;
    }
}
