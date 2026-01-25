<?php

/**
 * File: Browser.php
 * Author: Chris Schuld (http://chrisschuld.com/)
 * Last Modified: April 14th, 2020
 * @version 1.9.6
 *
 * Copyright 2019 Chris Schuld
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without
 * limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to
 * whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * Typical Usage:
 *
 *   $browser = new Browser();
 *   if( $browser->getBrowser() == Browser::BROWSER_FIREFOX && $browser->getVersion() >= 2 ) {
 *    echo 'You have FireFox version 2 or greater';
 *   }
 *
 * User Agents Sampled from: http://www.useragentstring.com/
 *
 * This implementation is based on the original work from Gary White
 * http://apptools.com/phptools/browser/
 *
 */

namespace NukeViet\Client;

class Browser
{

    private $_agent = '';

    private $_browser_name = '';

    private $_browser_key = '';

    private $_version = '';

    private $_platform = '';

    private $_platform_family = '';

    private $_os = '';

    private $_is_aol = false;

    private $_is_mobile = false;

    private $_is_tablet = false;

    private $_is_robot = false;

    private $_is_facebook = false;

    private $_aol_version = '';

    const BROWSER_UNKNOWN = 'unknown';

    const VERSION_UNKNOWN = 'unknown';

    // http://www.opera.com/
    const BROWSER_OPERA = 'opera';

    // http://www.opera.com/mini/
    const BROWSER_OPERA_MINI = 'operamini';

    // http://www.webtv.net/pc/
    const BROWSER_WEBTV = 'webtv';

    // http://www.microsoft.com/ie/
    const BROWSER_IE = 'explorer';

    // https://www.microsoft.com/en-us/windows/microsoft-edge
    const BROWSER_EDGE = 'edge';

    // http://en.wikipedia.org/wiki/Internet_Explorer_Mobile
    const BROWSER_POCKET_IE = 'pocket';

    // http://www.konqueror.org/
    const BROWSER_KONQUEROR = 'konqueror';

    // http://www.icab.de/
    const BROWSER_ICAB = 'icab';

    // http://www.omnigroup.com/applications/omniweb/
    const BROWSER_OMNIWEB = 'omniweb';

    // http://www.ibphoenix.com/
    const BROWSER_FIREBIRD = 'firebird';

    // http://www.mozilla.com/en-US/firefox/firefox.html
    const BROWSER_FIREFOX = 'firefox';

    // https://brave.com/
    const BROWSER_BRAVE = 'brave';

    // https://www.palemoon.org/
    const BROWSER_PALEMOON = 'palemoon';

    // http://www.geticeweasel.org/
    const BROWSER_ICEWEASEL = 'iceweasel';

    // http://wiki.mozilla.org/Projects/shiretoko
    const BROWSER_SHIRETOKO = 'shiretoko';

    // http://www.mozilla.com/en-US/
    const BROWSER_MOZILLA = 'mozilla';

    // http://www.w3.org/Amaya/
    const BROWSER_AMAYA = 'amaya';

    // http://en.wikipedia.org/wiki/Lynx
    const BROWSER_LYNX = 'lynx';

    // http://apple.com
    const BROWSER_SAFARI = 'safari';

    // http://apple.com
    const BROWSER_IPHONE = 'iphone';

    // http://apple.com
    const BROWSER_IPOD = 'ipod';

    // http://apple.com
    const BROWSER_IPAD = 'ipad';

    // http://www.google.com/chrome
    const BROWSER_CHROME = 'chrome';

    // https://coccoc.com
    const BROWSER_COCCOC = 'coccoc';

    // http://www.android.com/
    const BROWSER_ANDROID = 'android';

    // http://en.wikipedia.org/wiki/Googlebot
    const BROWSER_GOOGLEBOT = 'googlebot';

    // http://help.coccoc.com/searchengine
    const BROWSER_COCCOCBOT = 'coccocbot';

    // https://en.wikipedia.org/wiki/CURL
    const BROWSER_CURL = 'curl';

    // https://en.wikipedia.org/wiki/Wget
    const BROWSER_WGET = 'wget';

    // https://www.ucweb.com/
    const BROWSER_UCBROWSER = 'ucbrowser';

    // http://yandex.com/bots
    const BROWSER_YANDEXBOT = 'yandexbot';

    // http://yandex.com/bots
    const BROWSER_YANDEXIMAGERESIZER_BOT = 'yandeximageresizer';

    // http://yandex.com/bots
    const BROWSER_YANDEXIMAGES_BOT = 'yandeximages';

    // http://yandex.com/bots
    const BROWSER_YANDEXVIDEO_BOT = 'yandexvideo';

    // http://yandex.com/bots
    const BROWSER_YANDEXMEDIA_BOT = 'yandexmedia';

    // http://yandex.com/bots
    const BROWSER_YANDEXBLOGS_BOT = 'yandexblogs';

    // http://yandex.com/bots
    const BROWSER_YANDEXFAVICONS_BOT = 'yandexfavicons';

    // http://yandex.com/bots
    const BROWSER_YANDEXWEBMASTER_BOT = 'yandexwebmaster';

    // http://yandex.com/bots
    const BROWSER_YANDEXDIRECT_BOT = 'yandexdirect';

    // http://yandex.com/bots
    const BROWSER_YANDEXMETRIKA_BOT = 'yandexmetrika';

    // http://yandex.com/bots
    const BROWSER_YANDEXNEWS_BOT = 'yandexnews';

    // http://yandex.com/bots
    const BROWSER_YANDEXCATALOG_BOT = 'yandexcatalog';

    // http://en.wikipedia.org/wiki/Yahoo!_Slurp
    const BROWSER_SLURP = 'yahooslurp';

    // http://validator.w3.org/
    const BROWSER_W3CVALIDATOR = 'w3cvalidator';

    // http://www.blackberry.com/
    const BROWSER_BLACKBERRY = 'blackberry';

    // http://en.wikipedia.org/wiki/GNU_IceCat
    const BROWSER_ICECAT = 'icecat';

    // http://en.wikipedia.org/wiki/Web_Browser_for_S60
    const BROWSER_NOKIA_S60 = 'nokias60';

    // * all other WAP-based browsers on the Nokia Platform
    const BROWSER_NOKIA = 'nokia';

    // http://explorer.msn.com/
    const BROWSER_MSN = 'msn';

    // http://search.msn.com/msnbot.htm
    const BROWSER_MSNBOT = 'msnbot';

    // http://en.wikipedia.org/wiki/Bingbot
    const BROWSER_BINGBOT = 'bingbot';

    // https://vivaldi.com/
    const BROWSER_VIVALDI = 'vivaldi';

    // https://browser.yandex.ua/
    const BROWSER_YANDEX = 'yandex';

    // http://browser.netscape.com/ (DEPRECATED)
    const BROWSER_NETSCAPE_NAVIGATOR = 'netscape';

    // http://galeon.sourceforge.net/ (DEPRECATED)
    const BROWSER_GALEON = 'galeon';

    // http://en.wikipedia.org/wiki/NetPositive (DEPRECATED)
    const BROWSER_NETPOSITIVE = 'netpositive';

    // http://en.wikipedia.org/wiki/History_of_Mozilla_Firefox (DEPRECATED)
    const BROWSER_PHOENIX = 'phoenix';

    const BROWSER_PLAYSTATION = "playstation";

    const BROWSER_SAMSUNG = "samsungbrowser";

    const BROWSER_SILK = "silk";

    const BROWSER_I_FRAME = "iframely";

    const BROWSER_COCOA = "cocoarestclient";

    // http://www.opera.com/
    const BROWSER_OPERA_NAME = 'Opera';

    // http://www.opera.com/mini/
    const BROWSER_OPERA_MINI_NAME = 'Opera Mini';

    // http://www.webtv.net/pc/
    const BROWSER_WEBTV_NAME = 'WebTV';

    // http://www.microsoft.com/ie/
    const BROWSER_IE_NAME = 'Internet Explorer';

    // https://msdn.microsoft.com/en-us/library/hh869301%28v=vs.85%29.aspx
    const BROWSER_EDGE_NAME = 'Microsoft Edge';

    // http://en.wikipedia.org/wiki/Internet_Explorer_Mobile
    const BROWSER_POCKET_IE_NAME = 'Pocket Internet Explorer';

    // http://www.konqueror.org/
    const BROWSER_KONQUEROR_NAME = 'Konqueror';

    // http://www.icab.de/
    const BROWSER_ICAB_NAME = 'iCab';

    // http://www.omnigroup.com/applications/omniweb/
    const BROWSER_OMNIWEB_NAME = 'OmniWeb';

    // http://www.ibphoenix.com/
    const BROWSER_FIREBIRD_NAME = 'Firebird';

    // http://www.mozilla.com/en-US/firefox/firefox.html
    const BROWSER_FIREFOX_NAME = 'Firefox';

    // https://brave.com/
    const BROWSER_BRAVE_NAME = 'Brave';

