<?php

namespace Autopilot;

use JsonSerializable;

class AutopilotContact implements JsonSerializable
{
    /**
     * All fields
     */
    protected array $fields;

    /**
     * List of ids that the user is a part of
     */
    protected array $lists;

    public function __construct(array $options = [])
    {
        $this->fields = [];

        $this->lists = [];

        $this->fill($options);
    }

    /**
     * Getter for contact properties
     *
     * @param $name
     *
     * @return string|null
     */
    public function __get($name)
    {
        return $this->getFieldValue($name);
    }

    /**
     * Setter for contact properties
     *
     * @param $name
     * @param $value
     *
     * @return string|null
     */
    public function __set($name, $value)
    {
        return $this->setFieldValue($name, $value);
    }

    /**
     * Check if contact property is set
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->issetFieldValue($name);
    }

    /**
     * Unsetter for contact properties
     *
     * @param $name
     */
    public function __unset($name)
    {
        $this->unsetFieldValue($name);
    }

    /**
     * @param $name
     *
     * @return string|null
     */
    public function getFieldValue($name): ?string
    {
        $name = AutopilotField::getFieldName($name);

        // no validation on "getValue()" required since set internally
        return isset($this->fields[$name]) ? $this->fields[$name]->getValue() : null;
    }

    public function setFieldValue($name, $value): ?string
    {
        $name = AutopilotField::getFieldName($name);

        /** @var AutopilotField $field */
        if (! isset($this->fields[$name])) {
            $field = new AutopilotField($name, $value);
            $this->fields[$name] = $field;
        } else {
            $this->fields[$name]->setValue($value);
        }

        return $this->fields[$name]->getValue();
    }

    /**
     * Remove field
     */
    public function unsetFieldValue($name): void
    {
        $name = AutopilotField::getFieldName($name);

        unset($this->fields[$name]);
    }

    /**
     * Check if contact object contains field
     */
    public function issetFieldValue($name): bool
    {
        $name = AutopilotField::getFieldName($name);

        return isset($this->fields[$name]);
    }

    /**
     * Get all lists (cache, not an API call)
     */
    public function getAllContactLists(): array
    {
        return $this->lists;
    }

    /**
     * Check if is member of list (cache, not API call)
     */
    public function hasList($list): bool
    {
        return in_array($list, $this->lists, true);
    }

    /**
     * For each item, add appropriate field with value
     */
    public function fill(array $options = []): static
    {
        foreach($options as $key => $value) {
            if ($key === 'custom_fields') {
                foreach($value as $custom) {
                    $field = new AutopilotField($custom['kind'], $custom['value'], $custom['fieldType']);
                    $this->fields[$field->getName()] = $field;
                }
            } elseif ($key === 'lists') {
                $this->lists = $value;
            } elseif (!is_array($value)) {
                $field = new AutopilotField($key, $value);
                $this->fields[$field->getName()] = $field;
            }
        }

        return $this;
    }

    /**
     * Prepare an array for the API call
     */
    public function toRequest(bool $prependKey = true): array
    {
        $result = [
            'custom' => []
        ];

        /** @var AutopilotField $field */
        foreach($this->fields as $field) {
            if (! $field->isReserved()) {
                $result['custom'][$field->formatName()] = $field->getValue();
            } else {
                $result[$field->formatName()] = $field->getValue();
            }
        }

        // if not custom values, remove unnecessary key
        if (count($result['custom']) === 0) {
            unset($result['custom']);
        }

        return $prependKey ? ['contact' => $result] : $result;
    }

    /**
     * Return all fields and their values
     */
    public function toArray(): array
    {
        $result = [];

        /** @var AutopilotField $field */
        foreach($this->fields as $field) {
            $result[$field->getName()] = $field->getValue();
        }

        return $result;
    }

    /**
     * Return json of all fields and their values
     */
    function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
