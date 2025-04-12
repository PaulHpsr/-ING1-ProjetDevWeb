<?php
// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user_profile')]
    public function profile(User $user): Response
    {
        if ($this->getUser() && $this->getUser()->getId() !== $user->getId() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à voir ce profil.');
        }

        return $this->render('profil/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
