<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeFormeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
       /**
     * @Route("/reservation", name="user_reservation", methods={"GET|POST"})
     */
    public function  register(Request $request, EntityManagerInterface $entityManager): Response
    {
        # 1 - Instanciation de class
        $commande = new Commande();

        # 2 - Création du formulaire
        $form = $this->createForm(CommandeFormeType::class, $commande)
            ->handleRequest($request);

        # 4 - Si le form est soumis ET valide
        if($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', "Vous vous êtes inscrit avec succès !");
            return $this->redirectToRoute('default_home');
        }

        # 3 - On retourne la vue du formulaire
        return $this->render("commande/reservation.html.twig", [
            'form' => $form->createView()
        ]);
    }







// 
}
