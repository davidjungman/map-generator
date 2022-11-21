<?php

namespace App\Controller;

use App\Service\MapGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    public function __construct(
        private readonly MapGenerator $mapGenerator
    ) {
    }

    #[Route('/', name: 'map')]
    public function generate(): Response
    {
        $map = $this->mapGenerator->generate(32, 40);

        return $this->render('index.html.twig', [
            'map' => $map
        ]);
    }
}