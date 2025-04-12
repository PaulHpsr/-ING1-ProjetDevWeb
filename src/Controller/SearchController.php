<?php

// src/Controller/SearchController.php
namespace App\Controller;

use App\Entity\User;
use App\Entity\ObjetsConnectes;
use App\Entity\Infos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la valeur de la recherche et le filtre
        $searchTerm = trim($request->query->get('recherche', ''));
        $filter = $request->query->get('filter', '');

        // Si aucun terme de recherche n'est fourni, afficher une vue sans résultats
        if (empty($searchTerm)) {
            return $this->render('recherche.html.twig', [
                'users'  => [],
                'objets' => [],
                'infos'  => [],
            ]);
        }

        // Initialiser les variables de résultats
        $users = [];
        $objets = [];
        $infos = [];

        // Rechercher selon le filtre sélectionné
        switch ($filter) {
            case 'user':
                $users = $entityManager->getRepository(User::class)->findByUsernameLike($searchTerm);
                break;
            case 'objet':
                $objets = $entityManager->getRepository(ObjetsConnectes::class)->findByNameOrTypeLike($searchTerm);
                break;
                case 'infos':
                    $infos = $entityManager->getRepository(Infos::class)->findByTitleOrContentLike($searchTerm);
                    break;
            default:
                // Recherche dans les trois entités par défaut
                $users = $entityManager->getRepository(User::class)->findByUsernameLike($searchTerm);
                $objets = $entityManager->getRepository(ObjetsConnectes::class)->findByNameOrTypeLike($searchTerm);
                $infos = $entityManager->getRepository(Infos::class)->findByTitleOrContentLike($searchTerm);
                break;
        }


        return $this->render('search_results.html.twig', [
            'users'  => $users,
            'objets' => $objets,
            'infos'  => $infos,
        ]);
    }
}
