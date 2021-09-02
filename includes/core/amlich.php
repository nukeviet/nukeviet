<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * jdFromDate()
 *
 * @param int $dd
 * @param int $mm
 * @param int $yy
 * @return float
 */
function jdFromDate($dd, $mm, $yy)
{
    $a = floor((14 - $mm) / 12);
    $y = $yy + 4800 - $a;
    $m = $mm + 12 * $a - 3;
    $jd = $dd + floor((153 * $m + 2) / 5) + 365 * $y + floor($y / 4) - floor($y / 100) + floor($y / 400) - 32045;
    if ($jd < 2299161) {
        $jd = $dd + floor((153 * $m + 2) / 5) + 365 * $y + floor($y / 4) - 32083;
    }

    return $jd;
}

/**
 * jdToDate()
 *
 * @param string $jd
 * @return array
 */
function jdToDate($jd)
{
    if ($jd > 2299160) {
        $a = $jd + 32044;
        $b = floor((4 * $a + 3) / 146097);
        $c = $a - floor(($b * 146097) / 4);
    } else {
        $b = 0;
        $c = $jd + 32082;
    }
    $d = floor((4 * $c + 3) / 1461);
    $e = $c - floor((1461 * $d) / 4);
    $m = floor((5 * $e + 2) / 153);
    $day = $e - floor((153 * $m + 2) / 5) + 1;
    $month = $m + 3 - 12 * floor($m / 10);
    $year = $b * 100 + $d - 4800 + floor($m / 10);

    return [$day, $month, $year];
}

/**
 * getNewMoonDay()
 *
 * @param int $k
 * @param int $timeZone
 * @return float
 */
function getNewMoonDay($k, $timeZone)
{
    $T = $k / 1236.85;

    $T2 = $T * $T;
    $T3 = $T2 * $T;
    $dr = M_PI / 180;
    $Jd1 = 2415020.75933 + 29.53058868 * $k + 0.0001178 * $T2 - 0.000000155 * $T3;
    $Jd1 = $Jd1 + 0.00033 * sin((166.56 + 132.87 * $T - 0.009173 * $T2) * $dr);

    $M = 359.2242 + 29.10535608 * $k - 0.0000333 * $T2 - 0.00000347 * $T3;

    $Mpr = 306.0253 + 385.81691806 * $k + 0.0107306 * $T2 + 0.00001236 * $T3;

    $F = 21.2964 + 390.67050646 * $k - 0.0016528 * $T2 - 0.00000239 * $T3;

    $C1 = (0.1734 - 0.000393 * $T) * sin($M * $dr) + 0.0021 * sin(2 * $dr * $M);
    $C1 = $C1 - 0.4068 * sin($Mpr * $dr) + 0.0161 * sin($dr * 2 * $Mpr);
    $C1 = $C1 - 0.0004 * sin($dr * 3 * $Mpr);
    $C1 = $C1 + 0.0104 * sin($dr * 2 * $F) - 0.0051 * sin($dr * ($M + $Mpr));
    $C1 = $C1 - 0.0074 * sin($dr * ($M - $Mpr)) + 0.0004 * sin($dr * (2 * $F + $M));
    $C1 = $C1 - 0.0004 * sin($dr * (2 * $F - $M)) - 0.0006 * sin($dr * (2 * $F + $Mpr));
    $C1 = $C1 + 0.0010 * sin($dr * (2 * $F - $Mpr)) + 0.0005 * sin($dr * (2 * $Mpr + $M));
    if ($T < -11) {
        $deltat = 0.001 + 0.000839 * $T + 0.0002261 * $T2 - 0.00000845 * $T3 - 0.000000081 * $T * $T3;
    } else {
        $deltat = -0.000278 + 0.000265 * $T + 0.000262 * $T2;
    }
    $JdNew = $Jd1 + $C1 - $deltat;

    return floor($JdNew + 0.5 + $timeZone / 24);
}

/**
 * getSunLongitude()
 *
 * @param int $jdn
 * @param int $timeZone
 * @return float
 */
function getSunLongitude($jdn, $timeZone)
{
    $T = ($jdn - 2451545.5 - $timeZone / 24) / 36525;

    $T2 = $T * $T;
    $dr = M_PI / 180;

    $M = 357.52910 + 35999.05030 * $T - 0.0001559 * $T2 - 0.00000048 * $T * $T2;

    $L0 = 280.46645 + 36000.76983 * $T + 0.0003032 * $T2;

    $DL = (1.914600 - 0.004817 * $T - 0.000014 * $T2) * sin($dr * $M);
    $DL = $DL + (0.019993 - 0.000101 * $T) * sin($dr * 2 * $M) + 0.000290 * sin($dr * 3 * $M);
    $L = $L0 + $DL;

    $L = $L * $dr;
    $L = $L - M_PI * 2 * (floor($L / (M_PI * 2)));

    return floor($L / M_PI * 6);
}

/**
 * getLunarMonth11()
 *
 * @param int $yy
 * @param int $timeZone
 * @return float
 */
