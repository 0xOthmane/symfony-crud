<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 50; $i++) {
            # code...
            $category = new Category();
            $category->setName($this->faker->word());
            $manager->persist($category);
        }

        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            # code...
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())->setPrice(mt_rand(0, 100));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        for ($j = 0; $j < 25; $j++) {
            # code...
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())->setTime(mt_rand(1, 1440))->setNbPeople(mt_rand(0, 1) === 1 ? mt_rand(1, 50) : null)->setDifficulty(mt_rand(0, 1) === 1 ? mt_rand(1, 5) : null)->setDescription($this->faker->text(300))->setPrice(mt_rand(0, 1) === 1 ? mt_rand(1, 1000) : null)->setIsFavorite(mt_rand(0, 1) === 1 ? true : false);
            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                # code...
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }
            $manager->persist($recipe);
        }

        for ($i = 0; $i < 10; $i++) {
            # code...
            $user = new User();
            // $hashPassword = $this->hasher->hashPassword($user, 'password');
            $user->setName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setPlainPassword('password')
                ->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
