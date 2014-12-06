# PayPal Core SDK - V1.5.4

## Prerequisites

 * PHP 5.2 and above
 * curl extension with support for OpenSSL
 * PHPUnit 3.5 for running test suite (Optional)
 * Composer (Optional - for running test cases)

## Configuration
  
 
## OpenID Connect Integration

   1. Redirect your buyer to `PPOpenIdSession::getAuthorizationUrl($redirectUri, array('openid', 'address'));` to obtain authorization. The second argument is the list of access privileges that you want from the buyer.
   2. Capture the authorization code that is available as a query parameter (`code`) in the redirect url
   3. Exchange the authorization code for a access token, refresh token, id token combo


```php
    $token = PPOpenIdTokeninfo::createFromAuthorizationCode(
		array(
			'code' => $authCode
		)
	);
```
   4. The access token is valid for a predefined duration and can be used for seamless XO or for retrieving user information
 

```php
   $user = PPOpenIdUserinfo::getUserinfo(
		array(
			'access_token' => $token->getAccessToken()
		)	
	);
```
   5. If the access token has expired, you can obtain a new access token using the refresh token from the 3'rd step.

```php
   $token->createFromRefreshToken(array('openid', 'address'));
```
   6. Redirect your buyer to `PPOpenIdSession::getLogoutUrl($redirectUri, $idToken);` to log him out of paypal. 
