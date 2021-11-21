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
 * Class Etsy
 *
 * @package OAuth\UserData\Extractor
 */
class Etsy extends LazyExtractor
{

    /**
     * Request contants
     */
    const REQUEST_USER = 'users/__SELF__';
    const REQUEST_PROFILE = 'users/:user_id/profile';
    const PROFILE_URL = 'https://www.etsy.com/people/:username';

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
                    self::FIELD_EXTRA
                ]
            ),
            self::getDefaultNormalizersMap()
                ->noNormalizer(self::FIELD_IMAGE_URL)->prefilled(self::FIELD_VERIFIED_EMAIL, true)
                ->pathContext('results.0')
                ->paths(
                    [
                        self::FIELD_UNIQUE_ID   => 'user_id',
                        self::FIELD_USERNAME    => 'login_name',
                        self::FIELD_FIRST_NAME  => 'first_name',
                        self::FIELD_LAST_NAME   => 'last_name',
                        self::FIELD_EMAIL       => 'primary_email',
                        self::FIELD_DESCRIPTION => 'bio',
                        self::FIELD_IMAGE_URL   => 'image_url_75x75',
                    ]
                )
        );
    }

    protected function profileLoader()
    {
        $data = $this->service->requestJSON(self::REQUEST_USER);

        if (!ArrayUtils::getNested($data, 'results.0.user_id')) {
            return [];
        }

        // Preserve email here, and save it to next request response, in order to prevent
        // from making second REQUEST_USER request
        // It's strange thing, but PROFILE data doesn't contains email, so we handle it here
        $email = ArrayUtils::getNested($data, 'results.0.primary_email');

        $profile = $this->service->requestJSON(
            str_replace(':user_id', ArrayUtils::getNested($data, 'results.0.user_id'), self::REQUEST_PROFILE)
        );

        if (!empty($profile) and $profile[ 'count' ]) {
            $profile[ 'results' ][ 0 ][ 'primary_email' ] = $email;
        }

        return $profile;
    }

    protected function fullNameNormalizer()
    {
        return trim($this->getFirstName() . ' ' . $this->getLastName());
    }

    protected function locationNormalizer($data)
    {
        $path = trim($this->normalizersMap->getPathContext(), '.');

        return join(
            ', ',
            array_filter(
                [
                    ArrayUtils::getNested($data, "$path.city", ''),
                    ArrayUtils::getNested($data, "$path.region", ''),
                ]
            )
        );
    }

    protected function profileUrlNormalizer()
    {
        return !$this->getUniqueId() ?: str_replace(':username', $this->getUsername(), self::PROFILE_URL);
    }
}
