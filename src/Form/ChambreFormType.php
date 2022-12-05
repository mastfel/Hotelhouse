<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChambreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
                'label' => 'Titre & prix'
            ])
            ->add('description_courte',TextareaType::class,[
                'label' => 'Courte description'
            ])
            ->add('description_longue', TextareaType::class,[
                'label' => 'longue description'
            ] )
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'data_class' => null,
                'attr' => [
                    'data-default-file' => $options['photo']
                ],
                'required' => false,
                'mapped' => false,
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => "Date d'enregistrement",
                'widget' => 'single_text'
            ] )
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto col-3 my-3 btn btn-success'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
            'allow_file_upload' => true,
            'photo' => null
        ]);
    }
}
