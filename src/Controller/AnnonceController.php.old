<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/list', name: 'liste_annonce')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        // Récupérer tous les annonces
        $liste_annonces = $annonceRepository->findAll();

        // Envoyer les annonces à la vue
        return $this->render('annonce/liste.html.twig', [
            'liste_annonces' => $liste_annonces
        ]);
    }
}
