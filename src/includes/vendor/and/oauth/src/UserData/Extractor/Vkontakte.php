<?php

/*
 * This file is part of the php-oauth package <https://github.com/logical-and/php-oauth>.
 *
 * (c) Developed by And <and.webdev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OAuth\UserData\Extractor;

use OAuth\UserData\Arguments\FieldsValues;
use OAuth\UserData\Utils\ArrayUtils;

/**
 * Class Vkontakte
 *
 * @package OAuth\UserData\Extractor
 */
class Vkontakte extends LazyExtractor
{

    const REQUEST_PROFILE = 'users.get.json';
    const REQUEST_CITY = 'database.getCitiesById';

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
                    self::FIELD_VERIFIED_EMAIL,
                    self::FIELD_EXTRA
                ]
            ),
            self::getDefaultNormalizersMap()
                ->pathContext('response.0')
                ->paths(
                    [
                        self::FIELD_UNIQUE_ID   => 'uid',
                        self::FIELD_FIRST_NAME  => 'first_name',
                        self::FIELD_LAST_NAME   => 'last_name',
                        self::FIELD_EMAIL       => 'email',
                        self::FIELD_DESCRIPTION => 'about',
                        self::FIELD_IMAGE_URL   => 'photo_max_orig'
                    ]
                )
        );
    }

    protected function profileLoader()
    {
        return $this->service->requestJSON(
            self::REQUEST_PROFILE,
            [
                'fields' =>
                // https://vk.com/dev/users.get

                    'sex, bdate, city, country, photo_50, photo_100, photo_200_orig, ' .
                    'photo_200, photo_400_orig, photo_max, photo_max_orig, photo_id, ' .
                    'online, online_mobile, domain, has_mobile, contacts, connections, site, ' .
                    'education, universities, schools, can_post, can_see_all_posts, can_see_audio, ' .
                    'can_write_private_message, status, last_seen, common_count, relation, ' .
                    'relatives, counters, screen_name, maiden_name, timezone, occupation, ' .
                    'activities, interests, music, movies, tv, books, games, about, quotes, personal'
            ]
        );
    }

    protected function usernameNormalizer()
    {
        return $this->fullNameNormalizer();
    }

    protected function fullNameNormalizer()
    {
        return trim($this->getFirstName() . ' ' . $this->getLastName());
    }

    protected function verifiedEmailNormalizer()
    {
        return !!$this->getEmail();
    }

    protected function locationNormalizer($data)
    {
        $cityId = ArrayUtils::getNested($data, 'response.0.city');

        if ($cityId) {
            return ArrayUtils::getNested(
                $this->service->requestJSON(self::REQUEST_CITY, ['city_ids' => $cityId]),
                'response.0'
            );
        }

        return null;
    }

    protected function profileUrlNormalizer($data)
    {
        $id = ArrayUtils::getNested($data, 'response.0.screen_name');

        return !$id ? null : 'https://vk.com/' . ArrayUtils::getNested($data, 'response.0.screen_name');
    }
}
