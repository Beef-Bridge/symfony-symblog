<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Model\SearchData;
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
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData); 

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postRepository->findBySearch($searchData);

            return $this->render('post/index.html.twig', [
                'form' => $form->createView(),
                'category' => $category,
                'posts' => $posts
            ]);
        }

        $posts = $postRepository->findPublished($request->query->getInt('page', 1), $category);

        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
            'posts' => $posts
        ]);
    }

}
