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

use Gregwar\Image\Image;
use OAuth\Common\Exception\Exception;
use OAuth\Common\Service\AbstractService;
use OAuth\UserData\Arguments\FieldsValues;

/**
 * Class Extractor
 *
 * @package OAuth\UserData\Extractor
 */
class Extractor implements ExtractorInterface
{

    /**
     * @var \OAuth\Common\Service\ServiceInterface $service
     */
    protected $service;

    /**
     * Array of supported fields
     *
     * @var array $supports
     */
    protected $supports;

    /**
     * Associative array with all the fields value
     *
     * @var array
     */
    protected $fields;

    /**
     * Constructor
     *
     * @param FieldsValues $fieldsValues
     */
    public function __construct(FieldsValues $fieldsValues = null)
    {
        if (!$fieldsValues) {
            $fieldsValues = new FieldsValues();
        }

        $this->supports = $fieldsValues->getSupportedFields();
        $this->fields = $fieldsValues->getFieldsValues();
    }

    /**
     * {@inheritDoc}
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    // --- Accessors

    /**
     * {@inheritDoc}
     */
    public function supportsUniqueId()
    {
        return $this->isFieldSupported(self::FIELD_UNIQUE_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getUniqueId()
    {
        return $this->getField(self::FIELD_UNIQUE_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsUsername()
    {
        return $this->isFieldSupported(self::FIELD_USERNAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->getField(self::FIELD_USERNAME);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsFirstName()
    {
        return $this->isFieldSupported(self::FIELD_FIRST_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstName()
    {
        return $this->getField(self::FIELD_FIRST_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsLastName()
    {
        return $this->isFieldSupported(self::FIELD_LAST_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastName()
    {
        return $this->getField(self::FIELD_LAST_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsFullName()
    {
        return $this->isFieldSupported(self::FIELD_FULL_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getFullName()
    {
        return $this->getField(self::FIELD_FULL_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsEmail()
    {
        return $this->isFieldSupported(self::FIELD_EMAIL);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {
        return $this->getField(self::FIELD_EMAIL);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsLocation()
    {
        return $this->isFieldSupported(self::FIELD_LOCATION);
    }

    /**
     * {@inheritDoc}
     */
    public function getLocation()
    {
        return $this->getField(self::FIELD_LOCATION);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDescription()
    {
        return $this->isFieldSupported(self::FIELD_DESCRIPTION);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->getField(self::FIELD_DESCRIPTION);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsImageUrl()
    {
        return $this->isFieldSupported(self::FIELD_IMAGE_URL);
    }

    /**
     * {@inheritDoc}
     */
    public function getImageUrl()
    {
        return $this->getField(self::FIELD_IMAGE_URL);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsProfileUrl()
    {
        return $this->isFieldSupported(self::FIELD_PROFILE_URL);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfileUrl()
    {
        return $this->getField(self::FIELD_PROFILE_URL);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsWebsites()
    {
        return $this->isFieldSupported(self::FIELD_WEBSITES);
    }

    /**
     * {@inheritDoc}
     */
    public function getWebsites()
    {
        return $this->getField(self::FIELD_WEBSITES);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsVerifiedEmail()
    {
        return $this->isFieldSupported(self::FIELD_VERIFIED_EMAIL);
    }

    /**
     * {@inheritDoc}
     */
    public function isEmailVerified()
    {
        return $this->getField(self::FIELD_VERIFIED_EMAIL);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsExtra()
    {
        return $this->isFieldSupported(self::FIELD_EXTRA);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtra($key)
    {
        $extras = $this->getExtras();

        return (isset($extras[ $key ]) ? $extras[ $key ] : null);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtras()
    {
        return $this->getField(self::FIELD_EXTRA);
    }

    // --- Helpers

    /**
     * {@inheritdoc}
     */
    public function saveImage($savePath, $width = false, $height = false)
    {
        $ext = pathinfo($savePath, PATHINFO_EXTENSION);

        if (!$ext) {
            throw new Exception('Path passed as arguments is path without extension!');
        }

        try {
            return Image::fromData($this->getImageRawData($width, $height))->save($savePath, $ext);
        } // Catch any exception
        catch (\Exception $e) {
            throw new Exception('Exception occurred  during saving image: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getImageRawData($width = false, $height = false)
    {
        if (!$this->supportsImageUrl()) {
            throw new Exception('Image url is not supported by "' . basename(get_class($this)) . '" class!');
        }

        if (!$this->getImageUrl()) {
            throw new Exception('Image url is empty');
        }

        $rawImage = $this->service->httpRequest($this->getImageUrl(), [], [], 'GET');
        $image = Image::fromData($rawImage);

        if ($width or $height) {
            /** @noinspection PhpUndefinedMethodInspection */
            $image->resize($width ? $width : null, $height ? $height : null);
        }

        try {
            return $image->get();
        } // Catch any exception
        catch (\Exception $e) {
            throw new Exception('Exception occurred  during saving image: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getService($throw = true)
    {
        if (!$this->service) {
            if ($throw) {
                throw new Exception('Service was not persisted in Extractor!');
            } else {
                return false;
            }
        }

        return $this->service;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceId()
    {
        /** @var AbstractService $service */
        $service = $this->getService(true);

        return $service->service();
    }

    // --- Internal methods

    /**
     * Get the value for a given field
     *
     * @param  string $field the name of the field
     *
     * @return null|mixed
     */
    protected function getField($field)
    {
        if ($this->isFieldSupported($field) && isset($this->fields[ $field ])) {
            return $this->fields[ $field ];
        }

        return null;
    }

    /**
     * Check if a given field is supported
     *
     * @param  string $field the name of the field
     *
     * @return bool
     */
    protected function isFieldSupported($field)
    {
        return in_array($field, $this->supports);
    }

    /**
     * Get an array listing all fields names
     *
     * @return FieldsValues
     */
    protected static function getAllFields()
    {
        return FieldsValues::construct(
            [
                self::FIELD_UNIQUE_ID,
                self::FIELD_USERNAME,
                self::FIELD_FIRST_NAME,
                self::FIELD_LAST_NAME,
                self::FIELD_FULL_NAME,
                self::FIELD_EMAIL,
                self::FIELD_DESCRIPTION,
                self::FIELD_LOCATION,
                self::FIELD_PROFILE_URL,
                self::FIELD_IMAGE_URL,
                self::FIELD_WEBSITES,
                self::FIELD_VERIFIED_EMAIL,
                self::FIELD_EXTRA
            ]
        );
    }
}
