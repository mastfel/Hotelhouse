<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Slider;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\SliderFormType;
use App\Form\ChambreFormType;
use App\Form\CommandeFormeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin")
 */


class AdminController extends AbstractController
{
    /**
     * @Route("/tableau-de-bord", name="show_dashboard", methods={"GET"})
     */
    public function showDashboard(EntityManagerInterface $entityManager): Response
    {
        
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } catch (AccessDeniedException $exception) {
            $this->addFlash('warning', 'Cette partie du site est réservée aux admins');
            return $this->redirectToRoute('default_home');
        }

        $chambres = $entityManager->getRepository(Chambre::class)->findAll();
        $sliders = $entityManager->getRepository(Slider::class)->findAll();
         $commandes = $entityManager->getRepository(Commande::class)->findAll();
         $users = $entityManager->getRepository(User::class)->findAll();
       
        return $this->render("admin/show_dashboard.html.twig", [
            'chambres' => $chambres,
            'sliders' => $sliders,
             'commandes' => $commandes,
             'users' => $users,
        ]);
    }



    /**
     * @Route("/ajouter-un-article", name="create_chambre", methods={"GET|POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        # 1 - Instanciation
        $chambre = new Chambre();

        # 2 - Création du formulaire
        $form = $this->createForm(ChambreFormType::class, $chambre)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $chambre->setCreatedAt(new DateTime());

          

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

           
            if ($photo) {
                # Déconstructioon
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);


                # Reconstruction
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $chambre->setPhoto($newFilename);
                } catch (FileException $exception) {
                    # Code à exécuter en cas d'erreur.
                }
            } # end if($photo)

            $entityManager->persist($chambre);
            $entityManager->flush();

            $this->addFlash('success', "La chambre est en ligne avec succès !");
            return $this->redirectToRoute('show_dashboard');
        } # end if ($form)

        # 3 - Création de la vue
        return $this->render("admin/form/chambre.html.twig", [
            'form' => $form->createView()
        ]);
    } # end function createArticle

    /**
     * @Route("/modifier-une-chambre_{id}", name="update_chambre", methods={"GET|POST"})
     */
    public function updateArticle(chambre $chambre, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $originalPhoto = $chambre->getPhoto();

        # 2 - Création du formulaire
        $form = $this->createForm(ChambreFormType::class, $chambre, [
            'photo' => $originalPhoto
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

           
            if ($photo) {

                # Déconstructioon
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);


                # Reconstruction
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $chambre->setPhoto($newFilename);
                } catch (FileException $exception) {
                    # Code à exécuter en cas d'erreur.
                }
            } else {
                $chambre->setPhoto($originalPhoto);
            } # end if($photo)



            $entityManager->persist($chambre);
            $entityManager->flush();

            $this->addFlash('success', "La chambre a été modifié avec succès !");
            return $this->redirectToRoute('show_dashboard');
        } # end if ($form)

        # 3 - Création de la vue
        return $this->render("admin/form/chambre.html.twig", [
            'form' => $form->createView(),
            'chambre' => $chambre
        ]);
    } # end function updateArticle

    /**
     * @Route("/supprimer-une_une chambre_{id}", name="chambre_delete", methods={"GET"})
     */
    public function hardDeleteArticle(Chambre $chambre, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Suppression manuelle de la photo
        $photo = $chambre->getPhoto();

        // On utilise la fonction native de PHP unlink() pour supprimer un fichier dans le filesystem
        if ($photo) {
            unlink($this->getParameter('uploads_dir') . '/' . $photo);
        }

        $entityManager->remove($chambre);
        $entityManager->flush();

        $this->addFlash('success', "La chambre a bien été supprimé de la base de données");
        return $this->redirectToRoute('show_dashboard');
    }


    // SLIDER

    /**
     * @Route("/ajouter-un-slider", name="create_slider", methods={"GET|POST"})
     */
    public function createSlider(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        # 1 - Instanciation
        $slider = new Slider();

        # 2 - Création du formulaire
        $form = $this->createForm(SliderFormType::class, $slider)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slider->setCreatedAt(new DateTime());



            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

           
            if ($photo) {
                # Déconstructioon
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);


                # Reconstruction
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $slider->setPhoto($newFilename);
                } catch (FileException $exception) {
                    # Code à exécuter en cas d'erreur.
                }
            } # end if($photo)



            $entityManager->persist($slider);
            $entityManager->flush();

            $this->addFlash('success', "Le slider est en ligne avec succès !");
            return $this->redirectToRoute('show_dashboard');
        } # end if ($form)

        # 3 - Création de la vue
        return $this->render("admin/form/slider.html.twig", [
            'form' => $form->createView()
        ]);
    } # end function createArticle

    /**
     * @Route("/modifier-un-slider_{id}", name="update_slider", methods={"GET|POST"})
     */
    public function updateSlider(slider $slider, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $originalPhoto = $slider->getPhoto();

        # 2 - Création du formulaire
        $form = $this->createForm(SliderFormType::class, $slider, [
            'photo' => $originalPhoto
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            # Si une photo a été uploadée dans le formulaire on va faire le traitement nécessaire à son stockage dans notre projet.
            if ($photo) {

                # Déconstructioon
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);


                # Reconstruction
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $slider->setPhoto($newFilename);
                } catch (FileException $exception) {
                    # Code à exécuter en cas d'erreur.
                }
            } else {
                $slider->setPhoto($originalPhoto);
            } # end if($photo)



            $entityManager->persist($slider);
            $entityManager->flush();

            $this->addFlash('success', "Le slider a été modifié avec succès !");
            return $this->redirectToRoute('show_dashboard');
        } # end if ($form)

        # 3 - Création de la vue
        return $this->render("admin/form/slider.html.twig", [
            'form' => $form->createView(),
            'slider' => $slider
        ]);
    } # end function updateArticle


/**
     * @Route("/supprimer-unslider_{id}", name="slider_delete", methods={"GET"})
     */
    public function hardDeleteCahmbre(Slider $slider, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Suppression manuelle de la photo
        $photo = $slider->getPhoto();

        // On utilise la fonction native de PHP unlink() pour supprimer un fichier dans le filesystem
        if ($photo) {
            unlink($this->getParameter('uploads_dir') . '/' . $photo);
        }

        $entityManager->remove($slider);
        $entityManager->flush();

        $this->addFlash('success', "Le slider a bien été supprimé de la base de données");
        return $this->redirectToRoute('show_dashboard');
    }


//     // réservation

/**
     * @Route("/voir-membre", name="show_user", methods={"GET"})
     */
    public function showUser(EntityManagerInterface $entityManager): Response
    {
        try {
            $this->denyAccessUnLessGranted('ROLE_ADMIN');
        } catch (AccessDeniedException $exception) {
            $this->addFlash('warning', 'Cette partie du site est réservée aux admins');
            return $this->redirectToRoute('show_dashboard');
        }

        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render("admin/show_user.html.twig", [
            'users' => $users,
        ]);
    }






}