function getLunarMonth11($yy, $timeZone)
{
    $off = jdFromDate(31, 12, $yy) - 2415021;
    $k = floor($off / 29.530588853);
    $nm = getNewMoonDay($k, $timeZone);
    $sunLong = getSunLongitude($nm, $timeZone);

    if ($sunLong >= 9) {
        $nm = getNewMoonDay($k - 1, $timeZone);
    }

    return $nm;
}

/**
 * getLeapMonthOffset()
 *
 * @param int $a11
 * @param int $timeZone
 * @return int
 */
function getLeapMonthOffset($a11, $timeZone)
{
    $k = floor(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
    $last = 0;
    $i = 1;

    $arc = getSunLongitude(getNewMoonDay($k + $i, $timeZone), $timeZone);
    do {
        $last = $arc;
        $i = $i + 1;
        $arc = getSunLongitude(getNewMoonDay($k + $i, $timeZone), $timeZone);
    } while ($arc != $last and $i < 14);

    return $i - 1;
}

/**
 * convertSolar2Lunar()
 *
 * @param int $dd
 * @param int $mm
 * @param int $yy
 * @param int $timeZone
 * @return array
 */
function convertSolar2Lunar($dd, $mm, $yy, $timeZone)
{
    $dayNumber = jdFromDate($dd, $mm, $yy);
    $k = floor(($dayNumber - 2415021.076998695) / 29.530588853);
    $monthStart = getNewMoonDay($k + 1, $timeZone);
    if ($monthStart > $dayNumber) {
        $monthStart = getNewMoonDay($k, $timeZone);
    }
    $a11 = getLunarMonth11($yy, $timeZone);
    $b11 = $a11;
    if ($a11 >= $monthStart) {
        $lunarYear = $yy;
        $a11 = getLunarMonth11($yy - 1, $timeZone);
    } else {
        $lunarYear = $yy + 1;
        $b11 = getLunarMonth11($yy + 1, $timeZone);
    }
    $lunarDay = $dayNumber - $monthStart + 1;
    $diff = floor(($monthStart - $a11) / 29);
    $lunarLeap = 0;
    $lunarMonth = $diff + 11;
    if ($b11 - $a11 > 365) {
        $leapMonthDiff = getLeapMonthOffset($a11, $timeZone);
        if ($diff >= $leapMonthDiff) {
            $lunarMonth = $diff + 10;
            if ($diff == $leapMonthDiff) {
                $lunarLeap = 1;
            }
        }
    }
    if ($lunarMonth > 12) {
        $lunarMonth = $lunarMonth - 12;
    }
    if ($lunarMonth >= 11 and $diff < 4) {
        --$lunarYear;
    }

    return [$lunarDay, $lunarMonth, $lunarYear, $lunarLeap];
}

/**
 * convertLunar2Solar()
 *
 * @param int $lunarDay
 * @param int $lunarMonth
 * @param int $lunarYear
 * @param int $lunarLeap
 * @param int $timeZone
 * @return array
 */
function convertLunar2Solar($lunarDay, $lunarMonth, $lunarYear, $lunarLeap, $timeZone)
{
    if ($lunarMonth < 11) {
        $a11 = getLunarMonth11($lunarYear - 1, $timeZone);
        $b11 = getLunarMonth11($lunarYear, $timeZone);
    } else {
        $a11 = getLunarMonth11($lunarYear, $timeZone);
        $b11 = getLunarMonth11($lunarYear + 1, $timeZone);
    }
    $k = floor(0.5 + ($a11 - 2415021.076998695) / 29.530588853);
    $off = $lunarMonth - 11;
    if ($off < 0) {
        $off += 12;
    }
    if ($b11 - $a11 > 365) {
        $leapOff = getLeapMonthOffset($a11, $timeZone);
        $leapMonth = $leapOff - 2;
        if ($leapMonth < 0) {
            $leapMonth += 12;
        }
        if ($lunarLeap != 0 and $lunarMonth != $leapMonth) {
            return [0, 0, 0];
        }
        if ($lunarLeap != 0 or $off >= $leapOff) {
            ++$off;
        }
    }
    $monthStart = getNewMoonDay($k + $off, $timeZone);

    return jdToDate($monthStart + $lunarDay - 1);
}

/**
 * alhn()
 *
 * @return string
 */
function alhn()
{
    $CAN = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    $CHI = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
    $arr = array_slice(convertSolar2Lunar(date('d'), date('m'), date('Y'), 7), 0, 3);
    $arr[0] = str_pad($arr[0], 2, '0', STR_PAD_LEFT);
    $arr[1] = str_pad($arr[1], 2, '0', STR_PAD_LEFT);
    $arr[2] = $CAN[($arr[2] + 6) % 10] . ' ' . $CHI[($arr[2] + 8) % 12];

    return 'Âm lịch: ngày ' . $arr[0] . ' tháng ' . $arr[1] . ' năm ' . $arr[2];
}
