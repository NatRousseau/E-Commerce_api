<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $product = new Product();
            $product
                ->setTitle($faker->words(3, true))
                ->setMark($faker->words(1, true))
                ->setModel($faker->words(1, true))
                ->setDescription($faker->sentences(3, true))
                ->setPrice($faker->numberBetween(10, 5000));
            $manager->persist($product);
        }
        $manager->flush();
    }
}
