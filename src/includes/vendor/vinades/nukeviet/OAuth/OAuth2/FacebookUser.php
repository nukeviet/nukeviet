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

class FacebookUser implements ResourceOwnerInterface
{
    /**
     * @var array array dữ liệu array oauth trả về
     */
    protected $data;

    /**
     * @param  array $response
     */
    public function __construct(array $response)
    {
        $this->data = $response;

        if (!empty($response['picture']['data']['url'])) {
            $this->data['picture_url'] = $response['picture']['data']['url'];
        }

        if (isset($response['picture']['data']['is_silhouette'])) {
            $this->data['is_silhouette'] = $response['picture']['data']['is_silhouette'];
        }

        if (!empty($response['cover']['source'])) {
            $this->data['cover_photo_url'] = $response['cover']['source'];
        }
    }

    /**
     * Unique ID của user trên Oauth
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->getField('id') ?: '';
    }

    /**
     * Họ tên
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getField('name') ?: '';
    }

    /**
     * Tên
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->getField('first_name') ?: '';
    }

    /**
     * Họ
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->getField('last_name') ?: '';
    }

    /**
     * Email nếu có
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getField('email') ?: '';
    }

    /**
     * Địa chỉ
     *
     * @return array|null
     */
    public function getHometown(): ?array
    {
        return $this->getField('hometown') ?: [];
    }

    /**
     * Ảnh đại diện mặc định
     *
     * @return boolean
     */
    public function isDefaultPicture(): bool
    {
        return $this->getField('is_silhouette') ?: '';
    }

    /**
     * Ảnh đại diện nếu có
     *
     * @return string|null
     */
    public function getPictureUrl(): ?string
    {
        return $this->getField('picture_url') ?: '';
    }

    /**
     * Giới tính nếu có
     *
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->getField('gender') ?: '';
    }

    /**
     * Liên kết đến trang cá nhân nếu có
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->getField('link') ?: '';
    }

    /**
     * Giới hạn dưới của độ tuổi của người dùng
     *
     * @return integer|null
     */
    public function getMinAge(): ?int
    {
        return $this->data['age_range']['min'] ?? null;
    }

    /**
     * Giới hạn trên của độ tuổi của người dùng
     *
     * @return integer|null
     */
    public function getMaxAge(): ?int
    {
        return $this->data['age_range']['max'] ?? null;
    }

    /**
     * Tất cả dữ liệu trả về dạng mảng
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Trả về field nhất định nếu không thì null
     *
     * @return mixed|null
     */
    private function getField(string $key)
    {
        return $this->data[$key] ?? null;
    }
}
