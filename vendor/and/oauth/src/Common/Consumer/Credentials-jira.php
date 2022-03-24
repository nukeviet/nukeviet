<?php

namespace OAuth\Common\Consumer;

/**
 * Value object for the credentials of an OAuth service.
 */
class Credentials implements CredentialsInterface
{

    /**
     * @var string
     */
    protected $consumerId;

    /**
     * @var string
     */
    protected $consumerSecret;
    
    /**
     * @var string
     */
    protected $consumerPrivateKey;

    /**
     * @var string
     */
    protected $callbackUrl;

    /**
     * @param string $consumerId
     * @param string $consumerSecret
     * @param string $callbackUrl
     */
    public function __construct($consumerId, $consumerSecret, $callbackUrl, $consumerPrivateKey = NULL)
    {
        $this->consumerId = $consumerId;
        $this->consumerSecret = $consumerSecret;
        $this->consumerPrivateKey = $consumerPrivateKey;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * @return string
     */
    public function getCallbackUrl()
    {
        return (string) $this->callbackUrl;
    }

    /**
     * @return string
     */
    public function getConsumerId()
    {
        return $this->consumerId;
    }

    /**
     * @return string
     */
    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }
    
    /**
     * @return string
     */
    public function getConsumerPrivateKey()
    {
        return $this->consumerPrivateKey;
    }
}
