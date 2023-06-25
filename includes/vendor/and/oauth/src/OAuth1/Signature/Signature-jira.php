<?php

namespace OAuth\OAuth1\Signature;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Url;
use OAuth\OAuth1\Signature\Exception\UnsupportedHashAlgorithmException;

class Signature implements SignatureInterface
{

    /**
     * @var Credentials
     */
    protected $credentials;

    /**
     * @var string
     */
    protected $algorithm;

    /**
     * @var string
     */
    protected $tokenSecret = null;

    /**
     * @param CredentialsInterface $credentials
     */
    public function __construct(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @param string $algorithm
     */
    public function setHashingAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @param string $token
     */
    public function setTokenSecret($token)
    {
        $this->tokenSecret = $token;
    }

    /**
     * @param Url $uri
     * @param array $params
     * @param string $method
     *
     * @return string
     */
    public function getSignature(Url $uri, array $params, $method = 'POST')
    {
        parse_str($uri->getQuery(), $queryStringData);

        foreach (array_merge($queryStringData, $params) as $key => $value) {
            $signatureData[ rawurlencode($key) ] = rawurlencode($value);
        }

        ksort($signatureData);

        // determine base uri
        $baseUri = $uri->getScheme() . '://' . $uri->getAuthority();

        if ('/' === $uri->getPath()->getUriComponent()) {
            $baseUri .= '/';
        } else {
            $baseUri .= $uri->getPath()->getUriComponent();
        }

        $baseString = strtoupper($method) . '&';
        $baseString .= rawurlencode($baseUri) . '&';
        $baseString .= rawurlencode($this->buildSignatureDataString($signatureData));

        return base64_encode($this->hash($baseString));
    }

    /**
     * @param array $signatureData
     *
     * @return string
     */
    protected function buildSignatureDataString(array $signatureData)
    {
        $signatureString = '';
        $delimiter = '';
        foreach ($signatureData as $key => $value) {
            $signatureString .= $delimiter . $key . '=' . $value;

            $delimiter = '&';
        }

        return $signatureString;
    }

    /**
     * @return string
     */
    protected function getSigningKey()
    {
        $signingKey = rawurlencode($this->credentials->getConsumerSecret()) . '&';
        if ($this->tokenSecret !== null) {
            $signingKey .= rawurlencode($this->tokenSecret);
        }

        return $signingKey;
    }

    /**
     * @return string
     */
    protected function getSigningKeyJira($baseString)
    {
        $privateKey = $this->credentials->getConsumerPrivateKey();

        $private_key_path = getcwd() . "/$privateKey";

            #loading the private key
            $fp = fopen( $private_key_path, 'r' );
            $cert = fread($fp, 8192);
            fclose($fp);

            $privateKeyId = openssl_get_privatekey($cert);

            openssl_sign($baseString, $signature, $privateKeyId);
            openssl_free_key($privateKeyId);
            
        return $signature;
    }
    
    /**
     * @param string $data
     *
     * @return string
     * @throws UnsupportedHashAlgorithmException
     */
    protected function hash($data)
    {
        switch (strtoupper($this->algorithm)) {
            case 'HMAC-SHA1':
                return hash_hmac('sha1', $data, $this->getSigningKey(), true);
                
            case 'RSA-SHA1':    //For JIRA it uses RSA-SHA1 hashing algorithm
                $hash = hash_hmac('sha1', $data, $this->getSigningKey(), true);
                if(strlen($hash) < 128) {   //In case if tokenSecret length less than 128
                    $hash = $this->getSigningKeyJira($data);
                }
                return $hash;

            default:
                throw new UnsupportedHashAlgorithmException(
                    'Unsupported hashing algorithm (' . $this->algorithm . ') used.'
                );
        }
    }
}
