<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\OAuth\OAuth2;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use NukeViet\OAuth\Oauth2Exception\HostedDomainException;
use Psr\Http\Message\ResponseInterface;

/**
 * Google Oauth 2.0 Client
 */
class Google extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected $accessType = 'online';

    /**
     * @var string If set, this will be sent to google as the "hd" parameter.
     * @link https://developers.google.com/identity/protocols/OpenIDConnect#authenticationuriparameters
     */
    protected $hostedDomain;

    /**
     * @var string If set, this will be sent to google as the "prompt" parameter.
     * @link https://developers.google.com/identity/protocols/OpenIDConnect#authenticationuriparameters
     */
    protected $prompt;

    /**
     * @var array Danh sách quyền cần cấp
     * @link https://developers.google.com/identity/protocols/googlescopes
     */
    protected $scopes = [];

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getBaseAuthorizationUrl()
     */
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://accounts.google.com/o/oauth2/v2/auth';
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getBaseAccessTokenUrl()
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://oauth2.googleapis.com/token';
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getResourceOwnerDetailsUrl()
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://openidconnect.googleapis.com/v1/userinfo';
    }

    protected function getAuthorizationParameters(array $options): array
    {
        if (empty($options['hd']) && $this->hostedDomain) {
            $options['hd'] = $this->hostedDomain;
        }

        if (empty($options['access_type']) && $this->accessType) {
            $options['access_type'] = $this->accessType;
        }

        if (empty($options['prompt']) && $this->prompt) {
            $options['prompt'] = $this->prompt;
        }

        $scopes = array_merge($this->getDefaultScopes(), $this->scopes);
        if (!empty($options['scope'])) {
            $scopes = array_merge($scopes, $options['scope']);
        }

        $options['scope'] = array_unique($scopes);
        $options = parent::getAuthorizationParameters($options);

        // https://developers.google.com/identity/protocols/oauth2/openid-connect#prompt
        unset($options['approval_prompt']);

        return $options;
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getDefaultScopes()
     */
    protected function getDefaultScopes(): array
    {
        return [
            'openid',
            'email',
            'profile',
        ];
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getScopeSeparator()
     */
    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::checkResponse()
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (empty($data['error'])) {
            return;
        }

        $code = 0;
        $error = $data['error'];

        if (is_array($error)) {
            $code = $error['code'];
            $error = $error['message'];
        }

        throw new IdentityProviderException($error, $code, $data);
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::createResourceOwner()
     */
    protected function createResourceOwner(array $response, AccessToken $token): GoogleUser
    {
        $user = new GoogleUser($response);

        $this->assertMatchingDomain($user->getHostedDomain());

        return $user;
    }

    /**
     * @param string|null $hostedDomain
     *
     * @throws HostedDomainException Nếu miền user này không khớp với miền được phép khi có cấu hình
     */
    protected function assertMatchingDomain(?string $hostedDomain): void
    {
        // Không check miền
        if ($this->hostedDomain === null) {
            return;
        }

        // Được phép mọi miền
        if ($this->hostedDomain === '*' && $hostedDomain) {
            return;
        }

        // Khớp miền
        if ($this->hostedDomain === $hostedDomain) {
            return;
        }

        throw HostedDomainException::notMatchingDomain($this->hostedDomain);
    }
}
