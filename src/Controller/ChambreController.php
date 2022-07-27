<?php

namespace App\Controller;

use App\Entity\Chambre;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChambreController extends AbstractController
{
    /**
     * @Route("/chambre", name="show_chambre")
     */
    public function showChambre(Chambre $chambre): Response
    {
        return $this->render('chambre/show_chambre.html.twig', [
            'chambre' => '$chambre',
        ]);
    }
}


