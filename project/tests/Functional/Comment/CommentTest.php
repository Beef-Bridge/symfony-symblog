<?php

namespace App\Tests\Functional\Comment;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentTest extends WebTestCase
{
    public function testPostCommentWorks(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        /** @var EntityManagerInterface $em */
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var PostRepository $postRepository */
        $postRepository = $em->getRepository(Post::class);

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository(User::class);

        /** @var Post $post */
        $post = $postRepository->findOneBy([]);

        /** @var User $user */
        $user = $userRepository->findOneBy([]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('post_details', ['slug' => $post->getSlug()])
        );
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter('form[name=comment]')->form([
            'comment[content]' => 'Mon test pour le fonctionnement des commentaires'
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertRouteSame('post_details', ['slug' => $post->getSlug()]);
        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            'Votre commentaire a bien été enregistré. Il sera soumis à modération dans les plus brefs délais.'
        );
    }
}
