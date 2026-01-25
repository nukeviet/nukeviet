<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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
    public function getName(): string
    {
        return $this->response['name'] ?? '';
    }

    /**
     * Tên
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->getResponseValue('given_name') ?: '';
    }

    /**
     * Họ
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->getResponseValue('family_name') ?: '';
    }

    /**
     * Ngôn ngữ
     *
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->getResponseValue('locale') ?: '';
    }

    /**
     * Địa chị email nếu có
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getResponseValue('email') ?: '';
    }

    /**
     * Domain nếu dùng Google Cloud
     *
     * @return string|null
     */
    public function getHostedDomain(): ?string
    {
        return $this->getResponseValue('hd') ?: '';
    }

    /**
     * Ảnh đại diện nếu có
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->getResponseValue('picture') ?: '';
    }

    /**
     * Tất cả dữ liệu trả về dạng mảng
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->response;
    }

    /**
     * @param string $key
     * @return null|string|int
     */
    private function getResponseValue($key)
    {
        return $this->response[$key] ?? null;
    }
}
