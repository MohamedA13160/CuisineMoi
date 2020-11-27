<?php

namespace App\Form;


use App\Entity\Option;
use App\Entity\Recette;
use App\Entity\Category;
use App\Entity\Ingredient;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title' , null , [
                
                'label' => false,
                "attr" => [
                    "placeholder" => "Titre",
                    
                ]
                
            ])
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'titre',
                    'label' => false,
                    "placeholder" => "Catégorie"
                ]
            )
            ->add('description', null, [
                'label' => false,
                "attr" => [
                    "placeholder" => "Description"
                ]
                ])
           
            ->add('ingredient', null, [
            'label' => false,
                "attr" => [
                    "placeholder" => "Veuillez saisir un ingrédient par ligne"
                ]
            ])
            ->add('preparation', null, [
            'label' => false,
                "attr" => [
                    "placeholder" => "Etape 1"
                ]
            ])

            ->add('tps_preparation', null, [
                'label' => false,
                "attr" => [
                    "placeholder" => "Temps de préparation"
                ]
            ])
            ->add('tps_cuisson', null, [
                'label' => false,
                "attr" => [
                    "placeholder" => "Temps de cuissons "
                ]
            ])
            ->add('personne', null, [
            'label' => false,
                "attr" => [
                    "placeholder" => "4 Personnes"
                ]
            ])
            
            ->add('imageFile', FileType::class,
            [
                "required" => false ,
                "attr" => [
                   "class" => "inputfile"
                ],
      
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => "Options",
                
                

            ]);
           
            
        
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
            'translation_domain' => 'forms',
            
        ]);

    }
}
