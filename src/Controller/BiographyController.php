<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BiographyController extends AbstractController
{
    #[Route('/biography', name: 'app_biography')]
    public function index(): Response
    {
        return $this->render('biography/index.html.twig', [
            'controller_name' => 'BiographyController',
        ]);
    }
}
