<?php

namespace App\Form;

use App\Entity\DemandeFormateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeFType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom',TextType::class)
        ->add('Prenom',TextType::class)
        ->add('Mail',EmailType::class)
        ->add('Telephone',IntegerType::class)
        ->add('Adresse',TextType::class)
        ->add('DateDeNaissance',DateType::class, array(
            'widget' => 'choice',
            'years' => range(date('Y'), date('Y')-80),
            'months' => range(date('m'), 12),
            'days' => range(date('d'),31),
          ))
        ->add('CV',FileType::class,[
            'mapped' => false,
            'label' => 'insérez votre CV '

        ])
    
        ->add('Valider',SubmitType::class);}
        

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeFormateur::class,
        ]);
    }}
  

    
               
           

