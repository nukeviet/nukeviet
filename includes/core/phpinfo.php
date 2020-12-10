<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @copyright 2010
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010 20:40
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * phpinfo_array()
 *
 * @param integer $option
 * @param bool $return
 * @return
 * - INFO_GENERAL => 1 The configuration line, php.ini location, build date, Web Server, System and more.
 * - INFO_CREDITS => 2 PHP Credits. See also phpcredits().
 * - INFO_CONFIGURATION => 4 Current Local and Master values for PHP directives. See also ini_get().
 * - INFO_MODULES => 8 Loaded modules and their respective settings. See also get_loaded_extensions().
 * - INFO_ENVIRONMENT => 16 Environment Variable information that's also available in $_ENV.
 * - INFO_VARIABLES => 32 Shows all predefined variables from EGPCS (Environment, GET, POST, Cookie, Server).
 * - INFO_LICENSE => 64 PHP License information. See also the license FAQ.
 * - INFO_ALL => -1 Shows all of the above.
 */
function phpinfo_array($option = 1, $return = false)
{
    $pi = array();
    if (nv_function_exists('phpinfo')) {
        ob_start();
        phpinfo($option);

        $info = preg_replace(array( '#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms', '#<h1>Configuration</h1>#', "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#', "#[ \t]+#", '#&nbsp;#', '# +#', '# class=".*?"#', '%&#039;%', '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>' . '<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#', '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#', '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#', "# +#", '#<tr>#', '#</tr>#' ), array( '$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ', '<h2>PHP Configuration</h2>' . "\n" . '<tr><td>PHP Version</td><td>$2</td></tr>' . "\n" . '<tr><td>PHP Egg</td><td>$1</td></tr>', '<tr><td>PHP Credits Egg</td><td>$1</td></tr>', '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" . '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%' ), ob_get_clean());

        $sections = explode('<h2>', strip_tags($info, '<h2><th><td>'));
        unset($sections[0]);

        foreach ($sections as $section) {
            $n = substr($section, 0, strpos($section, '</h2>'));
            preg_match_all('#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#', $section, $askapache, PREG_SET_ORDER);
            foreach ($askapache as $m) {
                $pi[$n][$m[1]] = (isset($m[2]) and (! isset($m[3]) or $m[2] == $m[3])) ? $m[2] : array_slice($m, 2);
            }
        }
    }

    return ($return === false) ? print_r($pi) : $pi;
}