<?php
echo "GD loaded: " . (extension_loaded('gd') ? "YES" : "NO") . "<br>";
echo "imagecreatetruecolor exists: " . (function_exists('imagecreatetruecolor') ? "YES" : "NO") . "<br>";
echo "GD info:<pre>";
print_r(gd_info());
echo "</pre>";
