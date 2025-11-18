<?php

namespace App\Controller;

use App\Entity\Degree;
use App\Form\DegreeType;
use App\Repository\DegreeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/degree')]
final class DegreeController extends AbstractController
{
    #[Route(name: 'app_degree_index', methods: ['GET'])]
    public function index(DegreeRepository $degreeRepository): Response
    {
        return $this->render('degree/index.html.twig', [
            'degrees' => $degreeRepository->findAllOrderedByDateDesc(),
        ]);
    }

    #[Route('/new', name: 'app_degree_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $degree = new Degree();
        $form = $this->createForm(DegreeType::class, $degree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($degree);
            $entityManager->flush();

            return $this->redirectToRoute('app_degree_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('degree/new.html.twig', [
            'degree' => $degree,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_degree_show', methods: ['GET'])]
    public function show(Degree $degree): Response
    {
        return $this->render('degree/show.html.twig', [
            'degree' => $degree,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_degree_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Degree $degree, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DegreeType::class, $degree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_degree_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('degree/edit.html.twig', [
            'degree' => $degree,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_degree_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Degree $degree, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$degree->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($degree);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_degree_index', [], Response::HTTP_SEE_OTHER);
    }
}
