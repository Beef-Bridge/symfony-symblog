<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/like/article/{id}', name: 'like_post', methods: [Request::METHOD_GET])]
    #[IsGranted('ROLE_USER')]
    public function like(Post $post, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if ($post->isLikedByUser($user)) {
            $post->removeLike($user);
            $manager->flush();

            return $this->json([
                'message' => 'Le like a été supprimé.',
                'nbLike' => $post->howManyLikes()
            ]);
        }

        $post->addLike($user);
        $manager->flush();

        return $this->json([
            'message' => 'Le like a été ajouté.',
            'nbLike' => $post->howManyLikes()
        ]);
    }
}
