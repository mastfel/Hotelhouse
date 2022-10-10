<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="show_restaurant")
     */
    public function show(): Response
    {
        return $this->render('default/restaurant.html.twig', [
            'controller_name' => 'RestaurantController',
        ]);
    }
}
