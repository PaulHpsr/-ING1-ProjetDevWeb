<?php

// src/Controller/ThemeController.php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThemeController extends AbstractController
{
    #[Route('/update-theme', name: 'update_theme', methods: ['POST'])]
    public function updateTheme(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer le contenu JSON
        $data = json_decode($request->getContent(), true);
        $newColor = isset($data['color']) ? (int) $data['color'] : 0;

        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non connecté.'], 403);
        }

        // Mettre à jour la propriété "color" de l'utilisateur
        $user->setColor($newColor);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'newColor' => $newColor]);
    }
}
