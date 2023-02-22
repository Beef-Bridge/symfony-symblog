<?php

namespace App\Tests\Functional\Post;

use App\Entity\Post;
use Doctrine\ORM\EntityManager;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostTest extends WebTestCase
{
    public function testPostPageWorks(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGeneratorInterface */
        $urlGeneratorInterface = $client->getContainer()->get('router');
        /** @var EntityManager $em */
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /** @var PostRepository $postRepository */
        $postRepository = $em->getRepository(Post::class);

        /** @var Post */
        $post = $postRepository->findOneBy([]);

        $client->request(
            Request::METHOD_GET,
            $urlGeneratorInterface->generate('post_details', ['slug' => $post->getSlug()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1', ucfirst($post->getTitle()));
    }

    public function testReturnToBlogWorks(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGeneratorInterface */
        $urlGeneratorInterface = $client->getContainer()->get('router');
        /** @var EntityManager $em */
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /** @var PostRepository $postRepository */
        $postRepository = $em->getRepository(Post::class);

        /** @var Post */
        $post = $postRepository->findOneBy([]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGeneratorInterface->generate('post_details', ['slug' => $post->getSlug()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $link = $crawler->selectLink('Retour')->link()->getUri();

        $crawler = $client->request(Request::METHOD_GET, $link);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('post_index');
    }
}
