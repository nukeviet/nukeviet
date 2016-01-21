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

/**
 * Class Twitter
 *
 * @package OAuth\UserData\Extractor
 */
class Twitter extends LazyExtractor
{

    /**
     * Request constants
     */
    const REQUEST_PROFILE = '/account/verify_credentials.json';

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
                    self::FIELD_FULL_NAME,
                    self::FIELD_FIRST_NAME,
                    self::FIELD_LAST_NAME,
                    self::FIELD_DESCRIPTION,
                    self::FIELD_LOCATION,
                    self::FIELD_PROFILE_URL,
                    self::FIELD_IMAGE_URL,
                    self::FIELD_WEBSITES,
                    self::FIELD_EXTRA
                ]
            ),
            self::getDefaultNormalizersMap()
                ->paths(
                    [
                        self::FIELD_UNIQUE_ID   => 'id',
                        self::FIELD_USERNAME    => 'screen_name',
                        self::FIELD_FULL_NAME   => 'name',
                        self::FIELD_DESCRIPTION => 'description',
                        self::FIELD_LOCATION    => 'location',
                        self::FIELD_IMAGE_URL   => 'profile_image_url'
                    ]
                )
        );
    }

    protected function profileLoader()
    {
        return $this->service->requestJSON(self::REQUEST_PROFILE);
    }

    protected function firstNameNormalizer()
    {
        $fullName = $this->getField(self::FIELD_FULL_NAME);
        if ($fullName) {
            $names = explode(' ', $fullName);

            return $names[ 0 ];
        }

        return null;
    }

    protected function lastNameNormalizer()
    {
        $fullName = $this->getField(self::FIELD_FULL_NAME);
        if ($fullName) {
            $names = explode(' ', $fullName);

            return $names[ sizeof($names) - 1 ];
        }

        return null;
    }

    protected function profileUrlNormalizer($data)
    {
        return isset($data[ 'screen_name' ]) ? sprintf('https://twitter.com/%s', $data[ 'screen_name' ]) : null;
    }

    protected function websitesNormalizer($data)
    {
        $websites = [];
        if (isset($data[ 'url' ])) {
            $websites[ ] = $data[ 'url' ];
        }
        if (isset($data[ 'entities' ][ 'url' ][ 'urls' ])) {
            foreach ($data[ 'entities' ][ 'url' ][ 'urls' ] as $urlData) {
                $websites[ ] = $urlData[ 'expanded_url' ];
            }
        }

        return array_unique($websites);
    }
}
