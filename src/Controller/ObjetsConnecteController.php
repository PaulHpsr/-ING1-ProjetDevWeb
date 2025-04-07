<?php

// src/Controller/ObjetConnecteController.php
namespace App\Controller;

use App\Entity\ObjetsConnectes;
use App\Form\ObjetsConnectesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ObjetsConnecteController extends AbstractController
{
    // Liste des objets connectés
    #[Route('/objets', name: 'objets_connectes')]
    public function listObjects(EntityManagerInterface $entityManager): Response
    {
        $objetsConnectes = $entityManager->getRepository(ObjetsConnectes::class)->findAll();

        return $this->render('objets_connectes/list.html.twig', [
            'objets' => $objetsConnectes,
        ]);
    }

    // Page technique d'un objet connecté
    #[Route('/objets/{id}', name: 'objets_connectes_show', requirements: ['id' => '\d+'])]
    public function showObject(ObjetsConnectes $objet): Response
    {
        // L'utilisateur doit avoir un rôle 'ROLE_ADMIN' ou 'ROLE_COMPLEX' pour consulter l'objet
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_COMPLEX']);

        return $this->render('objets_connectes/show.html.twig', [
            'objet' => $objet,
        ]);
    }

    // Ajouter un objet connecté (uniquement pour les utilisateurs avec le rôle 'ROLE_ADMIN' ou 'ROLE_COMPLEX')
    #[Route('/objets/ajouter', name: 'objets_connectes_add')]
    public function addObject(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_COMPLEX']); 

        $objet = new ObjetsConnectes();
        $form = $this->createForm(ObjetsConnectesType::class, $objet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objet);
            $entityManager->flush();

            $this->addFlash('success', 'L\'objet connecté a été ajouté avec succès!');
            return $this->redirectToRoute('objets_connectes');
        }

        return $this->render('objets_connectes/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
