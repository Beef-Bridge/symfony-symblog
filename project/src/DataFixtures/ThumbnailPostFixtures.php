<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Thumbnail;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ThumbnailPostFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $thumbnail = new Thumbnail();
            $thumbnail->setImageName($this->faker->words(1, true));
            $thumbnail->setImageSize($this->faker->randomDigitNotNull());

            $this->setPost($thumbnail, $manager);
        }

        $manager->flush();
    }

    private function setPost(Thumbnail $thumbnail, ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle($this->faker->words(4, true))
            ->setContent($this->faker->realText(1800))
            ->setState(mt_rand(0, 2) === 1 ? Post::STATE_LIST[0] : Post::STATE_LIST[1]);
        $manager->persist($post);

        $post->setThumbnail($thumbnail);
        $manager->persist($post);
    }
}
