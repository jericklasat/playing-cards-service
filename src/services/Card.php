<?php

declare(strict_types=1);

namespace App\services;

use App\models\Player;
use App\models\Card as CardModel;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Card implements CardInterface
{
    const NUMBER_OF_CARD = 13;
    const TYPE_OF_CARD = 4;

    /**
     * @param int $playerCount
     * @return Player[]
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function distribute(int $playerCount): array
    {
        $cardsPerPlayers = [];
        $distributedCardsPerPlayers = [];
        $cards = $this->generateCards();
        $numbersPerPlayers = $this->assignRandomNumberPerPlayer($playerCount);

        foreach ($numbersPerPlayers as $playerNumber=>$cardNumbers) {
            // If the player count exceeded number of cards
            // Player is still included in the list but with empty card.
            if (empty($cardNumbers)) {
                $cardsPerPlayers[$playerNumber] = [];

                continue;
            }

            foreach ($cardNumbers as $cardNumber) {
                $cardsPerPlayers[$playerNumber][] = $cards[$cardNumber];
            }
        }

        foreach ($cardsPerPlayers as $playerNumber=>$cardsPerPlayer) {
            $distributedCardsPerPlayers[] = new Player('Player ' . $playerNumber, $cardsPerPlayer);
        }

        return $distributedCardsPerPlayers;
    }

    /**
     * @return CardModel[]
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function generateCards(): array
    {
        $cache = new FilesystemAdapter();
        $cachedCard = $cache->getItem('cards');

        if ($cachedCard->isHit()) {
            return $cachedCard->get();
        }

        $data = [];
        $suites = ['S', 'H', 'D', 'C'];
        $letterValue = [1 => 'A', 10 => 'X', 11 => 'J', 12 => 'Q', 13 => 'K'];

        foreach ($suites as $suite) {
            for ($cardNumber = 1; $cardNumber <= self::NUMBER_OF_CARD; $cardNumber++) {
                $value = ($cardNumber === 1 || $cardNumber >= 10) ? $letterValue[$cardNumber] : (string) $cardNumber;
                $data[] = new CardModel($value, $suite);
            }
        }

        $cachedCard->set($data);
        $cache->save($cachedCard);

        return $data;
    }

    /**
     * @param int $playerCount
     * @return int[][]
     */
    private function assignRandomNumberPerPlayer(int $playerCount): array
    {
        $numbersPerPlayer = [];
        $totalCardsCount = self::NUMBER_OF_CARD * self::TYPE_OF_CARD;
        $randomTotalCardsCount = range(1, $totalCardsCount);

        for ($playerNumber = 1; $playerNumber <= $playerCount; $playerNumber++) {
            for ($cardNumber = 1; $cardNumber <= round(($totalCardsCount / $playerCount)); $cardNumber++) {
                if (! isset($numbersPerPlayer[$playerNumber])) {
                    $numbersPerPlayer[$playerNumber] = [];
                }

                if (empty($randomTotalCardsCount)) {
                    continue;
                }

                $randomNumber = array_rand($randomTotalCardsCount);
                $numbersPerPlayer[$playerNumber][] = $randomNumber;

                unset($randomTotalCardsCount[$randomNumber]);
            }
        }

        return $numbersPerPlayer;
    }
}