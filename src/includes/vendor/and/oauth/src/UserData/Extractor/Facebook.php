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

use OAuth\UserData\Arguments\FieldsValues;
use OAuth\UserData\Utils\ArrayUtils;
use OAuth\UserData\Utils\StringUtils;

/**
 * Class Facebook
 *
 * @package OAuth\UserData\Extractor
 */
class Facebook extends LazyExtractor
{

    /**
     * Request contants
     */
    const REQUEST_PROFILE = '/me';
    const REQUEST_IMAGE = '/me/picture?type=large&redirect=false';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            FieldsValues::construct(
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
            ),
            self::getDefaultNormalizersMap()
                ->noNormalizer(self::FIELD_IMAGE_URL)->prefilled(self::FIELD_VERIFIED_EMAIL, true)
                ->paths(
                    [
                        self::FIELD_UNIQUE_ID   => 'id',
                        self::FIELD_USERNAME    => 'username',
                        self::FIELD_FIRST_NAME  => 'first_name',
                        self::FIELD_LAST_NAME   => 'last_name',
                        self::FIELD_FULL_NAME   => 'name',
                        self::FIELD_EMAIL       => 'email',
                        self::FIELD_DESCRIPTION => 'bio',
                        self::FIELD_LOCATION    => 'location.name',
                        self::FIELD_PROFILE_URL => 'link',
                    ]
                )
                // Facebook users who have access to Open Graph and OAuth always have a verified email
                ->prefilled(self::FIELD_VERIFIED_EMAIL, true),
            self::getDefaultLoadersMap()->loader('image')->readdField(self::FIELD_IMAGE_URL)
        );
    }

    protected function profileLoader()
    {
        return $this->service->requestJSON(self::REQUEST_PROFILE);
    }

    protected function imageLoader()
    {
        return ArrayUtils::getNested($this->service->requestJSON(self::REQUEST_IMAGE), 'data.url');
    }

    protected function websitesNormalizer($data)
    {
        return isset($data[ 'website' ]) ? StringUtils::extractUrls($data[ 'website' ]) : [];
    }
}
