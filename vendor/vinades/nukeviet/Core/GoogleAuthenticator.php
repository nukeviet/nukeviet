<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

/**
 * NukeViet\Core\GoogleAuthenticator
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class GoogleAuthenticator
{
    private $secretLength = 16;
    private $optLength = 6;

    /**
     * __construct()
     *
     * @param int $secretLength
     * @param int $optLength
     */
    public function __construct($secretLength = 16, $optLength = 6)
    {
        $secretLength = (int) $secretLength;
        $optLength = (int) $optLength;

        if ($secretLength > 0) {
            $this->secretLength = $secretLength;
        }
        if ($optLength > 0) {
            $this->optLength = $optLength;
        }
    }

    /**
     * verifyOpt()
     *
     * @param string $secretkey
     * @param mixed  $opt
     * @return bool
     */
    public function verifyOpt($secretkey, $opt)
    {
        $timeSlice = floor(time() / 30);
        // Check realtime code and 30sec code before
        for ($i = -1; $i <= 0; ++$i) {
            $trueCode = $this->getTrueCode($secretkey, $timeSlice + $i);
            if ($trueCode === $opt) {
                return true;
            }
        }

        return false;
    }

    /**
     * creatSecretkey()
     *
     * @return string
     */
    public function creatSecretkey()
    {
        $secret = '';
        $validChars = $this->getTable();
        unset($validChars[32]);

        for ($i = 0; $i < $this->secretLength; ++$i) {
            $secret .= $validChars[array_rand($validChars)];
        }

        return $secret;
    }

    /**
     * getTrueCode()
     *
     * @param string     $secret
     * @param mixed|null $timeSlice
     * @return string
     */
    private function getTrueCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretkey = $this->decode($secret);
        $time = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timeSlice);
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        $offset = ord(substr($hm, -1)) & 0x0F;
        $hashpart = substr($hm, $offset, 4);
        $value = unpack('N', $hashpart);
        $value = $value[1];
        $value = $value & 0x7FFFFFFF;
        $modulo = pow(10, $this->optLength);

        return str_pad($value % $modulo, $this->optLength, '0', STR_PAD_LEFT);
    }

    /**
     * encode()
     *
     * @param string $secret
     * @param bool   $padding
     * @return string
     */
    private function encode($secret, $padding = true)
    {
        if (empty($secret)) {
            return '';
        }

        $validChars = $this->getTable();
        $secret = str_split($secret);
        $binaryString = '';

        for ($i = 0; $i < count($secret); ++$i) {
            $binaryString .= str_pad(base_convert(ord($secret[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
        }

        $fiveBitBinaryArray = str_split($binaryString, 5);
        $base32 = '';

        $i = 0;
        while ($i < count($fiveBitBinaryArray)) {
            $base32 .= $validChars[base_convert(str_pad($fiveBitBinaryArray[$i], 5, '0'), 2, 10)];
            ++$i;
        }

        if ($padding and ($x = strlen($binaryString) % 40) != 0) {
            if ($x == 8) {
                $base32 .= str_repeat($validChars[32], 6);
            } elseif ($x == 16) {
                $base32 .= str_repeat($validChars[32], 4);
            } elseif ($x == 24) {
                $base32 .= str_repeat($validChars[32], 3);
            } elseif ($x == 32) {
                $base32 .= $validChars[32];
            }
        }

        return $base32;
    }

    /**
     * decode()
     *
     * @param string $secret
     * @return false|string
     */
    private function decode($secret)
    {
        if (empty($secret)) {
            return '';
        }

        $validChars = $this->getTable();
        $validCharsFlipped = array_flip($validChars);
        $paddingCharCount = substr_count($secret, $validChars[32]);
        $allowedValues = [6, 4, 3, 1, 0];

        if (!in_array($paddingCharCount, $allowedValues, true)) {
            return false;
        }

        for ($i = 0; $i < 4; ++$i) {
            if ($paddingCharCount == $allowedValues[$i] and substr($secret, -($allowedValues[$i])) != str_repeat($validChars[32], $allowedValues[$i])) {
                return false;
            }
        }

        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);

        $binaryString = '';
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = '';
            if (!in_array($secret[$i], $validChars, true)) {
                return false;
            }

            for ($j = 0; $j < 8; ++$j) {
                $x .= str_pad(base_convert(@$validCharsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }

            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); ++$z) {
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
            }
        }

        return $binaryString;
    }

    /**
     * getTable()
     *
     * @return array
     */
    private function getTable()
    {
        return [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', // 7
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 24
            'Y', 'Z', '2', '3', '4', '5', '6', '7', // 32
            '='
        ];
    }
}
