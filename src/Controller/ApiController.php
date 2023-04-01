<?php

namespace App\Controller;

use App\Services\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api_paiement')]
    public function autorisation(ApiHelper $apiHelper): Response
    {

        dd($apiHelper->autorisationPaiement());

    }

}
