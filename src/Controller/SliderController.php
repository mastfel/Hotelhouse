<?php

namespace App\Controller;

use App\Entity\Slider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SliderController extends AbstractController
{
      /**
     * @Route("/slider", name="show_slider", methods={"GET"})
     */

   public function show(EntityManagerInterface $entityManager): Response
   {


       return $this->render('chambre/show_slider.html.twig', [
           'sliders' => $entityManager->getRepository(Slider::class)->findAll()
       ]);
   }
}
