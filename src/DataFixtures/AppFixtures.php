<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct() {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        
        for ($i=0; $i <50 ; $i++) { 
            # code...
            $category = new Category();
            $category->setName($this->faker->word());
            $manager->persist($category);
        }

        for ($i=0; $i <50 ; $i++) { 
            # code...
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())->setPrice(mt_rand(0, 100));
            $manager->persist($ingredient);
        }
        $manager->flush();
    }
}