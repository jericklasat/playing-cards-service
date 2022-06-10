<?php

declare(strict_types=1);

namespace App\Controller;

use App\services\CardInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/card")
 */
class CardController extends AbstractController
{
    public function __construct(
        private CardInterface $service,
    ){}

    /**
     * @Route("/distribute", methods={"POST"})
     */
    public function distribute(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $response = $this->service->distribute(intval($data['playerCount']));

        return $this->json($response);
    }
}
