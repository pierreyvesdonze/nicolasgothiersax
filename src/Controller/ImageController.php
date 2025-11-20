<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/image')]
final class ImageController extends AbstractController
{
    #[Route(name: 'app_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFiles = $form->get('path')->getData(); // tableau de fichiers

            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $uploadedFile) {

                    if (!$uploadedFile) {
                        continue;
                    }

                    $extension = $uploadedFile->guessExtension()
                        ?: $uploadedFile->getClientOriginalExtension()
                        ?: 'png';

                    $newFilename = uniqid() . '.' . $extension;

                    $destination = $this->getParameter('images_directory');
                    $uploadedFile->move($destination, $newFilename);

                    $image = new Image();
                    $image->setPath($newFilename);

                    $entityManager->persist($image);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('image/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        $oldImage = $image->getPath();

        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('path')->getData();

            if ($imagePath) {
                $newFilename = uniqid() . '.' . $imagePath->guessExtension();
                $destination = $this->getParameter('images_directory');
                $imagePath->move($destination, $newFilename);

                if ($oldImage && file_exists($this->getParameter('images_directory') . '/' . $oldImage)) {
                    unlink($this->getParameter('images_directory') . '/' . $oldImage);
                }

                $image->setPath($newFilename);
            } else {
                $image->setPath($oldImage);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_image_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
