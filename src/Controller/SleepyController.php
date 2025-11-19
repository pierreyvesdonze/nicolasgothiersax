<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SleepyController extends AbstractController
{
    #[Route('/sleepy', name: 'app_sleepy')]
    public function index(): Response
    {
        return $this->render('sleepy/index.html.twig', [
            'controller_name' => 'SleepyController',
        ]);
    }
}
