<?php

namespace App\Controller;

use App\Entity\CartePaiement;
use App\Form\CartePaiementType;
use App\Repository\CartePaiementRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carte/paiement')]
class CartePaiementController extends AbstractController
{
    #[Route('/liste/admin', name: 'admin_liste_cartes', methods: ['GET'])]
    public function admin_liste(CartePaiementRepository $cartePaiementRepository): Response
    {
        $user = $this->getUser();
        #ToDo Vérifier si admin pour afficher toutes les cartes dans la base

        if (empty($user)) {
            $this->addFlash('danger', 'Il faut être connecté pour pouvoir voir les cartes associées au compte');
            return $this->redirectToRoute('app_annonce_crud_index');
        }

        return $this->render('carte_paiement/index.html.twig', [
            'carte_paiements' => $cartePaiementRepository->findAll()
        ]);
    }

    #[Route('/', name: 'app_carte_paiement_index', methods: ['GET'])]
    public function index(CartePaiementRepository $cartePaiementRepository): Response
    {
        $user = $this->getUser();
        if (empty($user)) {
            $this->addFlash('danger', 'Il faut être connecté pour pouvoir voir les cartes associées au compte');
            return $this->redirectToRoute('app_annonce_crud_index');
        }

        return $this->render('carte_paiement/index.html.twig', [
//            'carte_paiements' => $cartePaiementRepository->findAll(),
            'carte_paiements' => $user->getCartePaiements()
        ]);
    }

    #[Route('/new', name: 'app_carte_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CartePaiementRepository $cartePaiementRepository): Response
    {
        $user = $this->getUser();
        if (empty($user)) {
            $this->addFlash('danger', 'Il faut être connecté pour pouvoir rajouter une carte de paiement');
            return $this->redirectToRoute('app_annonce_crud_index');
        }
        $cartePaiement = new CartePaiement();
        $form = $this->createForm(CartePaiementType::class, $cartePaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cartePaiement->setUser($user);
            $cartePaiement->setDateCreation(new \DateTime());
            $cartePaiementRepository->save($cartePaiement, true);

            return $this->redirectToRoute('app_carte_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carte_paiement/new.html.twig', [
            'carte_paiement' => $cartePaiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carte_paiement_show', methods: ['GET'])]
    public function show(CartePaiement $cartePaiement): Response
    {
        return $this->render('carte_paiement/show.html.twig', [
            'carte_paiement' => $cartePaiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carte_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CartePaiement $cartePaiement, CartePaiementRepository $cartePaiementRepository): Response
    {
        $form = $this->createForm(CartePaiementType::class, $cartePaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cartePaiementRepository->save($cartePaiement, true);

            return $this->redirectToRoute('app_carte_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carte_paiement/edit.html.twig', [
            'carte_paiement' => $cartePaiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carte_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, CartePaiement $cartePaiement, CartePaiementRepository $cartePaiementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cartePaiement->getId(), $request->request->get('_token'))) {
            $cartePaiementRepository->remove($cartePaiement, true);
        }

        return $this->redirectToRoute('app_carte_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
