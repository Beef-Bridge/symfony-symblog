<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'post_index', methods: [Request::METHOD_GET])]
    public function index(
        Request $request,
        PostRepository $postRepository,
        PaginatorInterface $paginatorInterface
    ): Response {
        $postList = $postRepository->findPublished();
        $posts = $paginatorInterface->paginate(
            $postList,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts
        ]);
    }
}
