<?php

namespace OAuth\UserData\Arguments;

class FieldsValues extends AbstractArgument
{

    protected $supports = [];
    protected $values = [];

    /**
     * @see FieldsValues::setFieldsWithValues
     *
     * @param array $fieldsValues
     */
    public function __construct(array $fieldsValues = [])
    {
        if ($fieldsValues) {
            $this->setFieldsWithValues($fieldsValues);
        }
    }

    /**
     * Allowed arguments: <pre>
     * // just fields list
     * ['field1', 'field2'],
     * // or fields with default values
     * ['field1' => 'value1', 'field2' => 'value2',
     * // or it's combination
     * ['field1', 'field2' => 'value2']
     * </pre>
     *
     * @param array $fieldsValues
     *
     * @return $this
     */
    public function setFieldsWithValues(array $fieldsValues)
    {
        $this->supports = [];
        $this->values = [];

        foreach ($fieldsValues as $field => $value) {
            if (is_int($field)) {
                $field = $value;
                $value = null;
            }

            $this->supports[ ] = $field;
            if (!is_null($value)) {
                $this->values[ $field ] = $value;
            }
        }

        return $this;
    }

    /**
     * Set field default value
     *
     * @param $field
     * @param $value
     *
     * @return $this
     */
    public function fieldValue($field, $value)
    {
        if (!in_array($field, $this->supports)) {
            $this->supports[ ] = $field;
        }
        $this->values[ $field ] = $value;

        return $this;
    }

    public function getFieldsValues()
    {
        return $this->values;
    }

    public function getSupportedFields()
    {
        return $this->supports;
    }
}
