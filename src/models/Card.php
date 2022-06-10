<?php

declare(strict_types=1);

namespace App\models;

class Card implements \JsonSerializable
{
    public function __construct(
        private string $value,
        private string $suite,
    ){}

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getSuite(): string
    {
        return $this->suite;
    }

    public function jsonSerialize()
    {
        return [
            'value' => $this->value,
            'suite' => $this->suite,
        ];
    }
}