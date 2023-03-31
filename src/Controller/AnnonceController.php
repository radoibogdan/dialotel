<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;
use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * Afficher le détail d'une seule annonce
     *
     * @param Annonce $annonce
     * @return Response
     */
    #[Route('/annonce/candidature/{id}', name: 'annonce_candidature', methods: ['GET'])]
    public function voirUneAnnonce(Annonce $annonce): Response
    {
        $user = $this->getUser();
        if (empty($user)) {
            $this->addFlash('danger', 'Il faut être connecté pour postuler à une annonce');
            return $this->redirectToRoute('annonces');
        }

        return $this->render('annonce/annonce.html.twig', [
            'annonce' => $annonce,
            'userConnecteId' => $user->getId()
        ]);
    }

    #[Route('/annonces', name: 'annonces')]
    public function annonces(AnnonceRepository $annonceRepository): Response
    {
        // Récupérer tous les annonces
        $liste_annonces = $annonceRepository->findAll();

        // Envoyer les annonces à la vue
        return $this->render('annonce/annonces.html.twig', [
            'liste_annonces' => $liste_annonces
        ]);
    }

    #[Route('/user/liste_annonce_candidatures', name: 'liste_annonce_candidatures')]
    public function liste_candidatures_user(): Response
    {
        $user = $this->getUser();
        if (empty($user)) {
            $this->addFlash('danger', 'Il faut être connecté pour voir la liste');
            return $this->redirectToRoute('annonces');
        }
        $liste_annonces_candidatures = [];
        // Envoyer les annonces à la vue
        return $this->render('annonce/annonces.html.twig', [
            'liste_annonces_candidatures' => $liste_annonces_candidatures
        ]);
    }

    /**
     * Afficher le détail d'une seule annonce
     *
     * @param Annonce $annonce
     * @return Response
     */
    #[Route('/user/{idAnnonce}/candidature/{idUser}', name: 'user_candidature', methods: ['POST'])]
    public function candidature($idAnnonce, $idUser, AnnonceRepository $annonceRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $annonce = $annonceRepository->findOneBy(['id' => $idAnnonce]);
        $user = $userRepository->findOneBy(['id' => $idUser]);
        $annonce->addCandidature($user);
        $entityManager->persist($annonce);
        $entityManager->flush();

        return $this->json(['reponse' => 'ok']);
    }

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
