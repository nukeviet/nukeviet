<?php

/*
 * This file is part of the php-oauth package <https://github.com/logical-and/php-oauth>.
 *
 * (c) Oryzone, developed by Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OAuth\UserData\Extractor;

use OAuth\UserData\Exception\Exception;

/**
 * Interface ExtractorInterface
 *
 * @package OAuth\UserData\Extractor
 */
interface ExtractorInterface
{

    /**
     * Field names constants
     */
    const FIELD_UNIQUE_ID = 'uniqueId';
    const FIELD_USERNAME = 'username';
    const FIELD_FIRST_NAME = 'firstName';
    const FIELD_LAST_NAME = 'lastName';
    const FIELD_FULL_NAME = 'fullName';
    const FIELD_EMAIL = 'email';
    const FIELD_LOCATION = 'location';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_IMAGE_URL = 'imageUrl';
    const FIELD_PROFILE_URL = 'profileUrl';
    const FIELD_WEBSITES = 'websites';
    const FIELD_VERIFIED_EMAIL = 'verifiedEmail';
    const FIELD_EXTRA = 'extra';

    /**
     * @param  \OAuth\Common\Service\ServiceInterface $service
     *
     * @return void
     */
    public function setService($service);

    /**
     * Get oauth service
     *
     * @param bool $throw Will throw an exception if no service was set
     *
     * @throws Exception If not service was set
     *
     * @return \OAuth\OAuth1\Service\AbstractService|\OAuth\OAuth2\Service\AbstractService
     */
    public function getService($throw = true);

    /**
     * Get service id
     *
     * @return string String, like "google" or "facebook"
     * @throws Exception
     */
    public function getServiceId();

    /**
     * Check if the current provider supports a unique id
     *
     * @return bool
     */
    public function supportsUniqueId();

    /**
     * Get the unique id of the user
     *
     * @return string
     */
    public function getUniqueId();

    /**
     * Check if the current provider supports a username
     *
     * @return bool
     */
    public function supportsUsername();

    /**
     * Get the username
     *
     * @return string
     */
    public function getUsername();

    /**
     * Check if the current provider supports a first name
     *
     * @return bool
     */
    public function supportsFirstName();

    /**
     * Get the first name
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Check if the current provider supports a last name
     *
     * @return bool
     */
    public function supportsLastName();

    /**
     * Get the last name
     *
     * @return string
     */
    public function getLastName();

    /**
     * Check if the current provider supports a full name
     *
     * @return bool
     */
    public function supportsFullName();

    /**
     * Get the full name
     *
     * @return string
     */
    public function getFullName();

    /**
     * Check ig the current provider supports an email
     *
     * @return bool
     */
    public function supportsEmail();

    /**
     * Get the email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Check if the current provider supports a location
     *
     * @return bool
     */
    public function supportsLocation();

    /**
     * Get the location
     *
     * @return string
     */
    public function getLocation();

    /**
     * Check if the current provider supports a description
     *
     * @return bool
     */
    public function supportsDescription();

    /**
     * Get the description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Check if the current provider supports an image url
     *
     * @return bool
     */
    public function supportsImageUrl();

    /**
     * Get the image url
     *
     * @return string
     */
    public function getImageUrl();

    /**
     * Check if the current provider supports a profile url
     *
     * @return bool
     */
    public function supportsProfileUrl();

    /**
     * Get the profile url
     *
     * @return string
     */
    public function getProfileUrl();

    /**
     * Check if the current provider supports websites
     *
     * @return bool
     */
    public function supportsWebsites();

    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites();

    /**
     * Check if the current provider supports the "verified" field
     *
     * @return bool
     */
    public function supportsVerifiedEmail();

    /**
     * Get the verified
     *
     * @return bool
     */
    public function isEmailVerified();

    /**
     * Check if the current provider supports extra data
     *
     * @return bool
     */
    public function supportsExtra();

    /**
     * Get an extra attribute
     *
     * @param  string $key
     *
     * @return array
     */
    public function getExtra($key);

    /**
     * Get the extras array
     *
     * @return array
     */
    public function getExtras();

    /**
     * Save image file by given path. Path extension is mandatory and it's determine, which image type will be used
     *
     * @param $savePath
     * @param bool $width Optional value, max width
     * @param bool $height Optional value, max height
     *
     * @return bool|string File path if success, false if failure
     * @throws Exception
     */
    public function saveImage($savePath, $width = false, $height = false);

    /**
     * Get image raw data
     *
     * @param bool $width Optional value, max width
     * @param bool $height Optional value, max height
     *
     * @return bool|mixed|string
     * @throws Exception
     */
    public function getImageRawData($width = false, $height = false);
}
