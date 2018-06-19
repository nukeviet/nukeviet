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
use OAuth\UserData\Arguments\LoadersMap;
use OAuth\UserData\Arguments\NormalizersMap;
use OAuth\UserData\Exception\GenericException;
use OAuth\UserData\Utils\ArrayUtils;

/**
 * Class LazyExtractor
 *
 * @package OAuth\UserData\Extractor
 */
class LazyExtractor extends Extractor
{

    /**
     * @var LoadersMap $loadersMap
     */
    protected $loadersMap;

    /**
     * @var array $normalizersMap
     */
    protected $normalizersMap;

    /**
     * @var array $loadersResults
     */
    protected $loadersResults;

    /**
     * Constructor
     *
     * @param FieldsValues $fieldsData
     * @param NormalizersMap $normalizersMap
     * @param LoadersMap $loadersMap
     */
    public function __construct(
        FieldsValues $fieldsData = null,
        NormalizersMap $normalizersMap = null,
        LoadersMap $loadersMap = null
    ) {
        parent::__construct($fieldsData);

        if (!$normalizersMap) {
            $normalizersMap = self::getDefaultNormalizersMap();
        }
        if (!$loadersMap) {
            $loadersMap = self::getDefaultLoadersMap();
        }

        $this->loadersMap = $loadersMap;
        $this->normalizersMap = $normalizersMap;

        $this->loadersResults = [];
    }

    /**
     * {@inheritDoc}
     * @param string $field
     */
    public function getField($field)
    {
        if (!$this->isFieldSupported($field)) {
            return null;
        }

        if (!$this->hasLoadedField($field)) {
            $loaderData = $this->getLoaderData($field);
            if ($normalizer = $this->normalizersMap->getNormalizerForField($field)) {
                switch ($normalizer[ 'type' ]) {
                    case NormalizersMap::TYPE_METHOD:
                        $this->fields[ $field ] =
                            $this->{sprintf('%sNormalizer', $normalizer[ 'method' ])}($loaderData);
                        break;

                    case NormalizersMap::TYPE_ARRAY_PATH:
                        $this->fields[ $field ] =
                            ArrayUtils::getNested($loaderData, $normalizer[ 'path' ], $normalizer[ 'defaultValue' ]);
                        break;

                    case NormalizersMap::TYPE_PREFILLED_VALUE:
                        $this->fields[ $field ] = $normalizer[ 'value' ];
                        break;

                    default:
                        throw new GenericException("Unknown normalizer type \"{$normalizer['type']}\"");
                }

                switch ($field) {
                    case self::FIELD_WEBSITES:
                    case self::FIELD_EXTRA:
                        if ($this->fields[ $field ]) {
                            $this->fields[ $field ] = (array) $this->fields[ $field ];
                        } else {
                            $this->fields[ $field ] = [];
                        }
                        break;
                }
            } // Don't need to be normalized
            else {
                $this->fields[ $field ] = $loaderData;
            }
        }

        return parent::getField($field);
    }

    /**
     * Check if already loaded a given field
     *
     * @param  string $field
     *
     * @return bool
     */
    protected function hasLoadedField($field)
    {
        return array_key_exists($field, $this->fields);
    }

    /**
     * Get data from a loader.
     * A loader is a function who is delegated to fetch a request to get the raw data
     *
     * @param  string $field
     *
     * @return mixed
     */
    protected function getLoaderData($field)
    {
        $loaderName = $this->loadersMap->getLoaderForField($field);
        if (!isset($this->loadersResults[ $loaderName ])) {
            $this->loadersResults[ $loaderName ] = $this->{sprintf('%sLoader', $loaderName)}();
        }

        return $this->loadersResults[ $loaderName ];
    }

    /**
     * Get a default map of loaders
     *
     * @return LoadersMap
     */
    protected static function getDefaultLoadersMap()
    {
        return LoadersMap::construct(['profile' => self::getAllFields()->getSupportedFields()]);
    }

    /**
     * Get a default normalizers map
     *
     * @return NormalizersMap
     */
    protected static function getDefaultNormalizersMap()
    {
        return NormalizersMap::forMethods(self::getAllFields()->getSupportedFields());
    }

    // -- Generic implementations

    /**
     * Generic "extra normalizer"
     *
     * @param $data
     * @param string $path To be overridden
     *
     * @return array
     */
    protected function extraNormalizer($data, $path = '')
    {
        if (is_array($data)) {
            if (!$path) {
                $path = $this->normalizersMap->getPathContext();
            }
            $path = trim($path, '.');

            $pathsFields = [];
            foreach ($this->normalizersMap->getPathNormalizers() as $normalizer) {
                $pathsFields[ ] = $normalizer[ 'pathWithoutContext' ];
            }

            // Remove all paths fields
            return ArrayUtils::removeKeys(ArrayUtils::getNested($data, $path, []), $pathsFields);
        }

        return [];
    }
}
