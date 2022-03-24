<?php

namespace OAuth\UserData\Arguments;

use ReflectionClass;

class AbstractArgument
{

    /**
     * @return static
     */
    public static function construct()
    {
        $reflClass = new ReflectionClass(get_called_class());

        return $reflClass->newInstanceArgs(func_get_args());
    }
}
