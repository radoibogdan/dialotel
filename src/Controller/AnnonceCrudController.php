<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonces')]
class AnnonceCrudController extends AbstractController
{
    /**
     * Récupérer la liste de tous les annonces
     *
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    #[Route('/', name: 'app_annonce_crud_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        // Récupérer tous les annonces
        $liste_annonces = $annonceRepository->findAll();

        // Envoyer les annonces à la vue
        return $this->render('annonce/liste.html.twig', [
            'liste_annonces' => $liste_annonces
        ]);
    }


    /**
     * Créer une annonce
     *
     * @param Request $request
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    #[Route('/creer', name: 'app_annonce_crud_creer', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $user = $this->getUser();
        if (empty($user)) {
            $this->addFlash('danger', 'Il faut être connecté pour créer une annonce');
            return $this->redirectToRoute('app_annonce_crud_creer');
        }

        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setUser($user);
            $annonce->setDateAnnonce(new \DateTime());
            $annonceRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_crud/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * Afficher le detail d'une seule annonce
     * @param Annonce $annonce
     * @return Response
     */
    #[Route('/{id}', name: 'app_annonce_crud_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce_crud/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }


    /**
     * Modifier une annonce
     *
     * @param Request $request
     * @param Annonce $annonce
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_annonce_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_crud/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * Supprimer une annonce
     *
     * @param Request $request
     * @param Annonce $annonce
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    #[Route('/{id}', name: 'app_annonce_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $annonceRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
