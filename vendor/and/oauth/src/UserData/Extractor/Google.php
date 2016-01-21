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
 * Class Google
 *
 * @package OAuth\UserData\Extractor
 */
class Google extends LazyExtractor
{

    const REQUEST_PROFILE = 'https://www.googleapis.com/oauth2/v1/userinfo';

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
                    self::FIELD_PROFILE_URL,
                    self::FIELD_IMAGE_URL,
                    self::FIELD_VERIFIED_EMAIL,
                    self::FIELD_EXTRA,
                ]
            ),
            self::getDefaultNormalizersMap()
                ->add(
                    [
                        self::FIELD_UNIQUE_ID      => 'id',
                        self::FIELD_USERNAME       => 'name',
                        self::FIELD_FIRST_NAME     => 'given_name',
                        self::FIELD_LAST_NAME      => 'family_name',
                        self::FIELD_FULL_NAME      => 'name',
                        self::FIELD_EMAIL          => 'email',
                        self::FIELD_IMAGE_URL      => 'picture',
                        self::FIELD_VERIFIED_EMAIL => ['verified_email', false]
                    ]
                )
        );
    }

    protected function profileLoader()
    {
        return $this->service->requestJSON(self::REQUEST_PROFILE);
    }

    protected function profileUrlNormalizer($data)
    {
        return empty($data[ 'id' ]) ? null : "https://plus.google.com/{$data['id']}";
    }
}
