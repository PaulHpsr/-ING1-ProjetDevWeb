<?php

// src/Controller/HomeController.php
namespace App\Controller;

use App\Entity\Infos;
use App\Repository\InfosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, InfosRepository $infosRepository): Response
    {
        // Récupérer les 5 dernières informations du bâtiment
        $infos = $infosRepository->findBy([], ['publishDate' => 'DESC'], 5);

        // Retourner la vue avec les infos et la recherche
        return $this->render('home.html.twig', [
            'infos' => $infos,
        ]);
    }
}
