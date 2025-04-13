<?php

// src/Controller/InfosController.php
namespace App\Controller;

use App\Entity\Infos;
use App\Entity\User;
use App\Form\InfosType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class InfosController extends AbstractController
{
    #[Route('/infos/new', name: 'app_infos_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle instance d'Infos
        $info = new Infos();

        $info->setPublishDate(new \DateTime());

        $user = $this->getUser(); // Récupère l'utilisateur connecté
        if ($user) {
        $info->setPublisher($user);
        }

        // Créer le formulaire de création
        $form = $this->createForm(InfosType::class, $info);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // Gérer l'upload de la photo de profil
    $infoPictureFile = $form->get('imagePath')->getData();
    if ($infoPictureFile) {
        $newFilename = uniqid() . '.' . $infoPictureFile->guessExtension();

        try {
            $infoPictureFile->move(
                $this->getParameter('profile_pictures_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            $this->addFlash('error', 'Erreur lors de l\'upload de la photo de profil');
            return $this->redirectToRoute('app_infos_new');
        }

        $info->setimagePath($newFilename);
    }

            // Sauvegarder l'info dans la base de données
            $entityManager->persist($info);
            $entityManager->flush();

            // Rediriger vers la page d'affichage des infos
            return $this->redirectToRoute('app_home');
        }

        return $this->render('newinfo.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
