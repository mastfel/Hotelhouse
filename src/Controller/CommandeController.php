<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeFormeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
       /**
     * @Route("/reservation", name="create_reservation", methods={"GET|POST"})
     */
    public function  create(Request $request, EntityManagerInterface $entityManager): Response
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

            $this->addFlash('success', "votre réservation est prise en compte !");
            return $this->redirectToRoute('create_reservation');
        }

        # 3 - On retourne la vue du formulaire
        return $this->render("commande/reservation.html.twig", [
            'form' => $form->createView(),
            
            'commande' => $commande

        ]);
    }



    /**
      * @Route("/modifier-une-commande{id}", name="commande_update", methods={"GET|POST"})
     */
    public function update(Commande $commande, Request $request, EntityManagerInterface $entityManager): Response
     {
      $form = $this->createForm(CommandeFormeType::class, $commande)
            ->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) {
       $entityManager->persist($commande);
         $entityManager->flush();

           return $this->redirectToRoute('show_dashboard');
         } // end if()

       return $this->render("commande/show_commande.html.twig", [
            'user' => $commande,
         'form' => $form->createView()
        ]);
    } # end function update()



    /**
     * @Route("/supprimer-une_commande_{id}", name="commande_delete", methods={"GET"})
     */
    public function hardDeleteArticle(Commande $commande, EntityManagerInterface $entityManager): RedirectResponse
    {
      

        $entityManager->remove($commande);
        $entityManager->flush();

        $this->addFlash('success', "La chambre a bien été supprimé de la base de données");
        return $this->redirectToRoute('show_dashboard');
    }



 
}
