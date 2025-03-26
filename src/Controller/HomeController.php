<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    
    #[Route('/', name: 'app_home')] 

    public function index(): Response
    {
    
        return $this->render('home/index.html.twig', [
            'username' => $this->getUser() ? $this->getUser()->getUsername() : "-VISITOR-",  //Visiteur si pas connecté.
            'role' => $this->getUser() ? implode(', ', $this->getUser()->getRoles()) : "ROLE_VISITEUR", //Role visiteur
        ]);
    }
}
