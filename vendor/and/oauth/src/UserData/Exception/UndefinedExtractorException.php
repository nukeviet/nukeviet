<?php

/*
 * This file is part of the php-oauth package <https://github.com/logical-and/php-oauth>.
 *
 * (c) Oryzone, developed by Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OAuth\UserData\Exception;

use OAuth\Common\Service\ServiceInterface;

/**
 * Class UnmatchedExtractorException
 *
 * @package OAuth\UserData\Exception
 */
class UndefinedExtractorException extends \Exception implements Exception
{

    /**
     * @var \OAuth\Common\Service\ServiceInterface $service
     */
    protected $service;

    /**
     * @var array $registeredExtractors
     */
    protected $registeredExtractors;

    /**
     * Constructor
     *
     * @param \OAuth\Common\Service\ServiceInterface $service
     * @param array $registeredExtractors
     * @param string|null $message
     */
    public function __construct(ServiceInterface $service, $registeredExtractors = [], $message = null)
    {
        $this->service = $service;
        $this->registeredExtractors = $registeredExtractors;
        if (null === $message) {
            $message = sprintf(
                'Cannot find an extractor for the service "%s". Registered extractors: %s',
                get_class($service),
                json_encode($registeredExtractors)
            );
        }
        parent::__construct($message);
    }

    /**
     * Get the service
     *
     * @return ServiceInterface
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get registered extractors
     *
     * @return array
     */
    public function getRegisteredExtractors()
    {
        return $this->registeredExtractors;
    }
}
