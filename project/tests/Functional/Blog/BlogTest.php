<?php

namespace App\Tests\Functional\Blog;

use App\Entity\Post;
use App\Entity\Post\Tag;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogTest extends WebTestCase
{
    public function testBlogPageWorks(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1', 'Symblog : Le blog créé de A à Z avec Symfony');
    }

    public function testPaginationWorks(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $posts = $crawler->filter('div.card');
        $this->assertEquals(9, count($posts));

        $link = $crawler->selectLink('2')->extract(['href'])[0];
        $crawler = $client->request(Request::METHOD_GET, $link);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $posts = $crawler->filter('div.card');
        $this->assertGreaterThanOrEqual(1, count($posts));
    }

    public function testDropdownWorks(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $link = $crawler->filter('.dropdown-menu > li > a')->link()->getUri();
        $client->request(Request::METHOD_GET, $link);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('category_index');
    }

    public function testSearchBarWorks(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGeneratorInterface */
        $urlGeneratorInterface = $client->getContainer()->get('router');
        /** @var EntityManager $em */
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /** @var PostRepository $postRepository */
        $postRepository = $em->getRepository(Post::class);
        /** @var Post $post */
        $post = $postRepository->findOneBy([]);
        /** @var Tag $tag */
        $tag = $post->getTags()[0];

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGeneratorInterface->generate('post_index')
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $searchList = [
            substr($post->getTitle(), 0, 3),
            substr($tag->getName(), 0, 3)
        ];

        foreach ($searchList as $search) {
            $form = $crawler->filter('form[name=search]')->form([
                'search[q]' => $search
            ]);

            $crawler = $client->submit($form);

            $this->assertResponseIsSuccessful();
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
            $this->assertRouteSame('post_index');

            $nbPosts = count($crawler->filter('div.card'));
            $postsTitle = $crawler->filter('div.card h5');
            $count = 0;

            foreach ($postsTitle as $title) {
                if (
                    str_contains($title->textContent, $search) ||
                    str_contains($tag->getName(), $search)
                ) {
                    $count++;
                }
            }

            $this->assertEquals($nbPosts, $count);
        }
    }
}