    // https://www.palemoon.org/
    const BROWSER_PALEMOON_NAME = 'Palemoon';

    // http://www.geticeweasel.org/
    const BROWSER_ICEWEASEL_NAME = 'Iceweasel';

    // http://wiki.mozilla.org/Projects/shiretoko
    const BROWSER_SHIRETOKO_NAME = 'Shiretoko';

    // http://www.mozilla.com/en-US/
    const BROWSER_MOZILLA_NAME = 'Mozilla';

    // http://www.w3.org/Amaya/
    const BROWSER_AMAYA_NAME = 'Amaya';

    // http://en.wikipedia.org/wiki/Lynx
    const BROWSER_LYNX_NAME = 'Lynx';

    // http://apple.com
    const BROWSER_SAFARI_NAME = 'Safari';

    // http://apple.com
    const BROWSER_IPHONE_NAME = 'iPhone';

    // http://apple.com
    const BROWSER_IPOD_NAME = 'iPod';

    // http://apple.com
    const BROWSER_IPAD_NAME = 'iPad';

    // http://www.google.com/chrome
    const BROWSER_CHROME_NAME = 'Chrome';

    // https://coccoc.com
    const BROWSER_COCCOC_NAME = 'Coc Coc';

    // http://www.android.com/
    const BROWSER_ANDROID_NAME = 'Android';

    // http://en.wikipedia.org/wiki/Googlebot
    const BROWSER_GOOGLEBOT_NAME = 'GoogleBot';

    // http://help.coccoc.com/searchengine
    const BROWSER_COCCOCBOT_NAME = 'CoccocBot';

    // https://en.wikipedia.org/wiki/CURL
    const BROWSER_CURL_NAME = 'cURL';

    // https://en.wikipedia.org/wiki/Wget
    const BROWSER_WGET_NAME = 'Wget';

    // https://www.ucweb.com/
    const BROWSER_UCBROWSER_NAME = 'UCBrowser';

    // http://yandex.com/bots
    const BROWSER_YANDEXBOT_NAME = 'YandexBot';

    // http://yandex.com/bots
    const BROWSER_YANDEXIMAGERESIZER_BOT_NAME = 'YandexImageResizer';

    // http://yandex.com/bots
    const BROWSER_YANDEXIMAGES_BOT_NAME = 'YandexImages';

    // http://yandex.com/bots
    const BROWSER_YANDEXVIDEO_BOT_NAME = 'YandexVideo';

    // http://yandex.com/bots
    const BROWSER_YANDEXMEDIA_BOT_NAME = 'YandexMedia';

    // http://yandex.com/bots
    const BROWSER_YANDEXBLOGS_BOT_NAME = 'YandexBlogs';

    // http://yandex.com/bots
    const BROWSER_YANDEXFAVICONS_BOT_NAME = 'YandexFavicons';

    // http://yandex.com/bots
    const BROWSER_YANDEXWEBMASTER_BOT_NAME = 'YandexWebmaster';

    // http://yandex.com/bots
    const BROWSER_YANDEXDIRECT_BOT_NAME = 'YandexDirect';

    // http://yandex.com/bots
    const BROWSER_YANDEXMETRIKA_BOT_NAME = 'YandexMetrika';

    // http://yandex.com/bots
    const BROWSER_YANDEXNEWS_BOT_NAME = 'YandexNews';

    // http://yandex.com/bots
    const BROWSER_YANDEXCATALOG_BOT_NAME = 'YandexCatalog';

    // http://en.wikipedia.org/wiki/Yahoo!_Slurp
    const BROWSER_SLURP_NAME = 'Yahoo! Slurp';

    // http://validator.w3.org/
    const BROWSER_W3CVALIDATOR_NAME = 'W3C Validator';

    // http://www.blackberry.com/
    const BROWSER_BLACKBERRY_NAME = 'BlackBerry';

    // http://en.wikipedia.org/wiki/GNU_IceCat
    const BROWSER_ICECAT_NAME = 'IceCat';

    // http://en.wikipedia.org/wiki/Web_Browser_for_S60
    const BROWSER_NOKIA_S60_NAME = 'Nokia S60 OSS Browser';

    // * all other WAP-based browsers on the Nokia Platform
    const BROWSER_NOKIA_NAME = 'Nokia Browser';

    // http://explorer.msn.com/
    const BROWSER_MSN_NAME = 'MSN Browser';

    // http://search.msn.com/msnbot.htm
    const BROWSER_MSNBOT_NAME = 'MSN Bot';

    // http://en.wikipedia.org/wiki/Bingbot
    const BROWSER_BINGBOT_NAME = 'Bing Bot';

    // https://vivaldi.com/
    const BROWSER_VIVALDI_NAME = 'Vivaldi';

    // https://browser.yandex.ua/
    const BROWSER_YANDEX_NAME = 'Yandex';

    // http://browser.netscape.com/ (DEPRECATED)
    const BROWSER_NETSCAPE_NAVIGATOR_NAME = 'Netscape Navigator';

    // http://galeon.sourceforge.net/ (DEPRECATED)
    const BROWSER_GALEON_NAME = 'Galeon';

    // http://en.wikipedia.org/wiki/NetPositive (DEPRECATED)
    const BROWSER_NETPOSITIVE_NAME = 'NetPositive';

    // http://en.wikipedia.org/wiki/History_of_Mozilla_Firefox (DEPRECATED)
    const BROWSER_PHOENIX_NAME = 'Phoenix';

    const BROWSER_PLAYSTATION_NAME = "PlayStation";

    const BROWSER_SAMSUNG_NAME = "SamsungBrowser";

    const BROWSER_SILK_NAME = "Silk";

    const BROWSER_I_FRAME_NAME = "Iframely";

    const BROWSER_COCOA_NAME = "CocoaRestClient";

    const PLATFORM_UNKNOWN = 'unknown';

    const PLATFORM_WINDOWS = 'win';

    const PLATFORM_WINDOWS_11 = 'win11';

    const PLATFORM_WINDOWS_10 = 'win10';

    const PLATFORM_WINDOWS_8 = 'win8';

    const PLATFORM_WINDOWS_7 = 'win7';

    const PLATFORM_WINDOWS_2003 = 'win2003';

    const PLATFORM_WINDOWS_VISTA = 'winvista';

    const PLATFORM_WINDOWS_CE = 'wince';

    const PLATFORM_WINDOWS_XP = 'winxp';

    const PLATFORM_APPLE = 'apple';

    const PLATFORM_LINUX = 'linux';

    const PLATFORM_OS2 = 'os2';

    const PLATFORM_BEOS = 'beos';

    const PLATFORM_IPHONE = 'iphone';

    const PLATFORM_IPOD = 'ipod';

    const PLATFORM_IPAD = 'ipad';

    const PLATFORM_BLACKBERRY = 'blackberry';

    const PLATFORM_NOKIA = 'nokia';

    const PLATFORM_FREEBSD = 'freebsd';

    const PLATFORM_OPENBSD = 'openbsd';

    const PLATFORM_NETBSD = 'netbsd';

    const PLATFORM_SUNOS = 'sunos';

    const PLATFORM_OPENSOLARIS = 'opensolaris';

    const PLATFORM_ANDROID = 'android';

    const PLATFORM_PLAYSTATION = "sonyplaystation";

    const PLATFORM_ROKU = "roku";

    const PLATFORM_APPLE_TV = "appletv";

    const PLATFORM_TERMINAL = "terminal";

    const PLATFORM_FIRE_OS = "fireos";

    const PLATFORM_SMART_TV = "smarttv";

    const PLATFORM_CHROME_OS = "chromeos";

    const PLATFORM_JAVA_ANDROID = "javaandroid";

    const PLATFORM_POSTMAN = "postman";

    const PLATFORM_I_FRAME = "iframely";

    const PLATFORM_IRIX = 'irix';

    const PLATFORM_PALM = 'palm';

    const OPERATING_SYSTEM_UNKNOWN = 'unknown';

    /**
     * Class constructor
     *
     * @param string $userAgent
     */
    public function __construct($userAgent = "")
    {
        $this->reset();
        if ($userAgent != "") {
            $this->setUserAgent($userAgent);
        } else {
            $this->determine();
        }
    }

    /**
     * Reset all properties
     */
    public function reset()
    {
        $this->_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
        $this->_browser_name = self::BROWSER_UNKNOWN;
        $this->_browser_key = self::BROWSER_UNKNOWN;
        $this->_version = self::VERSION_UNKNOWN;
        $this->_platform = self::PLATFORM_UNKNOWN;
        $this->_platform_family = self::PLATFORM_UNKNOWN;
        $this->_os = self::OPERATING_SYSTEM_UNKNOWN;
        $this->_is_aol = false;
        $this->_is_mobile = false;
        $this->_is_tablet = false;
        $this->_is_robot = false;
        $this->_is_facebook = false;
        $this->_aol_version = self::VERSION_UNKNOWN;
    }

