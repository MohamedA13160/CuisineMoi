<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;

use App\Entity\Recette;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RecetteFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $category = new Category();
        $category->setTitre('Entrées');
        $category->setDescription('Entrées');
        $manager->persist($category);
    
        $category1 = new Category();
        $category1->setTitre('Plats');
        $category1->setDescription('Plats');
        $manager->persist($category1);
    
        $category2 = new Category();
        $category2->setTitre('Desserts');
        $category2->setDescription('Desserts');
        $manager->persist($category2);
    
        $category3 = new Category();
        $category3->setTitre('Boissons');
        $category3->setDescription('Boissons');
        $manager->persist($category3);
    

        
     
        for ($i=0; $i < 100 ; $i++) {

           $recette = new Recette();
           $recette->setTitle($faker->words(3, true))
                ->setCategory($faker->randomElement([$category, $category1, $category2, $category3]))
                ->setDescription($faker->words(6, true))
                ->setPreparation($faker->sentences(10, true))
                ->setTpsCuisson(10)
                ->setTpsPreparation(15)
                ->setPersonne($faker->numberBetween(1, 10))
                ->setIngredient($faker->sentences(10, true))
                ->setCreatedAt(new DateTime());
            $manager->persist($recette);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
