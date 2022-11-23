<?php

namespace App\Controller;

use App\Service\MapGenerator;
use App\Service\Render\Renderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    public function __construct(
        private readonly MapGenerator $mapGenerator,
        private readonly Renderer $renderer
    ) {
    }

    #[Route('/', name: 'map')]
    public function generate(): Response
    {
        $map = $this->mapGenerator->generate(30, 30);

        return $this->render('index.html.twig', [
            'html' => $this->renderer->render($map)
        ]);
    }
}