    /**
     * Check to see if the specific browser is valid
     *
     * @param string $browserName
     * @return bool True if the browser is the specified browser
     */
    public function isBrowser($browserName)
    {
        return (0 == strcasecmp($this->_browser_name, trim($browserName)) or 0 == strcasecmp($this->_browser_key, trim($browserName)));
    }

    /**
     * The name of the browser.
     * All return types are from the class contants
     *
     * @return string Name of the browser
     */
    public function getBrowser()
    {
        return $this->_browser_name;
    }

    /**
     * The key of the browser.
     * All return types are from the class contants
     *
     * @return string Key of the browser
     */
    public function getBrowserKey()
    {
        return $this->_browser_key;
    }

    /**
     * Set the name of the browser
     *
     * @param $browser string
     *            The name of the Browser
     */
    public function setBrowser($browser, $browser_name)
    {
        $this->_browser_name = $browser_name;
        $this->_browser_key = $browser;
    }

    /**
     * The name of the platform.
     * All return types are from the class contants
     *
     * @return string Name of the browser
     */
    public function getPlatform()
    {
        $names = [
            'unknown' => 'Unknown',
            'win' => 'Windows',
            'win10' => 'Windows 10/11',
            'win11' => 'Windows 10/11',
            'win8' => 'Windows 8',
            'win7' => 'Windows 7',
            'win2003' => 'Windows 2003',
            'winvista' => 'Windows Vista',
            'wince' => 'Windows CE',
            'winxp' => 'Windows XP',
            'apple' => 'Apple',
            'linux' => 'Linux',
            'os2' => 'OS/2',
            'beos' => 'BeOS',
            'iphone' => 'iPhone',
            'ipod' => 'iPod',
            'ipad' => 'iPad',
            'blackberry' => 'BlackBerry',
            'nokia' => 'Nokia',
            'freebsd' => 'FreeBSD',
            'openbsd' => 'OpenBSD',
            'netbsd' => 'NetBSD',
            'sunos' => 'SunOS',
            'opensolaris' => 'OpenSolaris',
            'android' => 'Android',
            'irix' => 'Irix',
            'palm' => 'Palm',
            'sonyplaystation' => 'Sony PlayStation',
            'roku' => 'Roku',
            'appletv' => 'Apple TV',
            'terminal' => 'Terminal',
            'fireos' => 'Fire OS',
            'smarttv' => 'SMART-TV',
            'chromeos' => 'Chrome OS',
            'javaandroid' => 'Java/Android',
            'postman' => 'Postman',
            'iframely' => 'Iframely'
        ];
        return isset($names[$this->_platform]) ? $names[$this->_platform] : $names['unknown'];
    }

    /**
     * @return string
     */
    public function getPlatformKey()
    {
        return $this->_platform;
    }

    /**
     * @return string
     */
    public function getPlatformFamily()
    {
        return $this->_platform_family;
    }

    /**
     * Set the name of the platform
     *
     * @param string $platform
     *            The name of the Platform
     */
    public function setPlatform($platform)
    {
        $this->_platform = $platform;
    }

    /**
     * @param string $platform_family
     * @return Browser
     */
    public function setPlatformFamily(string $platform_family): Browser
    {
        $this->_platform_family = $platform_family;
        return $this;
    }

    /**
     * The version of the browser.
     *
     * @return string Version of the browser (will only contain alpha-numeric characters and a period)
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * Set the version of the browser
     *
     * @param string $version
     *            The version of the Browser
     */
    public function setVersion($version)
    {
        $this->_version = preg_replace('/[^0-9,.,a-z,A-Z-]/', '', $version);
    }

    /**
     * The version of AOL.
     *
     * @return string Version of AOL (will only contain alpha-numeric characters and a period)
     */
    public function getAolVersion()
    {
        return $this->_aol_version;
    }

    /**
     * Set the version of AOL
     *
     * @param string $version
     *            The version of AOL
     */
    public function setAolVersion($version)
    {
        $this->_aol_version = preg_replace('/[^0-9,.,a-z,A-Z]/', '', $version);
    }

    /**
     * Is the browser from AOL?
     *
     * @return boolean True if the browser is from AOL otherwise false
     */
    public function isAol()
    {
        return $this->_is_aol;
    }

    /**
     * Is the browser from a mobile device?
     *
     * @return boolean True if the browser is from a mobile device otherwise false
     */
    public function isMobile()
    {
        return $this->_is_mobile;
    }

    /**
     * Is the browser from a tablet device?
     *
     * @return boolean True if the browser is from a tablet device otherwise false
     */
    public function isTablet()
    {
        return $this->_is_tablet;
    }

    /**
     * Is the browser from a robot (ex Slurp,GoogleBot)?
     *
     * @return boolean True if the browser is from a robot otherwise false
     */
    public function isRobot()
    {
        if (empty($this->_is_robot)) {
            if (stripos($this->_agent, 'googlebot') !== false) {
                $aresult = explode('/', stristr($this->_agent, 'googlebot'));
                if (isset($aresult[1])) {
                    $this->setRobot(true);
                }
            } elseif (stripos($this->_agent, 'msnbot') !== false) {
                $aresult = explode('/', stristr($this->_agent, 'msnbot'));
                if (isset($aresult[1])) {
                    $this->setRobot(true);
                    return true;
                }
            } elseif (stripos($this->_agent, 'bingbot') !== false) {
                $aresult = explode('/', stristr($this->_agent, 'bingbot'));
                if (isset($aresult[1])) {
                    $this->setRobot(true);
                }
            } elseif (stripos($this->_agent, 'slurp') !== false) {
                $aresult = explode('/', stristr($this->_agent, 'Slurp'));
                if (isset($aresult[1])) {
                    $this->setRobot(true);
                }
            } elseif (stristr($this->_agent, 'FacebookExternalHit')) {
                $this->setRobot(true);
                $this->setFacebook(true);
            }
        }
        return $this->_is_robot;
    }

    /**
     * Is the browser from facebook?
     *
     * @return boolean True if the browser is from facebook otherwise false
     */
    public function isFacebook()
    {
        return $this->_is_facebook;
    }

    /**
     * Set the browser to be from AOL
     *
     * @param
     *            $isAol
     */
    public function setAol($isAol)
    {
        $this->_is_aol = $isAol;
    }

    /**
     * Set the Browser to be mobile
     *
     * @param boolean $value
     *            is the browser a mobile browser or not
     */
    protected function setMobile($value = true)
    {
        $this->_is_mobile = $value;
    }

    /**
     * Set the Browser to be tablet
     *
     * @param boolean $value
     *            is the browser a tablet browser or not
     */
    protected function setTablet($value = true)
    {
        $this->_is_tablet = $value;
    }

    /**
     * Set the Browser to be a robot
     *
     * @param boolean $value
     *            is the browser a robot or not
     */
    protected function setRobot($value = true)
    {
        $this->_is_robot = $value;
    }

    /**
     * Set the Browser to be a Facebook request
     *
     * @param boolean $value
     *            is the browser a robot or not
     */
    protected function setFacebook($value = true)
    {
        $this->_is_facebook = $value;
    }

    /**
     * Get the user agent value in use to determine the browser
     *
     * @return string The user agent from the HTTP header
     */
    public function getUserAgent()
    {
        return $this->_agent;
    }

    /**
     * Set the user agent value (the construction will use the HTTP header value - this will overwrite it)
     *
     * @param string $agent_string
     *            The value for the User Agent
     */
    public function setUserAgent($agent_string)
    {
        $this->reset();
        $this->_agent = $agent_string;
        $this->determine();
    }

    /**
     * Used to determine if the browser is actually "chromeframe"
     *
     * @since 1.7
     * @return boolean True if the browser is using chromeframe
     */
    public function isChromeFrame()
    {
        return (strpos($this->_agent, 'chromeframe') !== false);
    }

    /**
     * Returns a formatted string with a summary of the details of the browser.
     *
     * @return string formatted string with a summary of the browser
     */
    public function __toString()
    {
        return "<strong>Browser Key:</strong> {$this->getBrowserKey()}<br/>\nBrowser Name:</strong> {$this->getBrowser()}<br/>\n" . "<strong>Browser Version:</strong> {$this->getVersion()}<br/>\n" . "<strong>Browser User Agent String:</strong> {$this->getUserAgent()}<br/>\n" . "<strong>Platform:</strong> {$this->getPlatform()}<br/>";
    }

    /**
     * Protected routine to calculate and determine what the browser is in use (including platform)
     */
    protected function determine()
    {
        $this->checkPlatform();
        $this->checkBrowsers();
        $this->checkForAol();
    }

