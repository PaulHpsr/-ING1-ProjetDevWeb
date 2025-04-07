<?php

// src/Controller/InfosController.php
namespace App\Controller;

use App\Entity\Infos;
use App\Form\InfosType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InfosController extends AbstractController
{
    #[Route('/infos/new', name: 'app_infos_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle instance d'Infos
        $info = new Infos();

        // Créer le formulaire de création
        $form = $this->createForm(InfosType::class, $info);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imagePath')->getData();

            if ($file) {
                $newFilename = uniqid() . '.' . $file->guessExtension();
                // Déplacer le fichier dans le répertoire des images avec gestion d'exception
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    // Enregistrer le nom du fichier dans l'entité Infos
                    $info->setImagePath($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', "Une erreur s'est produite lors du téléchargement de l'image.");
                    return $this->redirectToRoute('app_infos_new');
                }
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
