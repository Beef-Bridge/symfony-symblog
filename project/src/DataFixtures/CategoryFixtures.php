<?php

namespace App\DataFixtures;

use App\Entity\Post\Category;
use App\Repository\PostRepository;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private PostRepository $postRepository
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($this->faker->words(1, true) . ' ' . $i);
            $category->setDescription(
                $this->faker->realText(254)
            );

            $manager->persist($category);
            $categories[] = $category;
        }

        $posts = $this->postRepository->findAll();
        foreach ($posts as $post) {
            $post->addCategory(
                $categories[mt_rand(0, count($categories) - 1)]
            );
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ThumbnailPostFixtures::class];
    }
}
