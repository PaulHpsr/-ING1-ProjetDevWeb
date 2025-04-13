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
    #[Route('/', name: 'app_home')]
public function index(Request $request, InfosRepository $infosRepository): Response
{
    // Récupérer toutes les informations disponibles, triées par date de publication décroissante
    $infos = $infosRepository->findBy([], ['publishDate' => 'DESC']);

    // Retourner la vue avec toutes les informations
    return $this->render('home.html.twig', [
        'infos' => $infos,
    ]);
}

}
