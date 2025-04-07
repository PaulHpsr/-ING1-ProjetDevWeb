<?php

// src/Controller/ThemeController.php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ThemeController extends AbstractController
{
    #[Route('/update-theme', name: 'update_theme', methods: ['POST'])]
    public function updateTheme(Request $request, EntityManagerInterface $entityManager, UserInterface $user): JsonResponse
    {
        // Décoder le contenu JSON de la requête
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid JSON input'], 400);
        }

        // Vérifier que le paramètre 'color' est présent
        if (!isset($data['color'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Missing color parameter'], 400);
        }

        $color = $data['color'];

        // Exemple de validation : le code couleur doit respecter le format hexadécimal (ex : "#AABBCC")
        if (!is_string($color) || !preg_match('/^#[a-fA-F0-9]{6}$/', $color)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid color format. Expecting a hex code like "#AABBCC".'], 400);
        }

        // Mettre à jour le thème de l'utilisateur
        // On suppose que votre entité User (implémentant UserInterface) possède une méthode setColor()
        /** @var User $userEntity */
        $userEntity = $user;
        $userEntity->setColor($color);

        // Enregistrer les modifications en base de données
        $entityManager->flush();

        // Retourner une réponse JSON indiquant le succès de l'opération
        return new JsonResponse(['status' => 'success']);
    }
}
