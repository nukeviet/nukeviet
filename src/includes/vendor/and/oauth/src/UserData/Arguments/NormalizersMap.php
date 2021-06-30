<?php

namespace OAuth\UserData\Arguments;

use OAuth\UserData\Exception\GenericException;

class NormalizersMap extends AbstractArgument
{

    const TYPE_METHOD = 1;
    const TYPE_ARRAY_PATH = 2;
    const TYPE_PREFILLED_VALUE = 3;

    protected $contextPath = '';
    protected $fields = [];

    /**
     * Construct NormalizersMap and set fields methods
     *
     * @param array $fields
     *
     * @return static
     */
    public static function forMethods(array $fields)
    {
        $prepFields = [];

        foreach ($fields as $field) {
            $prepFields[ ] = '::' . $field;
        }

        return new static($prepFields);
    }

    public function __construct(array $fields = [])
    {
        if ($fields) {
            $this->set($fields);
        }
    }

    // -- Accessors

    /**
     * Parse array and set fields.
     *
     * @see NormalizersMap::add
     *
     * @param array $fields
     *
     * @return $this
     */
    public function set(array $fields)
    {
        $this->fields = [];

        return $this->add($fields);
    }

    /**
     * Parse array and add fields. Available formats: <pre>
     * 'field' => ['field.path', 'defaultValue'],
     * 'field' => '::method',
     * 'field' => 'field.path'
     * </pre>
     *
     * @param array $fields
     *
     * @return $this
     * @throws GenericException
     */
    public function add(array $fields)
    {
        foreach ($fields as $field => $normalizer) {
            if (is_array($normalizer)) {
                // 'field' => ['field.path', 'defaultValue']
                if (2 == count($normalizer)) {
                    $this->path($field, $normalizer[ 0 ], $normalizer[ 1 ]);
                } else {
                    throw new GenericException('Array can contain only 2 elements!');
                }
            } // 'field' => '::method'
            elseif (0 === strpos($normalizer, '::')) {
                $method = str_replace('::', '', $normalizer);
                if (is_int($field)) {
                    $field = $method;
                }
                $this->method($field, $method);
            } // 'field' => 'field.path'
            else {
                $this->path($field, $normalizer);
            }
        }

        return $this;
    }

    /**
     * Add field method
     *
     * @param $field
     * @param null $methodName
     *
     * @return $this
     * @throws GenericException
     */
    public function method($field, $methodName = null)
    {
        if (!$methodName) {
            $methodName = $field;
        }

        if (!is_string($field)) {
            throw new GenericException('Must be a string!');
        }
        if (!is_string($methodName)) {
            throw new GenericException('Must be a string!');
        }

        $this->fields[ $field ] = [
            'type'   => self::TYPE_METHOD,
            'method' => $methodName
        ];

        return $this;
    }

    /**
     * Add fields methods
     *
     * @param array $fieldsMethods
     *
     * @return $this
     */
    public function methods(array $fieldsMethods)
    {
        foreach ($fieldsMethods as $field => $path) {
            $this->method($field, $path);
        }

        return $this;
    }

    /**
     * Add field path
     *
     * @param $field
     * @param $path
     * @param null $defaultValue
     *
     * @return $this
     * @throws GenericException
     */
    public function path($field, $path, $defaultValue = null)
    {
        if (!is_string($field)) {
            throw new GenericException('Must be a string!');
        }
        if (!is_string($path)) {
            throw new GenericException('Must be not empty string!');
        }

        $this->fields[ $field ] = [
            'type'               => self::TYPE_ARRAY_PATH,
            'path'               => $this->contextPath . $path,
            'defaultValue'       => $defaultValue,
            'contextPath'        => $this->contextPath,
            'pathWithoutContext' => $path
        ];

        return $this;
    }

    /**
     * Add fields paths
     *
     * @param array $fieldPaths
     *
     * @return $this
     */
    public function paths(array $fieldPaths)
    {
        foreach ($fieldPaths as $field => $path) {
            if (is_array($path)) {
                $this->path($field, $path[ 0 ], $path[ 1 ]);
            } else {
                $this->path($field, $path);
            }
        }

        return $this;
    }

    /**
     * Not normalized, accessor returns loaded data
     *
     * @param $field
     *
     * @return $this
     */
    public function noNormalizer($field)
    {
        unset($this->fields[ $field ]);

        return $this;
    }

    /**
     * Only return value if data loaded
     *
     * @param $field
     * @param $value
     *
     * @return $this
     */
    public function prefilled($field, $value)
    {
        $this->fields[ $field ] = [
            'type'  => self::TYPE_PREFILLED_VALUE,
            'value' => $value
        ];

        return $this;
    }

    // -- Search

    /**
     * Get normalizer for field
     *
     * @param $field
     *
     * @return bool|array Array if found or false otherwise
     */
    public function getNormalizerForField($field)
    {
        if (!empty($this->fields[ $field ])) {
            return $this->fields[ $field ];
        } else {
            return false;
        }
    }

    public function getNormalizersForType($type)
    {
        $normalizers = [];

        foreach ($this->fields as $field => $normalizer) {
            if ($type == $normalizer[ 'type' ]) {
                $normalizers[ $field ] = $normalizer;
            }
        }

        return $normalizers;
    }

    public function getMethodNormalizers()
    {
        return $this->getNormalizersForType(self::TYPE_METHOD);
    }

    public function getPathNormalizers()
    {
        return $this->getNormalizersForType(self::TYPE_ARRAY_PATH);
    }

    // -- Context

    /**
     * Set path context. All next fields will be added with prepended path
     *
     * @see NormalizersMap::prependByPathContext
     *
     * @param $pathContext
     *
     * @return $this
     * @throws \OAuth\UserData\Exception\GenericException
     */
    public function pathContext($pathContext)
    {
        if (!is_string($pathContext)) {
            throw new GenericException('Must be a string!');
        }

        $this->contextPath = ltrim($pathContext . '.', '.');

        return $this;
    }

    /**
     * Prepend all added fields paths
     *
     * @param $pathContext
     *
     * @return $this
     */
    public function prependByPathContext($pathContext)
    {
        $this->pathContext($pathContext);

        foreach ($this->getPathNormalizers() as $field => $normalizer) {
            $this->path($field, $normalizer[ 'pathWithoutContext' ], $normalizer[ 'defaultValue' ]);
        }

        return $this;
    }

    /**
     * Get last path context
     *
     * @return string
     */
    public function getPathContext()
    {
        return $this->contextPath;
    }
}
