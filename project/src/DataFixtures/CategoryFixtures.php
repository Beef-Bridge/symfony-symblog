<?php

use App\Entity\Post\Category;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($this->faker->words(1, true) . ' ' . $i)
                    ->setDescription(
                        mt_rand(0, 1) === 1 ? $this->faker->realText(254) : null
                    );
            $manager->persist($category);
        }

        $manager->flush();
    }
}
