<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Entity\Chambre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_home")
     */
    public function home(EntityManagerInterface $entityManager): Response
    {
        return $this->render('default/home.html.twig', [
            'sliders' => $entityManager->getRepository(Slider::class)->findAll()
        ]);
    }


     
}
