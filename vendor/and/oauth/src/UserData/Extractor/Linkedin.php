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
 * Class Linkedin
 *
 * @package OAuth\UserData\Extractor
 */
class Linkedin extends LazyExtractor
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            FieldsValues::construct(
                [
                    self::FIELD_UNIQUE_ID,
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
                ->paths(
                    [
                        self::FIELD_UNIQUE_ID   => 'id',
                        self::FIELD_FIRST_NAME  => 'firstName',
                        self::FIELD_LAST_NAME   => 'lastName',
                        self::FIELD_EMAIL       => 'emailAddress',
                        self::FIELD_DESCRIPTION => 'summary',
                        self::FIELD_LOCATION    => 'location',
                        self::FIELD_PROFILE_URL => 'publicProfileUrl',
                        self::FIELD_IMAGE_URL   => 'pictureUrl'
                    ]
                )
                // Linkedin users who have access to OAuth v2 always have a verified email
                ->prefilled(self::FIELD_VERIFIED_EMAIL, true)
        );
    }

    /**
     * Builds the query string needed to retrieve profile user data
     *
     * @return string
     */
    public static function createProfileRequestUrl()
    {
        $fields = [
            'id',
            'summary',
            'member-url-resources',
            'email-address',
            'first-name',
            'last-name',
            'headline',
            'location',
            'industry',
            'picture-url',
            'public-profile-url'
        ];

        return sprintf('/people/~:(%s)?format=json', implode(",", $fields));
    }

    protected function profileLoader()
    {
        return $this->service->requestJSON(self::createProfileRequestUrl());
    }

    protected function fullNameNormalizer()
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }

    protected function websitesNormalizer($data)
    {
        $websites = [];
        if (isset($data[ 'memberUrlResources' ], $data[ 'memberUrlResources' ][ 'values' ])) {
            foreach ($data[ 'memberUrlResources' ][ 'values' ] as $resource) {
                if (isset($resource[ 'url' ])) {
                    $websites[ ] = $resource[ 'url' ];
                }
            }
        }

        return $websites;
    }
}
