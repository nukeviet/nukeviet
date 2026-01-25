<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use Tests\Support\UnitTester;

class RegionTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Kiểm tra định dạng số
     *
     * @group install
     * @group all
     */
    public function testNumberFormat()
    {
        // Số nguyên
        $this->assertEquals('123.456.789', nv_number_format(123456789, 'vi'));
        $this->assertEquals('123,456,789', nv_number_format(123456789, 'en'));

        // Số thực
        $this->assertEquals('123.456.789,01', nv_number_format(123456789.0123, 'vi'));
        $this->assertEquals('123,456,789.01', nv_number_format(123456789.0123, 'en'));

        // Bỏ số 0 phía cuối
        $this->assertEquals('123.456.789', nv_number_format(123456789.003, 'vi'));
        $this->assertEquals('123,456,789', nv_number_format(123456789.003, 'en'));

        // Số 0 ở đầu
        $this->assertEquals('0,01', nv_number_format(0.01234, 'vi'));
        $this->assertEquals('0.01', nv_number_format(0.01234, 'en'));
    }

    /**
     * Kiểm tra định dạng tiền
     *
     * @group install
     * @group all
     */
    public function testCurrencyFormat()
    {
        // Số nguyên
        $this->assertEquals('123.456.789đ', nv_currency_format(123456789, 'vi'));
        $this->assertEquals('$123,456,789', nv_currency_format(123456789, 'en'));

        // Số thực
        $this->assertEquals('123.456.789,01đ', nv_currency_format(123456789.0123, 'vi'));
        $this->assertEquals('$123,456,789.01', nv_currency_format(123456789.0123, 'en'));

        // Bỏ số 0 phía cuối
        $this->assertEquals('123.456.789đ', nv_currency_format(123456789.003, 'vi'));
        $this->assertEquals('$123,456,789', nv_currency_format(123456789.003, 'en'));

        // Số 0 ở đầu
        $this->assertEquals('0,01đ', nv_currency_format(0.01234, 'vi'));
        $this->assertEquals('$0.01', nv_currency_format(0.01234, 'en'));
    }

    /**
     * Kiểm tra định dạng ngày
     *
     * @group install
     * @group all
     */
    public function testDateFormat()
    {
        $timestamp = 1718268338;

        $this->assertEquals('13/06/2024', nv_date_format(1, $timestamp, 'vi'));
        $this->assertEquals('06/13/2024', nv_date_format(1, $timestamp, 'en'));
    }

    /**
     * Kiểm tra chuyển get date thành timestamp
     *
     * @group install
     * @group all
     */
    public function testD2UGet()
    {
        $this->assertEquals(1718298000, nv_d2u_get('14-06-2024', null, null, null, 'vi'));
        $this->assertEquals(1718298000, nv_d2u_get('06-14-2024', null, null, null, 'en'));

        $this->assertEquals(1718384399, nv_d2u_get('14-06-2024', 23, 59, 59, 'vi'));
        $this->assertEquals(1718384399, nv_d2u_get('06-14-2024', 23, 59, 59, 'en'));

        $this->assertEquals(1718349800, nv_d2u_get('14-06-2024 14:23:20', null, null, null, 'vi'));
        $this->assertEquals(1718349800, nv_d2u_get('06-14-2024 14:23:20', null, null, null, 'en'));

        $this->assertEquals(1718349780, nv_d2u_get('14-06-2024 14:23', null, null, null, 'vi'));
        $this->assertEquals(1718349780, nv_d2u_get('06-14-2024 14:23', null, null, null, 'en'));

        $this->assertEquals(1718384399, nv_d2u_get('14-06-2024 14:23:20', 23, 59, 59, 'vi'));
        $this->assertEquals(1718384399, nv_d2u_get('06-14-2024 14:23:20', 23, 59, 59, 'en'));
    }

    /**
     * Kiểm tra chuyển post date thành timestamp
     *
     * @group install
     * @group all
     */
    public function testD2UPost()
    {
        $this->assertEquals(1718298000, nv_d2u_post('14/06/2024', null, null, null, 'vi'));
        $this->assertEquals(1718298000, nv_d2u_post('06/14/2024', null, null, null, 'en'));

        $this->assertEquals(1718384399, nv_d2u_post('14/06/2024', 23, 59, 59, 'vi'));
        $this->assertEquals(1718384399, nv_d2u_post('06/14/2024', 23, 59, 59, 'en'));

        $this->assertEquals(1718349800, nv_d2u_post('14/06/2024 14:23:20', null, null, null, 'vi'));
        $this->assertEquals(1718349800, nv_d2u_post('06/14/2024 14:23:20', null, null, null, 'en'));

        $this->assertEquals(1718349780, nv_d2u_post('14/06/2024 14:23', null, null, null, 'vi'));
        $this->assertEquals(1718349780, nv_d2u_post('06/14/2024 14:23', null, null, null, 'en'));

        $this->assertEquals(1718384399, nv_d2u_post('14/06/2024 14:23:20', 23, 59, 59, 'vi'));
        $this->assertEquals(1718384399, nv_d2u_post('06/14/2024 14:23:20', 23, 59, 59, 'en'));
    }

    /**
     * Kiểm tra chuyển timestamp thành get date
     *
     * @group install
     * @group all
     */
    public function testU2DGet()
    {
        $this->assertEquals('14-06-2024', nv_u2d_get(1718298000, 'vi'));
        $this->assertEquals('06-14-2024', nv_u2d_get(1718298000, 'en'));
    }

    /**
     * Kiểm tra chuyển timestamp thành post date
     *
     * @group install
     * @group all
     */
    public function testU2DPost()
    {
        $this->assertEquals('14/06/2024', nv_u2d_post(1718298000, 'vi'));
        $this->assertEquals('06/14/2024', nv_u2d_post(1718298000, 'en'));
    }
}
