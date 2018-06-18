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
 * Class Instagram
 *
 * @package OAuth\UserData\Extractor
 */
class Instagram extends LazyExtractor
{

    const REQUEST_PROFILE = '/users/self';

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
                    self::FIELD_WEBSITES,
                    self::FIELD_IMAGE_URL,
                    self::FIELD_PROFILE_URL,
                    self::FIELD_EXTRA
                ]
            ),
            self::getDefaultNormalizersMap()
                ->pathContext('data')
                ->paths(
                    [
                        self::FIELD_UNIQUE_ID   => 'id',
                        self::FIELD_USERNAME    => 'username',
                        self::FIELD_FULL_NAME   => 'full_name',
                        self::FIELD_DESCRIPTION => 'bio',
                        self::FIELD_WEBSITES    => 'website',
                        self::FIELD_IMAGE_URL   => 'profile_picture'
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

    protected function profileUrlNormalizer()
    {
        $username = $this->getField(self::FIELD_USERNAME);

        if ($username) {
            return sprintf('http://instagram.com/%s', $username);
        }

        return null;
    }
}
