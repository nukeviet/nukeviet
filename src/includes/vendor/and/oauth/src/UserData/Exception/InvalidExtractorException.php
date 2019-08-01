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

/**
 * Class InvalidExtractorException
 *
 * @package OAuth\UserData\Exception
 */
class InvalidExtractorException extends \Exception implements Exception
{

    /**
     * @var string $serviceName
     */
    protected $extractorClass;

    /**
     * Constructor
     *
     * @param string $extractorClass
     * @param string|null $message
     */
    public function __construct($extractorClass, $message = null)
    {
        $this->extractorClass = $extractorClass;
        if (null === $message) {
            $message = sprintf(
                'The class "%s" does not implement the interface OAuth\UserData\Extractor\ExtractorInterface',
                $extractorClass
            );
        }
        parent::__construct($message);
    }

    /**
     * Get the service name
     *
     * @return string
     */
    public function getExtractorClass()
    {
        return $this->extractorClass;
    }
}
