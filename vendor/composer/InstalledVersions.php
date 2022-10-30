<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;








class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => '4.4.01',
    'version' => '4.4.01.0',
    'aliases' => 
    array (
    ),
    'reference' => NULL,
    'name' => 'nukeviet/nukeviet',
  ),
  'versions' => 
  array (
    'and/oauth' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.7.x-dev',
      ),
      'reference' => '700b769807affc1c5d04ec5a77be69a10a12e52b',
    ),
    'gregwar/cache' => 
    array (
      'pretty_version' => 'v1.0.13',
      'version' => '1.0.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '184cc341c25298ff7d584f86b55b6ca26626da4f',
    ),
    'gregwar/image' => 
    array (
      'pretty_version' => 'v2.0.28',
      'version' => '2.0.28.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c1390f5fafe2dfb4ba83a6c7bc56f75ea18c0311',
    ),
    'kriswallsmith/buzz' => 
    array (
      'pretty_version' => 'v0.15',
      'version' => '0.15.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd4041666c3ffb379af02a92dabe81c904b35fab8',
    ),
    'league/url' => 
    array (
      'pretty_version' => '3.3.5',
      'version' => '3.3.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '1ae2c3ce29a7c5438339ff6388225844e6479da8',
    ),
    'nukeviet/nukeviet' => 
    array (
      'pretty_version' => '4.4.01',
      'version' => '4.4.01.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'phpmailer/phpmailer' => 
    array (
      'pretty_version' => 'v6.5.1',
      'version' => '6.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dd803df5ad7492e1b40637f7ebd258fee5ca7355',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.18.1',
      'version' => '1.18.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a6977d63bf9a0ad4c65cd352709e230876f9904a',
    ),
    'tecnickcom/tc-lib-barcode' => 
    array (
      'pretty_version' => '1.17.6',
      'version' => '1.17.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a8a7183e2be6379e522dc825a3b7a3af5394a9e7',
    ),
    'tecnickcom/tc-lib-color' => 
    array (
      'pretty_version' => '1.14.6',
      'version' => '1.14.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '83cdb57fd900901c6aa2af8cfd67202518fb69b2',
    ),
    'true/punycode' => 
    array (
      'pretty_version' => 'v2.1.1',
      'version' => '2.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a4d0c11a36dd7f4e7cd7096076cab6d3378a071e',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}

if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}





private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {
foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
