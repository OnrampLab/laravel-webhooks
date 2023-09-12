<?php

namespace OnrampLab\Webhooks\ValueObjects;

use JsonSerializable;

class ExclusionCriterion implements JsonSerializable
{
    public string $name;

    public array $values;

    /**
     * create object
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->values = $data['values'];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'values' => $this->values,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
