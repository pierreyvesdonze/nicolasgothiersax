<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/concert')]
final class ConcertController extends AbstractController
{
    #[Route(name: 'app_concert_index', methods: ['GET'])]
    public function index(ConcertRepository $concertRepository): Response
    {
        return $this->render('concert/index.html.twig', [
            'concerts' => $concertRepository->findAllOrderedByDateAsc(),
        ]);
    }

    #[Route('/new', name: 'app_concert_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $concert = new Concert();
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($concert);
            $entityManager->flush();

            return $this->redirectToRoute('app_concert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('concert/new.html.twig', [
            'concert' => $concert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_concert_show', methods: ['GET'])]
    public function show(Concert $concert): Response
    {
        return $this->render('concert/show.html.twig', [
            'concert' => $concert,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_concert_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Concert $concert, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_concert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('concert/edit.html.twig', [
            'concert' => $concert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_concert_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Concert $concert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $concert->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($concert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_concert_index', [], Response::HTTP_SEE_OTHER);
    }
}
