<?php

declare(strict_types=1);

namespace App\models;

class Player implements \JsonSerializable
{
    /**
     * @param Card[] $cards
     */
    public function __construct(
        private string $name,
        private array  $cards,
    ){}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function jsonSerialize()
    {
        return [
          'name' => $this->name,
          'cards' => $this->cards,
        ];
    }
}