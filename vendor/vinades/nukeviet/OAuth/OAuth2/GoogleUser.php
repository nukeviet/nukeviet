<?php

/**
 * NUKEVIET Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\OAuth\OAuth2;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class GoogleUser implements ResourceOwnerInterface
{
    /**
     * @var array dữ liệu array oauth trả về
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Unique ID của user trên Oauth
     *
     * @return string
     */
    public function getId()
    {
        return $this->response['sub'];
    }

    /**
     * Họ tên
     *
     * @return string
     */
    public function getName()
    {
        return isset($this->response['name']) ? $this->response['name'] : '';
    }

    /**
     * Tên
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->getResponseValue('given_name') ?: '';
    }

    /**
     * Họ
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getResponseValue('family_name') ?: '';
    }

    /**
     * Ngôn ngữ
     *
     * @return string|null
     */
    public function getLocale()
    {
        return $this->getResponseValue('locale') ?: '';
    }

    /**
     * Địa chị email nếu có
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getResponseValue('email') ?: '';
    }

    /**
     * Domain nếu dùng Google Cloud
     *
     * @return string|null
     */
    public function getHostedDomain()
    {
        return $this->getResponseValue('hd') ?: '';
    }

    /**
     * Ảnh đại diện nếu có
     *
     * @return string|null
     */
    public function getAvatar()
    {
        return $this->getResponseValue('picture') ?: '';
    }

    /**
     * Tất cả dữ liệu trả về dạng mảng
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }

    /**
     * @param string $key
     * @return null|string|int
     */
    private function getResponseValue($key)
    {
        return isset($this->response[$key]) ? $this->response[$key] : null;
    }
}
