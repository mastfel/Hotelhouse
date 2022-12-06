<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CommandeFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_arrivee')
            ->add('date_depart')
            ->add('prenom', TextType::class,[
                'label' => 'Prénom',
            ])
            ->add('nom',TextType::class,[
                'label' => 'Nom',
            ])
            ->add('telephone',TextType::class,[
                'label' => 'Téléphone',
            ])
            ->add('email',TextType::class,[
                'label' => 'E-mail',
            ])
            ->add('chambre',EntityType::class, [
                'class' => Chambre::class,
                'choice_label' => 'titre',
                'label' => 'Chambre & prix',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto col-3 my-3 btn btn-dark'
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
