<?php

namespace OAuth\UserData\Arguments;

use OAuth\UserData\Exception\GenericException;

class LoadersMap extends AbstractArgument
{

    protected $loaders = [];

    protected $contextLoader = '';

    public function __construct(array $set = [])
    {
        if ($set) {
            $this->set($set);
        }
    }

    /**
     * Format: [ 'loader' => [ 'field1', 'field2' ] ]
     *
     * @param array $set
     *
     * @return $this
     */
    public function set(array $set)
    {
        foreach ($set as $loader => $fields) {
            $this->loader($loader)->fields($fields);
        }

        // Disable
        $this->contextLoader = false;

        return $this;
    }

    /**
     * Set context loader
     *
     * @param $loader
     *
     * @throws GenericException
     * @return $this
     */
    public function loader($loader)
    {
        if (!is_string($loader)) {
            throw new GenericException('Must be string!');
        }
        $this->contextLoader = $loader;

        return $this;
    }

    /**
     * Add field to context loader
     *
     * @param string $field
     *
     * @throws GenericException
     * @return $this
     */
    public function addField($field)
    {
        $this->checkValidLoader();

        if (!is_string($field)) {
            throw new GenericException('Must be string!');
        }
        $this->loaders[ $this->contextLoader ][ ] = $field;

        return $this;
    }

    /**
     * Add fields to context loader
     *
     * @param array $fields
     *
     * @return $this
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    /**
     * Set fields to context loader
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->checkValidLoader();

        // Flatten
        $this->loaders[ $this->contextLoader ] = [];
        $this->addFields($fields);

        return $this;
    }

    /**
     * Set fields to context loader
     *
     * @param array $fields
     *
     * @return $this
     */
    public function fields(array $fields)
    {
        return $this->setFields($fields);
    }

    /**
     * Remove field from context loader
     *
     * @param string $field
     *
     * @return $this
     */
    public function removeField($field)
    {
        $this->checkValidLoader();

        if (in_array($field, $this->loaders[ $this->contextLoader ])) {
            array_splice(
                $this->loaders[ $this->contextLoader ],
                array_search($field, $this->loaders[ $this->contextLoader ])
            );
        }

        return $this;
    }

    /**
     * Remove fields from context loader
     *
     * @param array $fields
     *
     * @return $this
     */
    public function removeFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->removeField($field);
        }

        return $this;
    }

    /**
     * Add field to context loader and remove field from any other loaders
     *
     * @param $field
     *
     * @return $this
     */
    public function readdField($field)
    {
        $this->checkValidLoader();

        foreach ($this->loaders as $loader => $fields) {
            if (in_array($field, $this->loaders[ $loader ])) {
                array_splice($this->loaders[ $loader ], array_search($field, $this->loaders[ $loader ]), 1);
            }
        }

        $this->addField($field);

        return $this;
    }

    /**
     * Readd multiple fields at once
     *
     * @param array $fields
     *
     * @return $this
     */
    public function readdFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->readdField($field);
        }

        return $this;
    }

    /**
     * Get loader byfield name
     *
     * @param string $searchField
     *
     * @return int|string
     * @throws \OAuth\UserData\Exception\GenericException
     */
    public function getLoaderForField($searchField)
    {
        foreach ($this->loaders as $loader => $fields) {
            if (in_array($searchField, $fields, true)) {
                return $loader;
            }
        }

        throw new GenericException("Unable to find loader for field \"$searchField\"");
    }

    protected function checkValidLoader()
    {
        if (!$this->contextLoader) {
            throw new GenericException('Context loader is not defined!');
        }

        if (!isset($this->loaders[ $this->contextLoader ])) {
            $this->loaders[ $this->contextLoader ] = [];
        }
    }
}
