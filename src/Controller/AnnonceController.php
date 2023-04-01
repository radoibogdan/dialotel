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
    #[Route('/annonces/{id}/candidature', name: 'annonce_detail', methods: ['GET'])]
    public function voirUneAnnonce(Annonce $annonce): Response
    {
        $user = $this->getUser();
        if (empty($user)) {
            $this->addFlash('danger', 'Il faut être connecté pour postuler à une annonce');
            return $this->redirectToRoute('annonces_list');
        }

        return $this->render('annonce/detail_annonce.html.twig', [
            'annonce' => $annonce,
            'userConnecteId' => $user->getId()
        ]);
    }

    /**
     * Liste Annonces
     *
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    #[Route('/annonces/list', name: 'annonces_list', methods: ['GET'])]
    public function listeAnnonces(AnnonceRepository $annonceRepository): Response
    {
        // Récupérer tous les annonces
        $liste_annonces = $annonceRepository->findAll();

        // Envoyer les annonces à la vue
        return $this->render('annonce/liste.html.twig', [
            'liste_annonces' => $liste_annonces
        ]);
    }
    /**
     * Liste des annonces créées par l'utilisateur
     *
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    #[Route('/annonces/crees', name: 'liste_user_annonces')]
    public function listeUserAnnonces(AnnonceRepository $annonceRepository): Response
    {
        $user = $this->getUser();
        if (empty($user)) {
            $this->addFlash('danger', 'Il faut être connecté pour postuler à une annonce');
            return $this->redirectToRoute('annonces');
        }

        // Récupérer tous les annonces
        $liste_annonces = $user->getAnnonces();

        // Envoyer les annonces à la vue
        return $this->render('annonce_crud/index.html.twig', [
            'annonces' => $liste_annonces
        ]);
    }

    /**
     * Liste Annonces où le user à postulé lui même
     *
     * @return Response
     */
    #[Route('/liste_candidatures_annonces', name: 'liste_user_candidatures_deposees')]
    public function liste_candidatures_user(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (empty($user)) {
            $this->addFlash('info', 'Il faut être connecté pour voir la liste');
            return $this->redirectToRoute('annonces');
        }
        $liste_annonces_candidatures = $user->getCandidaturesAnnonces();
        // Envoyer les annonces à la vue
        return $this->render('annonce/user/candidatures_deposees.html.twig', [
            'liste_annonces' => $liste_annonces_candidatures
        ]);
    }

    /**
     * Postuler à une annonce
     *
     * @param $idAnnonce
     * @param $idUser
     * @param AnnonceRepository $annonceRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/user/{idUser}/candidature/{idAnnonce}', name: 'user_candidature', methods: ['POST'])]
    public function candidature($idAnnonce, $idUser, AnnonceRepository $annonceRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $annonce = $annonceRepository->findOneBy(['id' => $idAnnonce]);
        $user = $userRepository->findOneBy(['id' => $idUser]);
        dump($annonce);
        dump($user);
        $annonce->addCandidature($user);
        $entityManager->persist($annonce);
        $entityManager->flush();

        return $this->json(['reponse' => 'ok']);
    }
}
