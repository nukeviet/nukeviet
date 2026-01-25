<?php
/**
 * Zalo © 2023
 *
 */

namespace Zalo\Authentication;

/**
 * Class ZaloToken
 *
 * @package Zalo
 */
class ZaloToken
{
    /**
     * The access token value
     *
     * @var string
     */
    protected $accessToken = '';

    /**
     * The refresh token value
     *
     * @var string
     */
    protected $refreshToken = '';

    /**
     * Date when access token expires.
     *
     * @var \DateTime|null
     */
    protected $accessTokenExpiresAt;

    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @param int $accessTokenExpiresIn
     */
    public function __construct($accessToken, $refreshToken = '', $accessTokenExpiresIn = 0)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;

        $now = time();
        $this->accessTokenExpiresAt = $this->getExpiresAtFromTimeStamp($now + $accessTokenExpiresIn);
    }

    /**
     * Returns the access token as a string.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Returns the refresh token as a string.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Returns the access token expires as a DateTime.
     *
     * @return \DateTime|null
     */
    public function getAccessTokenExpiresAt()
    {
        return $this->accessTokenExpiresAt;
    }

    /**
     * Get expires at from timestamp.
     *
     * @param int $timeStamp
     */
    protected function getExpiresAtFromTimeStamp($timeStamp)
    {
        $dt = new \DateTime();
        $dt->setTimestamp($timeStamp);
        return $dt;
    }
}