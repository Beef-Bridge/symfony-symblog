<?php

namespace App\Controller;

use App\Entity\Post\Category;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'category_index', methods: [Request::METHOD_GET])]
    public function index(
        Request $request,
        Category $category,
        PostRepository $postRepository
    ): Response {
        $posts = $postRepository->findPublished($request->query->getInt('page', 1), $category);

        return $this->render('category/index.html.twig', [
            'category' => $category,
            'posts' => $posts
        ]);
    }

}
