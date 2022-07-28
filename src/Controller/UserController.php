<?php

namespace App\Controller;
use DateTime;
use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
     /**
     * @Route("/inscritpion", name="user_register", methods={"GET|POST"})
     */
    public function  register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        # 1 - Instanciation de class
        $user = new User();

        # 2 - Création du formulaire
        $form = $this->createForm(RegisterFormType::class, $user)
            ->handleRequest($request);

        # 4 - Si le form est soumis ET valide
        if($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new DateTime());
           
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Vous vous êtes inscrit avec succès !");
            return $this->redirectToRoute('app_login');
        }

        # 3 - On retourne la vue du formulaire
        return $this->render("user/register.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/mon-espace-perso", name="show_profile", methods={"GET"})
     */
    public function showProfile(EntityManagerInterface $entityManager): Response
    {

        return $this->render("user/show_profile.html.twig", [
            
        ]);
    } 
    
    // /**
    //  * @Route ("/ajouter-un-user.html", name="user_create", methods={"GET|POST"})
    //  */
    // public function create (Request $request, EntityManagerInterface $entityManager ):Response
    // {
    //     $user = new User();

    //     $form = $this->createForm(RegisterFormType::class, $user);
    //     $form->handleRequest($request);
    //     if($form->isSubmitted() && $form->isValid()) {

    //         //$form->get('salary')->getData();
    //         $entityManager->persist($user);
    //         $entityManager->flush();
            
    //         return $this->redirectToRoute('default_home');

    //     }

    //     return $this->render("", [
    //         "form_user" => $form->createView()
    //     ]);
    // }



     /**
      * @Route("/modifier-un-user{id}", name="user_update", methods={"GET|POST"})
     */
    public function update(User $user, Request $request, EntityManagerInterface $entityManager): Response
     {
      $form = $this->createForm(RegisterFormType::class, $user)
            ->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) {
       $entityManager->persist($user);
         $entityManager->flush();

           return $this->redirectToRoute('show_profile');
         } // end if()

       return $this->render("user/change_compte.html.twig", [
            'user' => $user,
         'form' => $form->createView()
        ]);
    } # end function update()

 
    /**
     * @Route("/supprimer-un-user-{id}", name="user_delete", methods={"GET"})

     */
    public function delete(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
    $entityManager->remove($user);
    $entityManager->flush();
    
    return $this->redirectToRoute("default_home");
    
    }


}


