<?php

namespace App\DataFixtures;

use App\Entity\Post\Category;
use App\Entity\Post\Tag;
use App\Repository\PostRepository;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryTagFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private PostRepository $postRepository
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $posts = $this->postRepository->findAll();

        // Category
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

        foreach ($posts as $post) {
            for ($i=0; $i < mt_rand(1, 5); $i++) {
                $post->addCategory(
                    $categories[mt_rand(0, count($categories) - 1)]
                );
            }
        }

        // Tag
        $tags = [];
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();
            $tag->setName($this->faker->words(1, true) . ' ' . $i);
            $tag->setDescription(
                $this->faker->realText(254)
            );

            $manager->persist($tag);
            $tags[] = $tag;
        }

        foreach ($posts as $post) {
            for ($i=0; $i < mt_rand(1, 5); $i++) {
                $post->addTag(
                    $tags[mt_rand(0, count($tags) - 1)]
                );
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ThumbnailPostFixtures::class];
    }
}