    /**
     * Protected routine to determine the browser type
     *
     * @return boolean True if the browser was detected otherwise false
     */
    protected function checkBrowsers()
    {
        return ( // well-known, well-used
                  // Special Notes:
                  // (1) Opera must be checked before FireFox due to the odd
                  // user agents used in some older versions of Opera
                  // (2) WebTV is strapped onto Internet Explorer so we must
                  // check for WebTV before IE
                  // (3) (deprecated) Galeon is based on Firefox and needs to be
                  // tested before Firefox is tested
                  // (4) OmniWeb is based on Safari so OmniWeb check must occur
                  // before Safari
                  // (5) Netscape 9+ is based on Firefox so Netscape checks
                  // before FireFox are necessary
        $this->checkBrowserWebTv() or $this->checkBrowserBrave() or $this->checkBrowserUCBrowser() or $this->checkBrowserEdge() or $this->checkBrowserInternetExplorer() or $this->checkBrowserOpera() or $this->checkBrowserGaleon() or $this->checkBrowserNetscapeNavigator9Plus() or $this->checkBrowserVivaldi() or $this->checkBrowserYandex() or $this->checkBrowserPalemoon() or $this->checkBrowserFirefox() or $this->checkBrowserChrome() or $this->checkBrowserOmniWeb() or
        // common mobile
        $this->checkBrowserAndroid() or $this->checkBrowseriPad() or $this->checkBrowseriPod() or $this->checkBrowseriPhone() or $this->checkBrowserBlackBerry() or $this->checkBrowserNokia() or
        // common bots
        $this->checkBrowserGoogleBot() or $this->checkBrowserMSNBot() or $this->checkBrowserBingBot() or $this->checkBrowserSlurp() or $this->checkBrowserCoccocBot() or
        // Yandex bots
        $this->checkBrowserYandexBot() or $this->checkBrowserYandexImageResizerBot() or $this->checkBrowserYandexBlogsBot() or $this->checkBrowserYandexCatalogBot() or $this->checkBrowserYandexDirectBot() or $this->checkBrowserYandexFaviconsBot() or $this->checkBrowserYandexImagesBot() or $this->checkBrowserYandexMediaBot() or $this->checkBrowserYandexMetrikaBot() or $this->checkBrowserYandexNewsBot() or $this->checkBrowserYandexVideoBot() or $this->checkBrowserYandexWebmasterBot() or
        // check for facebook external hit when loading URL
        $this->checkFacebookExternalHit() or
        // WebKit base check (post mobile and others)
        $this->checkBrowserSamsung() or $this->checkBrowserSilk() or $this->checkBrowserSafari() or
        // everyone else
        $this->checkBrowserNetPositive() or $this->checkBrowserFirebird() or $this->checkBrowserKonqueror() or $this->checkBrowserIcab() or $this->checkBrowserPhoenix() or $this->checkBrowserAmaya() or $this->checkBrowserLynx() or $this->checkBrowserShiretoko() or $this->checkBrowserIceCat() or $this->checkBrowserIceweasel() or $this->checkBrowserW3CValidator() or $this->checkBrowserCurl() or $this->checkBrowserWget() or $this->checkBrowserPlayStation() or $this->checkBrowserIframely() or $this->checkBrowserCocoa() or $this->checkBrowserMozilla() /* Mozilla is such an open standard that you must check it last */);
    }

