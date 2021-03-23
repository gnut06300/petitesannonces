<?php

namespace App\Controller;

use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AnnoncesRepository $repo): Response
    {
        $annonces= $repo->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'annonces' => $annonces,
        ]);
    }
    /**
     * @Route("/mentions/legales", name="mentions")
     */
    public function mentions(): Response
    {
        return $this->render('main/mentions.html.twig');
    }
}
