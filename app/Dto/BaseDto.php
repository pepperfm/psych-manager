<?php

namespace App\Dto;

use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use ReturnTypeWillChange;

abstract class BaseDto implements JsonSerializable
{
    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $key => $param) {
            if (property_exists($this, $key)) {
                $this->$key = $param;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return (array) $this;
    }

    /**
     * @inheritDoc
     */
    #[ReturnTypeWillChange] #[Pure] public function jsonSerialize()
    {
        return $this->toArray();
    }
}
