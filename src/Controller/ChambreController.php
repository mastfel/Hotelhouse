<?php

namespace App\Controller;

use App\Entity\Chambre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChambreController extends AbstractController
{
    /**
     * @Route("/chambre", name="chambre_show", methods={"GET"})
     */

   public function show(EntityManagerInterface $entityManager): Response
   {


       return $this->render('chambre/show_chambre.html.twig', [
           'chambres' => $entityManager->getRepository(Chambre::class)->findAll()
       ]);
   }


}


