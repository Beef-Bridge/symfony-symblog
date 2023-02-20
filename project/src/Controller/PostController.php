<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'post_index', methods: [Request::METHOD_GET])]
    public function index(
        Request $request,
        PostRepository $postRepository
    ): Response {
        $postList = $postRepository->findPublished($request->query->getInt('page', 1));

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $postList
        ]);
    }

    #[Route('/article/{slug}', name: 'post_details', methods: [Request::METHOD_GET])]
    public function details(Post $post): Response
    {
        return $this->render('post/details.html.twig', ['post' => $post]);
    }
}
