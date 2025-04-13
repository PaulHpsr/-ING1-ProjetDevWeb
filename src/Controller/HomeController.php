<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    
    #[Route('/landing', name: 'app_landing')]

    public function index(): Response
{
    $user = $this->getUser();

    return $this->render('home/index.html.twig', [
        'username' => $user ? $user->getUsername() : "-VISITOR-",
        'role'     => $user ? implode(', ', $user->getRoles()) : "ROLE_VISITEUR",
    ]);
}

}