    /**
     * Determine if the user is using a BlackBerry (last updated 1.7)
     *
     * @return boolean True if the browser is the BlackBerry browser otherwise false
     */
    protected function checkBrowserBlackBerry()
    {
        // User Agent in BlackBerry 6 and BlackBerry 7: Mozilla/5.0 (BlackBerry; U; BlackBerry AAAA; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/X.X.X.X Mobile Safari/534.11+
        // User Agent in BlackBerry Device Software 4.2 to 5.0: BlackBerry9000/5.0.0.93 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/179
        if (stripos($this->_agent, 'BlackBerry') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'BlackBerry'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_BLACKBERRY, self::BROWSER_BLACKBERRY_NAME);
                $this->setMobile(true);
                return true;
            }
        }
        // User Agent in BlackBerry Tablet OS: Mozilla/5.0 (PlayBook; U; RIM Tablet OS 2.0.0; en-US) AppleWebKit/535.8+ (KHTML, like Gecko) Version/7.2.0.0 Safari/535.8+
        if (stripos($this->_agent, 'PlayBook') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'PlayBook'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_BLACKBERRY, self::BROWSER_BLACKBERRY_NAME);
                $this->setTablet(true);
                return true;
            }
        }
        // User Agent in BlackBerry 10: Mozilla/5.0 (BB10; <Device Type>) AppleWebKit/537.10+ (KHTML, like Gecko) Version/<BB Version #> Mobile Safari/537.10+
        if (stripos($this->_agent, '(BB10;') !== false) {
            $aresult = explode('/', stristr($this->_agent, '(BB10;'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_BLACKBERRY, self::BROWSER_BLACKBERRY_NAME);
                $this->setMobile(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the user is using an AOL User Agent (last updated 1.7)
     *
     * @return boolean True if the browser is from AOL otherwise false
     */
    protected function checkForAol()
    {
        $this->setAol(false);
        $this->setAolVersion(self::VERSION_UNKNOWN);

        if (stripos($this->_agent, 'aol') !== false) {
            $aversion = explode(' ', stristr($this->_agent, 'AOL'));
            if (isset($aversion[1])) {
                $this->setAol(true);
                $this->setAolVersion(preg_replace('/[^0-9\.a-z]/i', '', $aversion[1]));
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the GoogleBot or not (last updated 1.7)
     *
     * @return boolean True if the browser is the GoogletBot otherwise false
     */
    protected function checkBrowserGoogleBot()
    {
        if (stripos($this->_agent, 'googlebot') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'googlebot'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_GOOGLEBOT, self::BROWSER_GOOGLEBOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    protected function checkBrowserCoccocBot()
    {
        if (stripos($this->_agent, 'coccocbot') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'coccocbot'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_COCCOCBOT, self::BROWSER_COCCOCBOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexBot or not
     *
     * @return boolean True if the browser is the YandexBot otherwise false
     */
    protected function checkBrowserYandexBot()
    {
        if (stripos($this->_agent, 'YandexBot') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexBot'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXBOT, self::BROWSER_YANDEXBOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexImageResizer or not
     *
     * @return boolean True if the browser is the YandexImageResizer otherwise false
     */
    protected function checkBrowserYandexImageResizerBot()
    {
        if (stripos($this->_agent, 'YandexImageResizer') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexImageResizer'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXIMAGERESIZER_BOT, self::BROWSER_YANDEXIMAGERESIZER_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexCatalog or not
     *
     * @return boolean True if the browser is the YandexCatalog otherwise false
     */
    protected function checkBrowserYandexCatalogBot()
    {
        if (stripos($this->_agent, 'YandexCatalog') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexCatalog'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXCATALOG_BOT, self::BROWSER_YANDEXCATALOG_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexNews or not
     *
     * @return boolean True if the browser is the YandexNews otherwise false
     */
    protected function checkBrowserYandexNewsBot()
    {
        if (stripos($this->_agent, 'YandexNews') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexNews'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXNEWS_BOT, self::BROWSER_YANDEXNEWS_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexMetrika or not
     *
     * @return boolean True if the browser is the YandexMetrika otherwise false
     */
    protected function checkBrowserYandexMetrikaBot()
    {
        if (stripos($this->_agent, 'YandexMetrika') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexMetrika'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXMETRIKA_BOT, self::BROWSER_YANDEXMETRIKA_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexDirect or not
     *
     * @return boolean True if the browser is the YandexDirect otherwise false
     */
    protected function checkBrowserYandexDirectBot()
    {
        if (stripos($this->_agent, 'YandexDirect') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexDirect'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXDIRECT_BOT, self::BROWSER_YANDEXDIRECT_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexWebmaster or not
     *
     * @return boolean True if the browser is the YandexWebmaster otherwise false
     */
    protected function checkBrowserYandexWebmasterBot()
    {
        if (stripos($this->_agent, 'YandexWebmaster') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexWebmaster'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXWEBMASTER_BOT, self::BROWSER_YANDEXWEBMASTER_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexFavicons or not
     *
     * @return boolean True if the browser is the YandexFavicons otherwise false
     */
    protected function checkBrowserYandexFaviconsBot()
    {
        if (stripos($this->_agent, 'YandexFavicons') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexFavicons'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXFAVICONS_BOT, self::BROWSER_YANDEXFAVICONS_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexBlogs or not
     *
     * @return boolean True if the browser is the YandexBlogs otherwise false
     */
    protected function checkBrowserYandexBlogsBot()
    {
        if (stripos($this->_agent, 'YandexBlogs') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexBlogs'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXBLOGS_BOT, self::BROWSER_YANDEXBLOGS_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexMedia or not
     *
     * @return boolean True if the browser is the YandexMedia otherwise false
     */
    protected function checkBrowserYandexMediaBot()
    {
        if (stripos($this->_agent, 'YandexMedia') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexMedia'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXMEDIA_BOT, self::BROWSER_YANDEXMEDIA_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexVideo or not
     *
     * @return boolean True if the browser is the YandexVideo otherwise false
     */
    protected function checkBrowserYandexVideoBot()
    {
        if (stripos($this->_agent, 'YandexVideo') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexVideo'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXVIDEO_BOT, self::BROWSER_YANDEXVIDEO_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexImages or not
     *
     * @return boolean True if the browser is the YandexImages otherwise false
     */
    protected function checkBrowserYandexImagesBot()
    {
        if (stripos($this->_agent, 'YandexImages') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexImages'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_YANDEXIMAGES_BOT, self::BROWSER_YANDEXIMAGES_BOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the MSNBot or not (last updated 1.9)
     *
     * @return boolean True if the browser is the MSNBot otherwise false
     */
    protected function checkBrowserMSNBot()
    {
        if (stripos($this->_agent, 'msnbot') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'msnbot'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_MSNBOT, self::BROWSER_MSNBOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the BingBot or not (last updated 1.9)
     *
     * @return boolean True if the browser is the BingBot otherwise false
     */
    protected function checkBrowserBingBot()
    {
        if (stripos($this->_agent, 'bingbot') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'bingbot'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->setBrowser(self::BROWSER_BINGBOT, self::BROWSER_BINGBOT_NAME);
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the W3C Validator or not (last updated 1.7)
     *
     * @return boolean True if the browser is the W3C Validator otherwise false
     */
    protected function checkBrowserW3CValidator()
    {
        if (stripos($this->_agent, 'W3C-checklink') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'W3C-checklink'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_W3CVALIDATOR, self::BROWSER_W3CVALIDATOR_NAME);
                return true;
            }
        } elseif (stripos($this->_agent, 'W3C_Validator') !== false) {
            // Some of the Validator versions do not delineate w/ a slash - add it back in
            $ua = str_replace('W3C_Validator ', 'W3C_Validator/', $this->_agent);
            $aresult = explode('/', stristr($ua, 'W3C_Validator'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_W3CVALIDATOR, self::BROWSER_W3CVALIDATOR_NAME);
                return true;
            }
        } elseif (stripos($this->_agent, 'W3C-mobileOK') !== false) {
            $this->setBrowser(self::BROWSER_W3CVALIDATOR, self::BROWSER_W3CVALIDATOR_NAME);
            $this->setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is the Yahoo! Slurp Robot or not (last updated 1.7)
     *
     * @return boolean True if the browser is the Yahoo! Slurp Robot otherwise false
     */
    protected function checkBrowserSlurp()
    {
        if (stripos($this->_agent, 'slurp') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Slurp'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_SLURP, self::BROWSER_SLURP_NAME);
                $this->setRobot(true);
                $this->setMobile(false);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Brave or not
     *
     * @return boolean True if the browser is Brave otherwise false
     */
    protected function checkBrowserBrave()
    {
        if (stripos($this->_agent, 'Brave/') !== false) {
            $aResult = explode('/', stristr($this->_agent, 'Brave'));
            if (isset($aResult[1])) {
                $aversion = explode(' ', $aResult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_BRAVE, self::BROWSER_BRAVE_NAME);
                return true;
            }
        } elseif (stripos($this->_agent, ' Brave ') !== false) {
            $this->setBrowser(self::BROWSER_BRAVE, self::BROWSER_BRAVE_NAME);
            // this version of the UA did not ship with a version marker
            // e.g. Mozilla/5.0 (Linux; Android 7.0; SM-G955F Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Brave Chrome/68.0.3440.91 Mobile Safari/537.36
            $this->setVersion('');
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Edge or not
     *
     * @return boolean True if the browser is Edge otherwise false
     */
    protected function checkBrowserEdge()
    {
        if ($name = (stripos($this->_agent, 'Edge/') !== false ? 'Edge' : ((stripos($this->_agent, 'Edg/') !== false || stripos($this->_agent, 'EdgA/') !== false) ? 'Edg' : false))) {
            $aresult = explode('/', stristr($this->_agent, $name));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_EDGE, self::BROWSER_EDGE_NAME);
                if (stripos($this->_agent, 'Windows Phone') !== false || stripos($this->_agent, 'Android') !== false) {
                    $this->setMobile(true);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Internet Explorer or not (last updated 1.7)
     *
     * @return boolean True if the browser is Internet Explorer otherwise false
     */
    protected function checkBrowserInternetExplorer()
    {
        // Test for IE11
        if (stripos($this->_agent, 'Trident/7.0; rv:11.0') !== false) {
            $this->setBrowser(self::BROWSER_IE, self::BROWSER_IE_NAME);
            $this->setVersion('11.0');
            return true;
        } // Test for v1 - v1.5 IE
        elseif (stripos($this->_agent, 'microsoft internet explorer') !== false) {
            $this->setBrowser(self::BROWSER_IE, self::BROWSER_IE_NAME);
            $this->setVersion('1.0');
            $aresult = stristr($this->_agent, '/');
            if (preg_match('/308|425|426|474|0b1/i', $aresult)) {
                $this->setVersion('1.5');
            }
            return true;
        } // Test for versions > 1.5
        elseif (stripos($this->_agent, 'msie') !== false and stripos($this->_agent, 'opera') === false) {
            // See if the browser is the odd MSN Explorer
            if (stripos($this->_agent, 'msnb') !== false) {
                $aresult = explode(' ', stristr(str_replace(';', '; ', $this->_agent), 'MSN'));
                if (isset($aresult[1])) {
                    $this->setBrowser(self::BROWSER_MSN, self::BROWSER_MSN_NAME);
                    $this->setVersion(str_replace([
                        '(',
                        ')',
                        ';'
                    ], '', $aresult[1]));
                    return true;
                }
            }
            $aresult = explode(' ', stristr(str_replace(';', '; ', $this->_agent), 'msie'));
            if (isset($aresult[1])) {
                $this->setBrowser(self::BROWSER_IE, self::BROWSER_IE_NAME);
                $this->setVersion(str_replace([
                    '(',
                    ')',
                    ';'
                ], '', $aresult[1]));
                if (stripos($this->_agent, 'IEMobile') !== false) {
                    $this->setBrowser(self::BROWSER_POCKET_IE, self::BROWSER_POCKET_IE_NAME);
                    $this->setMobile(true);
                }
                return true;
            }
        } // Test for versions > IE 10
        elseif (stripos($this->_agent, 'trident') !== false) {
            $this->setBrowser(self::BROWSER_IE, self::BROWSER_IE_NAME);
            $result = explode('rv:', $this->_agent);
            if (isset($result[1])) {
                $this->setVersion(preg_replace('/[^0-9.]+/', '', $result[1]));
                $this->_agent = str_replace([
                    'Mozilla',
                    'Gecko'
                ], 'MSIE', $this->_agent);
            }
        } // Test for Pocket IE
        elseif (stripos($this->_agent, 'mspie') !== false or stripos($this->_agent, 'pocket') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'mspie'));
            if (isset($aresult[1])) {
                $this->setPlatform(self::PLATFORM_WINDOWS_CE);
                $this->setBrowser(self::BROWSER_POCKET_IE, self::BROWSER_POCKET_IE_NAME);
                $this->setMobile(true);

                if (stripos($this->_agent, 'mspie') !== false) {
                    $this->setVersion($aresult[1]);
                } else {
                    $aversion = explode('/', $this->_agent);
                    if (isset($aversion[1])) {
                        $this->setVersion($aversion[1]);
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Opera or not (last updated 1.7)
     *
     * @return boolean True if the browser is Opera otherwise false
     */
    protected function checkBrowserOpera()
    {
        if (stripos($this->_agent, 'opera mini') !== false) {
            $resultant = stristr($this->_agent, 'opera mini');
            if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', $resultant);
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    $this->setVersion($aversion[0]);
                }
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera mini'));
                if (isset($aversion[1])) {
                    $this->setVersion($aversion[1]);
                }
            }
            $this->setBrowser(self::BROWSER_OPERA_MINI, self::BROWSER_OPERA_MINI_NAME);
            $this->setMobile(true);
            return true;
        } elseif (stripos($this->_agent, 'opera') !== false) {
            $resultant = stristr($this->_agent, 'opera');
            if (preg_match('/Version\/(1*.*)$/', $resultant, $matches)) {
                $this->setVersion($matches[1]);
            } elseif (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace('(', ' ', $resultant));
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    $this->setVersion($aversion[0]);
                }
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera'));
                $this->setVersion(isset($aversion[1]) ? $aversion[1] : '');
            }
            if (stripos($this->_agent, 'Opera Mobi') !== false) {
                $this->setMobile(true);
            }
            $this->setBrowser(self::BROWSER_OPERA, self::BROWSER_OPERA_NAME);
            return true;
        } elseif (stripos($this->_agent, 'OPR') !== false) {
            $resultant = stristr($this->_agent, 'OPR');
            if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace('(', ' ', $resultant));
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    $this->setVersion($aversion[0]);
                }
            }
            if (stripos($this->_agent, 'Mobile') !== false) {
                $this->setMobile(true);
            }
            $this->setBrowser(self::BROWSER_OPERA, self::BROWSER_OPERA_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Chrome or not (last updated 1.7)
     *
     * @return boolean True if the browser is Chrome otherwise false
     */
    protected function checkBrowserChrome()
    {
        if (stripos($this->_agent, 'Chrome') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Chrome'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                if (stripos($this->_agent, 'coc_coc') !== false) {
                    $this->setBrowser(self::BROWSER_COCCOC, self::BROWSER_COCCOC_NAME);
                } else {
                    $this->setBrowser(self::BROWSER_CHROME, self::BROWSER_CHROME_NAME);
                }
                // Chrome on Android
                if (stripos($this->_agent, 'Android') !== false) {
                    if (stripos($this->_agent, 'Mobile') !== false) {
                        $this->setMobile(true);
                    } else {
                        $this->setTablet(true);
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is WebTv or not (last updated 1.7)
     *
     * @return boolean True if the browser is WebTv otherwise false
     */
    protected function checkBrowserWebTv()
    {
        if (stripos($this->_agent, 'webtv') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'webtv'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_WEBTV, self::BROWSER_WEBTV_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is NetPositive or not (last updated 1.7)
     *
     * @return boolean True if the browser is NetPositive otherwise false
     */
    protected function checkBrowserNetPositive()
    {
        if (stripos($this->_agent, 'NetPositive') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'NetPositive'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace([
                    '(',
                    ')',
                    ';'
                ], '', $aversion[0]));
                $this->setBrowser(self::BROWSER_NETPOSITIVE, self::BROWSER_NETPOSITIVE_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Galeon or not (last updated 1.7)
     *
     * @return boolean True if the browser is Galeon otherwise false
     */
    protected function checkBrowserGaleon()
    {
        if (stripos($this->_agent, 'galeon') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'galeon'));
            $aversion = explode('/', $aresult[0]);
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_GALEON, self::BROWSER_GALEON_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Konqueror or not (last updated 1.7)
     *
     * @return boolean True if the browser is Konqueror otherwise false
     */
    protected function checkBrowserKonqueror()
    {
        if (stripos($this->_agent, 'Konqueror') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'Konqueror'));
            $aversion = explode('/', $aresult[0]);
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_KONQUEROR, self::BROWSER_KONQUEROR_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is iCab or not (last updated 1.7)
     *
     * @return boolean True if the browser is iCab otherwise false
     */
    protected function checkBrowserIcab()
    {
        if (stripos($this->_agent, 'icab') !== false) {
            $aversion = explode(' ', stristr(str_replace('/', ' ', $this->_agent), 'icab'));
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_ICAB, self::BROWSER_ICAB_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is OmniWeb or not (last updated 1.7)
     *
     * @return boolean True if the browser is OmniWeb otherwise false
     */
    protected function checkBrowserOmniWeb()
    {
        if (stripos($this->_agent, 'omniweb') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'omniweb'));
            $aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : '');
            $this->setVersion($aversion[0]);
            $this->setBrowser(self::BROWSER_OMNIWEB, self::BROWSER_OMNIWEB_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Phoenix or not (last updated 1.7)
     *
     * @return boolean True if the browser is Phoenix otherwise false
     */
    protected function checkBrowserPhoenix()
    {
        if (stripos($this->_agent, 'Phoenix') !== false) {
            $aversion = explode('/', stristr($this->_agent, 'Phoenix'));
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_PHOENIX, self::BROWSER_PHOENIX_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Firebird or not (last updated 1.7)
     *
     * @return boolean True if the browser is Firebird otherwise false
     */
    protected function checkBrowserFirebird()
    {
        if (stripos($this->_agent, 'Firebird') !== false) {
            $aversion = explode('/', stristr($this->_agent, 'Firebird'));
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_FIREBIRD, self::BROWSER_FIREBIRD_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Netscape Navigator 9+ or not (last updated 1.7)
     * NOTE: (http://browser.netscape.com/ - Official support ended on March 1st, 2008)
     *
     * @return boolean True if the browser is Netscape Navigator 9+ otherwise false
     */
    protected function checkBrowserNetscapeNavigator9Plus()
    {
        if (stripos($this->_agent, 'Firefox') !== false and preg_match('/Navigator\/([^ ]*)/i', $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR, self::BROWSER_NETSCAPE_NAVIGATOR_NAME);
            return true;
        } elseif (stripos($this->_agent, 'Firefox') === false and preg_match('/Netscape6?\/([^ ]*)/i', $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR, self::BROWSER_NETSCAPE_NAVIGATOR_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Shiretoko or not (https://wiki.mozilla.org/Projects/shiretoko) (last updated 1.7)
     *
     * @return boolean True if the browser is Shiretoko otherwise false
     */
    protected function checkBrowserShiretoko()
    {
        if (stripos($this->_agent, 'Mozilla') !== false and preg_match('/Shiretoko\/([^ ]*)/i', $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_SHIRETOKO, self::BROWSER_SHIRETOKO_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Ice Cat or not (http://en.wikipedia.org/wiki/GNU_IceCat) (last updated 1.7)
     *
     * @return boolean True if the browser is Ice Cat otherwise false
     */
    protected function checkBrowserIceCat()
    {
        if (stripos($this->_agent, 'Mozilla') !== false and preg_match('/IceCat\/([^ ]*)/i', $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_ICECAT, self::BROWSER_ICECAT_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Nokia or not (last updated 1.7)
     *
     * @return boolean True if the browser is Nokia otherwise false
     */
    protected function checkBrowserNokia()
    {
        if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", $this->_agent, $matches)) {
            $this->setVersion($matches[2]);
            if (stripos($this->_agent, 'Series60') !== false or strpos($this->_agent, 'S60') !== false) {
                $this->setBrowser(self::BROWSER_NOKIA_S60, self::BROWSER_NOKIA_S60_NAME);
            } else {
                $this->setBrowser(self::BROWSER_NOKIA, self::BROWSER_NOKIA_NAME);
            }
            $this->setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Palemoon or not
     *
     * @return boolean True if the browser is Palemoon otherwise false
     */
    protected function checkBrowserPalemoon()
    {
        if (stripos($this->_agent, 'safari') === false) {
            if (preg_match("/Palemoon[\/ \(]([^ ;\)]+)/i", $this->_agent, $matches)) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_PALEMOON, self::BROWSER_PALEMOON_NAME);
                return true;
            } else if (preg_match("/Palemoon([0-9a-zA-Z\.]+)/i", $this->_agent, $matches)) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_PALEMOON, self::BROWSER_PALEMOON_NAME);
                return true;
            } else if (preg_match("/Palemoon/i", $this->_agent, $matches)) {
                $this->setVersion('');
                $this->setBrowser(self::BROWSER_PALEMOON, self::BROWSER_PALEMOON_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is UCBrowser or not
     *
     * @return boolean True if the browser is UCBrowser otherwise false
     */
    protected function checkBrowserUCBrowser()
    {
        if (preg_match('/UC ?Browser\/?([\d\.]+)/', $this->_agent, $matches)) {
            if (isset($matches[1])) {
                $this->setVersion($matches[1]);
            }
            if (stripos($this->_agent, 'Mobile') !== false) {
                $this->setMobile(true);
            } else {
                $this->setTablet(true);
            }
            $this->setBrowser(self::BROWSER_UCBROWSER, self::BROWSER_UCBROWSER_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Firefox or not
     *
     * @return boolean True if the browser is Firefox otherwise false
     */
    protected function checkBrowserFirefox()
    {
        if (stripos($this->_agent, 'safari') === false) {
            if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i", $this->_agent, $matches)) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_FIREFOX, self::BROWSER_FIREFOX_NAME);
                // Firefox on Android
                if (stripos($this->_agent, 'Android') !== false || stripos($this->_agent, 'iPhone') !== false) {
                    if (stripos($this->_agent, 'Mobile') !== false || stripos($this->_agent, 'Tablet') !== false) {
                        $this->setMobile(true);
                    } else {
                        $this->setTablet(true);
                    }
                }
                return true;
            } else if (preg_match("/Firefox([0-9a-zA-Z\.]+)/i", $this->_agent, $matches)) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_FIREFOX, self::BROWSER_FIREFOX_NAME);
                return true;
            } else if (preg_match("/Firefox$/i", $this->_agent, $matches)) {
                $this->setVersion('');
                $this->setBrowser(self::BROWSER_FIREFOX, self::BROWSER_FIREFOX_NAME);
                return true;
            }
        } elseif (preg_match("/FxiOS[\/ \(]([^ ;\)]+)/i", $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_FIREFOX, self::BROWSER_FIREFOX_NAME);
            // Firefox on Android
            if (stripos($this->_agent, 'Android') !== false || stripos($this->_agent, 'iPhone') !== false) {
                if (stripos($this->_agent, 'Mobile') !== false || stripos($this->_agent, 'Tablet') !== false) {
                    $this->setMobile(true);
                } else {
                    $this->setTablet(true);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Firefox or not (last updated 1.7)
     *
     * @return boolean True if the browser is Firefox otherwise false
     */
    protected function checkBrowserIceweasel()
    {
        if (stripos($this->_agent, 'Iceweasel') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Iceweasel'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_ICEWEASEL, self::BROWSER_ICEWEASEL_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Mozilla or not (last updated 1.7)
     *
     * @return boolean True if the browser is Mozilla otherwise false
     */
    protected function checkBrowserMozilla()
    {
        if (stripos($this->_agent, 'mozilla') !== false and preg_match('/rv:[0-9].[0-9][a-b]?/i', $this->_agent) and stripos($this->_agent, 'netscape') === false) {
            $aversion = explode(' ', stristr($this->_agent, 'rv:'));
            preg_match('/rv:[0-9].[0-9][a-b]?/i', $this->_agent, $aversion);
            $this->setVersion(str_replace('rv:', '', $aversion[0]));
            $this->setBrowser(self::BROWSER_MOZILLA, self::BROWSER_MOZILLA_NAME);
            return true;
        } elseif (stripos($this->_agent, 'mozilla') !== false and preg_match('/rv:[0-9]\.[0-9]/i', $this->_agent) and stripos($this->_agent, 'netscape') === false) {
            $aversion = explode('', stristr($this->_agent, 'rv:'));
            $this->setVersion(str_replace('rv:', '', $aversion[0]));
            $this->setBrowser(self::BROWSER_MOZILLA, self::BROWSER_MOZILLA_NAME);
            return true;
        } elseif (stripos($this->_agent, 'mozilla') !== false and preg_match('/mozilla\/([^ ]*)/i', $this->_agent, $matches) and stripos($this->_agent, 'netscape') === false) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_MOZILLA, self::BROWSER_MOZILLA_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Lynx or not (last updated 1.7)
     *
     * @return boolean True if the browser is Lynx otherwise false
     */
    protected function checkBrowserLynx()
    {
        if (stripos($this->_agent, 'lynx') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Lynx'));
            $aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ''));
            $this->setVersion($aversion[0]);
            $this->setBrowser(self::BROWSER_LYNX, self::BROWSER_LYNX_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Amaya or not (last updated 1.7)
     *
     * @return boolean True if the browser is Amaya otherwise false
     */
    protected function checkBrowserAmaya()
    {
        if (stripos($this->_agent, 'amaya') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Amaya'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_AMAYA, self::BROWSER_AMAYA_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Safari or not (last updated 1.7)
     *
     * @return boolean True if the browser is Safari otherwise false
     */
    protected function checkBrowserSafari()
    {
        if (stripos($this->_agent, 'Safari') !== false and stripos($this->_agent, 'iPhone') === false and stripos($this->_agent, 'iPod') === false) {
            $aresult = explode('/', stristr($this->_agent, 'Version'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_SAFARI, self::BROWSER_SAFARI_NAME);
            return true;
        }
        return false;
    }

    protected function checkBrowserSamsung()
    {
        if (stripos($this->_agent, 'SamsungBrowser') !== false) {

            $aresult = explode('/', stristr($this->_agent, 'SamsungBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_SAMSUNG, self::BROWSER_SAMSUNG_NAME);
            return true;
        }
        return false;
    }

    protected function checkBrowserSilk()
    {
        if (stripos($this->_agent, 'Silk') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Silk'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_SILK, self::BROWSER_SILK_NAME);
            return true;
        }
        return false;
    }

    protected function checkBrowserIframely()
    {
        if (stripos($this->_agent, 'Iframely') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Iframely'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_I_FRAME, self::BROWSER_I_FRAME_NAME);
            return true;
        }
        return false;
    }

    protected function checkBrowserCocoa()
    {
        if (stripos($this->_agent, 'CocoaRestClient') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'CocoaRestClient'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_COCOA, self::BROWSER_COCOA_NAME);
            return true;
        }
        return false;
    }

    /**
     * Detect if URL is loaded from FacebookExternalHit
     *
     * @return boolean True if it detects FacebookExternalHit otherwise false
     */
    protected function checkFacebookExternalHit()
    {
        if (stristr($this->_agent, 'FacebookExternalHit')) {
            $this->setRobot(true);
            $this->setFacebook(true);
            return true;
        }
        return false;
    }

    /**
     * Detect if URL is being loaded from internal Facebook browser
     *
     * @return boolean True if it detects internal Facebook browser otherwise false
     */
    protected function checkForFacebookIos()
    {
        if (stristr($this->_agent, 'FBIOS')) {
            $this->setFacebook(true);
            return true;
        }
        return false;
    }

    /**
     * Detect Version for the Safari browser on iOS devices
     *
     * @return boolean True if it detects the version correctly otherwise false
     */
    protected function getSafariVersionOnIos()
    {
        $aresult = explode('/', stristr($this->_agent, 'Version'));
        if (isset($aresult[1])) {
            $aversion = explode(' ', $aresult[1]);
            $this->setVersion($aversion[0]);
            return true;
        }
        return false;
    }

    /**
     * Detect Version for the Chrome browser on iOS devices
     *
     * @return boolean True if it detects the version correctly otherwise false
     */
    protected function getChromeVersionOnIos()
    {
        $aresult = explode('/', stristr($this->_agent, 'CriOS'));
        if (isset($aresult[1])) {
            $aversion = explode(' ', $aresult[1]);
            $this->setVersion($aversion[0]);
            $this->setBrowser(self::BROWSER_CHROME, self::BROWSER_CHROME_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is iPhone or not (last updated 1.7)
     *
     * @return boolean True if the browser is iPhone otherwise false
     */
    protected function checkBrowseriPhone()
    {
        if (stripos($this->_agent, 'iPhone') !== false) {
            $this->setVersion(self::VERSION_UNKNOWN);
            $this->setBrowser(self::BROWSER_IPHONE, self::BROWSER_IPHONE_NAME);
            $this->getSafariVersionOnIos();
            $this->getChromeVersionOnIos();
            $this->checkForFacebookIos();
            $this->setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is iPad or not (last updated 1.7)
     *
     * @return boolean True if the browser is iPad otherwise false
     */
    protected function checkBrowseriPad()
    {
        if (stripos($this->_agent, 'iPad') !== false) {
            $this->setVersion(self::VERSION_UNKNOWN);
            $this->setBrowser(self::BROWSER_IPAD, self::BROWSER_IPAD_NAME);
            $this->getSafariVersionOnIos();
            $this->getChromeVersionOnIos();
            $this->checkForFacebookIos();
            $this->setTablet(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is iPod or not (last updated 1.7)
     *
     * @return boolean True if the browser is iPod otherwise false
     */
    protected function checkBrowseriPod()
    {
        if (stripos($this->_agent, 'iPod') !== false) {
            $this->setVersion(self::VERSION_UNKNOWN);
            $this->setBrowser(self::BROWSER_IPOD, self::BROWSER_IPOD_NAME);
            $this->getSafariVersionOnIos();
            $this->getChromeVersionOnIos();
            $this->checkForFacebookIos();
            $this->setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Android or not (last updated 1.7)
     *
     * @return boolean True if the browser is Android otherwise false
     */
    protected function checkBrowserAndroid()
    {
        if (stripos($this->_agent, 'Android') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'Android'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            if (stripos($this->_agent, 'Mobile') !== false) {
                $this->setMobile(true);
            } else {
                $this->setTablet(true);
            }
            $this->setBrowser(self::BROWSER_ANDROID, self::BROWSER_ANDROID_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Vivaldi
     *
     * @return boolean True if the browser is Vivaldi otherwise false
     */
    protected function checkBrowserVivaldi()
    {
        if (stripos($this->_agent, 'Vivaldi') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Vivaldi'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_VIVALDI, self::BROWSER_VIVALDI_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Yandex
     *
     * @return boolean True if the browser is Yandex otherwise false
     */
    protected function checkBrowserYandex()
    {
        if (stripos($this->_agent, 'YaBrowser') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YaBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_YANDEX, self::BROWSER_YANDEX_NAME);

                if (stripos($this->_agent, 'iPad') !== false) {
                    $this->setTablet(true);
                } elseif (stripos($this->_agent, 'Mobile') !== false) {
                    $this->setMobile(true);
                } elseif (stripos($this->_agent, 'Android') !== false) {
                    $this->setTablet(true);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the browser is a PlayStation
     *
     * @return boolean True if the browser is PlayStation otherwise false
     */
    protected function checkBrowserPlayStation()
    {
        if (stripos($this->_agent, 'PlayStation ') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'PlayStation '));
            $this->setBrowser(self::BROWSER_PLAYSTATION, self::BROWSER_PLAYSTATION_NAME);
            if (isset($aresult[0])) {
                $aversion = explode(')', $aresult[2]);
                $this->setVersion($aversion[0]);
                if (stripos($this->_agent, 'Portable)') !== false || stripos($this->_agent, 'Vita') !== false) {
                    $this->setMobile(true);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Wget or not (last updated 1.7)
     *
     * @return boolean True if the browser is Wget otherwise false
     */
    protected function checkBrowserWget()
    {
        if (preg_match("!^Wget/([^ ]+)!i", $this->_agent, $aresult)) {
            $this->setVersion($aresult[1]);
            $this->setBrowser(self::BROWSER_WGET, self::BROWSER_WGET_NAME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is cURL or not (last updated 1.7)
     *
     * @return boolean True if the browser is cURL otherwise false
     */
    protected function checkBrowserCurl()
    {
        if (strpos($this->_agent, 'curl') === 0) {
            $aresult = explode('/', stristr($this->_agent, 'curl'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_CURL, self::BROWSER_CURL_NAME);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine the user's platform (last updated 1.7)
     */
    protected function checkPlatform()
    {
        if (stripos($this->_agent, 'win') !== false) {
            $this->_platform = self::PLATFORM_WINDOWS;
            $this->_platform_family = self::PLATFORM_WINDOWS;
            if (preg_match("/wi(n|ndows)[ \-]?nt[ \/]?6\.(2|3)/i", $this->_agent)) {
                $this->_platform = self::PLATFORM_WINDOWS_8;
            } elseif (preg_match("/wi(n|ndows)[ \-]?nt[ \/]?10\.0/i", $this->_agent)) {
                $this->_platform = self::PLATFORM_WINDOWS_10;
            } elseif (preg_match("/wi(n|ndows)[ \-]?nt[ \/]?6\.1/i", $this->_agent)) {
                $this->_platform = self::PLATFORM_WINDOWS_7;
            } elseif (preg_match("/wi(n|ndows)[ \-]?nt[ \/]?6\.0/i", $this->_agent)) {
                $this->_platform = self::PLATFORM_WINDOWS_VISTA;
            } elseif (preg_match("/wi(n|ndows)[ \-]?(2003|nt[ \/]?5\\.2)/i", $this->_agent)) {
                $this->_platform = self::PLATFORM_WINDOWS_2003;
            } elseif (preg_match("/windows xp/i", $this->_agent) or preg_match("/wi(n|ndows)[ \-]?nt[ \/]?5\.1/i", $this->_agent)) {
                $this->_platform = self::PLATFORM_WINDOWS_XP;
            } elseif (preg_match("/wi(n|ndows)[ \-]?ce/i", $this->_agent)) {
                $this->_platform = self::PLATFORM_WINDOWS_CE;
            }
        } elseif (stripos($this->_agent, 'iPad') !== false) {
            $this->_platform = self::PLATFORM_IPAD;
            $this->_platform_family = self::PLATFORM_APPLE;
        } elseif (stripos($this->_agent, 'iPod') !== false) {
            $this->_platform = self::PLATFORM_IPOD;
            $this->_platform_family = self::PLATFORM_APPLE;
        } elseif (stripos($this->_agent, 'iPhone') !== false) {
            $this->_platform = self::PLATFORM_IPHONE;
            $this->_platform_family = self::PLATFORM_APPLE;
        } elseif (stripos($this->_agent, 'mac') !== false) {
            $this->_platform = self::PLATFORM_APPLE;
            $this->_platform_family = self::PLATFORM_APPLE;
        } elseif (stripos($this->_agent, 'android') !== false) {
            $this->_platform = self::PLATFORM_ANDROID;
            $this->_platform_family = self::PLATFORM_ANDROID;
        } elseif (stripos($this->_agent, 'Silk') !== false) {
            $this->_platform = self::PLATFORM_FIRE_OS;
            $this->_platform_family = self::PLATFORM_ANDROID;
        } elseif (stripos($this->_agent, 'linux') !== false && stripos($this->_agent, 'SMART-TV') !== false) {
            $this->_platform = self::PLATFORM_SMART_TV;
            $this->_platform_family = self::PLATFORM_LINUX;
        } elseif (stripos($this->_agent, 'linux') !== false or preg_match("/mdk for ([0-9.]{1,10})/i", $this->_agent)) {
            $this->_platform = self::PLATFORM_LINUX;
            $this->_platform_family = self::PLATFORM_LINUX;
        } elseif (stripos($this->_agent, 'Nokia') !== false) {
            $this->_platform = self::PLATFORM_NOKIA;
            $this->_platform_family = self::PLATFORM_NOKIA;
        } elseif (stripos($this->_agent, 'BlackBerry') !== false) {
            $this->_platform = self::PLATFORM_BLACKBERRY;
            $this->_platform_family = self::PLATFORM_BLACKBERRY;
        } elseif (stripos($this->_agent, 'PlayBook') !== false) {
            $this->_platform = self::PLATFORM_BLACKBERRY;
            $this->_platform_family = self::PLATFORM_BLACKBERRY;
        } elseif (stripos($this->_agent, 'BB10') !== false) {
            $this->_platform = self::PLATFORM_BLACKBERRY;
            $this->_platform_family = self::PLATFORM_BLACKBERRY;
        } elseif (stripos($this->_agent, 'FreeBSD') !== false) {
            $this->_platform = self::PLATFORM_FREEBSD;
            $this->_platform_family = self::PLATFORM_FREEBSD;
        } elseif (stripos($this->_agent, 'OpenBSD') !== false) {
            $this->_platform = self::PLATFORM_OPENBSD;
            $this->_platform_family = self::PLATFORM_OPENBSD;
        } elseif (stripos($this->_agent, 'NetBSD') !== false) {
            $this->_platform = self::PLATFORM_NETBSD;
            $this->_platform_family = self::PLATFORM_NETBSD;
        } elseif (stripos($this->_agent, 'OpenSolaris') !== false) {
            $this->_platform = self::PLATFORM_OPENSOLARIS;
            $this->_platform_family = self::PLATFORM_OPENSOLARIS;
        } elseif (stripos($this->_agent, 'SunOS') !== false) {
            $this->_platform = self::PLATFORM_SUNOS;
            $this->_platform_family = self::PLATFORM_SUNOS;
        } elseif (stripos($this->_agent, 'OS\/2') !== false or preg_match("/warp[ \/]?([0-9.]{1,10})/i", $this->_agent)) {
            $this->_platform = self::PLATFORM_OS2;
            $this->_platform_family = self::PLATFORM_OS2;
        } elseif (stripos($this->_agent, 'BeOS') !== false) {
            $this->_platform = self::PLATFORM_BEOS;
            $this->_platform_family = self::PLATFORM_BEOS;
        } elseif (stripos($this->_agent, 'irix') !== false) {
            $this->_platform = self::PLATFORM_IRIX;
            $this->_platform_family = self::PLATFORM_IRIX;
        } elseif (stripos($this->_agent, 'Palm') !== false) {
            $this->_platform = self::PLATFORM_PALM;
            $this->_platform_family = self::PLATFORM_PALM;
        } elseif (stripos($this->_agent, 'Playstation') !== false) {
            $this->_platform = self::PLATFORM_PLAYSTATION;
            $this->_platform_family = self::PLATFORM_PLAYSTATION;
        } elseif (stripos($this->_agent, 'Roku') !== false) {
            $this->_platform = self::PLATFORM_ROKU;
            $this->_platform_family = self::PLATFORM_ROKU;
        } elseif (stripos($this->_agent, 'iOS') !== false) {
            $this->_platform = self::PLATFORM_IPAD;
            $this->_platform_family = self::PLATFORM_APPLE;
        } elseif (stripos($this->_agent, 'tvOS') !== false) {
            $this->_platform = self::PLATFORM_APPLE_TV;
            $this->_platform_family = self::PLATFORM_APPLE;
        } elseif (stripos($this->_agent, 'curl') !== false) {
            $this->_platform = self::PLATFORM_TERMINAL;
            $this->_platform_family = self::PLATFORM_TERMINAL;
        } elseif (stripos($this->_agent, 'CrOS') !== false) {
            $this->_platform = self::PLATFORM_CHROME_OS;
            $this->_platform_family = self::PLATFORM_CHROME_OS;
        } elseif (stripos($this->_agent, 'okhttp') !== false) {
            $this->_platform = self::PLATFORM_JAVA_ANDROID;
            $this->_platform_family = self::PLATFORM_ANDROID;
        } elseif (stripos($this->_agent, 'PostmanRuntime') !== false) {
            $this->_platform = self::PLATFORM_POSTMAN;
            $this->_platform_family = self::PLATFORM_POSTMAN;
        } elseif (stripos($this->_agent, 'Iframely') !== false) {
            $this->_platform = self::PLATFORM_I_FRAME;
            $this->_platform_family = self::PLATFORM_I_FRAME;
        }
    }
}
