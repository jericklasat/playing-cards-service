<?php

namespace App\services;

interface CardInterface
{
    public function distribute(int $playerCount): array;
}