<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class CommandeFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_arrivee',DateTimeType::class, [
                'label' => "Date d'enregistrement",
                'widget' => 'single_text'
            ])
            ->add('date_depart',DateTimeType::class, [
                'label' => "Date d'enregistrement",
                'widget' => 'single_text'
            ])
            ->add('prix_total',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix €'
                ],
            ])
            ->add('prenom',TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('nom',TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('telephone',TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('email',EmailType::class, [
                'label' => 'E-mail'
                
            ])
            ->add('createdAt',DateTimeType::class, [
                'label' => "Date d'enregistrement",
                'widget' => 'single_text'
            ])
            ->add('chambre', EntityType::class, [
                'class' => Chambre::class,
                'choice_label' => 'name',
                'label' => 'Chambre',
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
