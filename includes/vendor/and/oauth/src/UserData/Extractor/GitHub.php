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
 * Class GitHub
 *
 * @package OAuth\UserData\Extractor
 */
class GitHub extends LazyExtractor
{

    const REQUEST_PROFILE = '/user';
    const REQUEST_EMAIL = '/user/emails';

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
                    self::FIELD_LOCATION,
                    self::FIELD_DESCRIPTION,
                    self::FIELD_IMAGE_URL,
                    self::FIELD_PROFILE_URL,
                    self::FIELD_VERIFIED_EMAIL,
                    self::FIELD_EXTRA
                ]
            ),
            self::getDefaultNormalizersMap()
                ->paths(
                    [
                        self::FIELD_UNIQUE_ID   => 'id',
                        self::FIELD_USERNAME    => 'login',
                        self::FIELD_FULL_NAME   => 'name',
                        self::FIELD_LOCATION    => 'location',
                        self::FIELD_DESCRIPTION => 'bio',
                        self::FIELD_IMAGE_URL   => 'avatar_url',
                        self::FIELD_PROFILE_URL => 'html_url'
                    ]
                ),
            self::getDefaultLoadersMap()
                ->loader('email')->readdFields([self::FIELD_EMAIL, self::FIELD_VERIFIED_EMAIL])
        );
    }

    protected function profileLoader()
    {
        return $this->service->requestJSON(self::REQUEST_PROFILE);
    }

    protected function emailLoader()
    {
        return $this->service->requestJSON(self::REQUEST_EMAIL);
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

    protected function emailNormalizer($emails)
    {
        $email = $this->getEmailObject($emails);

        return $email[ 'email' ];
    }

    protected function verifiedEmailNormalizer($emails)
    {
        $email = $this->getEmailObject($emails);

        return $email[ 'verified' ];
    }

    /**
     * Get the right email address from the one's the user provides.
     *
     * @param array $emails The array of email array objects provided by GitHub.
     *
     * @return array The email array object.
     */
    private function getEmailObject($emails)
    {
        // Try to find an email address which is primary and verified.
        foreach ($emails as $email) {
            if ($email[ 'primary' ] && $email[ 'verified' ]) {
                return $email;
            }
        }

        // Try to find an email address which is primary.
        foreach ($emails as $email) {
            if ($email[ 'primary' ]) {
                return $email;
            }
        }

        return $emails[ 0 ];
    }
}
