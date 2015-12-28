<?php

/*
 * This file is part of the php-oauth package <https://github.com/logical-and/php-oauth>.
 *
 * (c) Oryzone, developed by Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OAuth\UserData;

use OAuth\Common\Service\ServiceInterface;
use OAuth\UserData\Exception\InvalidExtractorException;
use OAuth\UserData\Exception\UndefinedExtractorException;
use OAuth\UserData\Extractor\ExtractorInterface;

/**
 * Class ExtractorFactory
 *
 * @package OAuth\UserData
 */
class ExtractorFactory implements ExtractorFactoryInterface
{

    /**
     * @var array $extractorsMap
     */
    protected $extractorsMap;

    /**
     * Constructor
     *
     * @param array $extractorsMap
     */
    public function __construct($extractorsMap = [])
    {
        $this->extractorsMap = $extractorsMap;
    }

    /**
     * {@inheritDoc}
     */
    public function get(ServiceInterface $service)
    {
        // Check in extractors map
        $serviceFullyQualifiedClass = get_class($service);
        if (isset($this->extractorsMap[ $serviceFullyQualifiedClass ])) {
            $extractorsClass = $this->extractorsMap[ $serviceFullyQualifiedClass ];
        } else {
            $extractorsClass = $this->searchExtractorClassInLib($serviceFullyQualifiedClass);
        }

        if (null === $extractorsClass) {
            throw new UndefinedExtractorException($service, array_keys($this->extractorsMap));
        }

        return $this->buildExtractor($service, $extractorsClass);
    }

    /**
     * Adds a new extractor to the extractorsMap
     *
     * @param string $serviceFullyQualifiedClass
     * @param string $extractorClass
     */
    public function addExtractorMapping($serviceFullyQualifiedClass, $extractorClass)
    {
        $this->extractorsMap[ $serviceFullyQualifiedClass ] = $extractorClass;
    }

    /**
     * Search a mapping on the fly by inspecting the library code
     *
     * @param  string $serviceFullyQualifiedClass
     *
     * @return null|string
     */
    protected function searchExtractorClassInLib($serviceFullyQualifiedClass)
    {
        $parts = explode('\\', $serviceFullyQualifiedClass);
        $className = $parts[ sizeof($parts) - 1 ];

        $extractorClass = sprintf('\OAuth\UserData\Extractor\%s', $className);
        if (class_exists($extractorClass)) {
            return $extractorClass;
        }

        return null;
    }

    /**
     * @param  ServiceInterface $service
     * @param  string $extractorClass
     *
     * @return ExtractorInterface
     * @throws Exception\InvalidExtractorException
     */
    protected function buildExtractor(ServiceInterface $service, $extractorClass)
    {
        $extractor = new $extractorClass;

        if (!$extractor instanceof ExtractorInterface) {
            throw new InvalidExtractorException($extractorClass);
        }

        $extractor->setService($service);

        return $extractor;
    }
}
