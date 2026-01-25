<?php
echo "PHP SAPI: " . php_sapi_name() . "<br>";
echo "PHP VERSION: " . PHP_VERSION . "<br>";
echo "INI: " . php_ini_loaded_file() . "<br>";
echo "EXT DIR: " . ini_get('extension_dir') . "<br>";
echo "gd loaded: " . (extension_loaded('gd') ? "YES" : "NO") . "<br>";
