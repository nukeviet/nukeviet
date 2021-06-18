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

/**
 * Class Harvest
 *
 * @package OAuth\UserData\Extractor
 */
class Harvest extends LazyExtractor
{

    /**
     * Request constants
     */
    const REQUEST_PROFILE = '/account/who_am_i';

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
                    self::FIELD_IMAGE_URL,
                    self::FIELD_EMAIL,
                    self::FIELD_EXTRA
                ]
            ),
            self::getDefaultNormalizersMap()
                ->pathContext('user')
                ->paths(
                    [
                        self::FIELD_UNIQUE_ID  => 'id',
                        self::FIELD_USERNAME   => 'email',
                        self::FIELD_FIRST_NAME => 'first_name',
                        self::FIELD_LAST_NAME  => 'last_name',
                        self::FIELD_EMAIL      => 'email',
                    ]
                )
        );
    }

    protected function profileLoader()
    {
        return $this->service->requestJSON(self::REQUEST_PROFILE);
    }

    protected function fullNameNormalizer()
    {
        return trim($this->getFirstName() . ' ' . $this->getLastName());
    }

    protected function imageUrlNormalizer($data)
    {
        $avatarUrl = ArrayUtils::getNested($data, 'user.avatar_url');

        return !$avatarUrl ?: 'https://api.harvestapp.com/' . $avatarUrl;
    }
}
