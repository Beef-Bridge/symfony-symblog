<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SearchType;
use App\Model\SearchData;
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
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData); 

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postRepository->findBySearch($searchData);

            return $this->render('post/index.html.twig', [
                'form' => $form->createView(),
                'posts' => $posts
            ]);
        }

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form->createView(),
            'posts' => $postRepository->findPublished($request->query->getInt('page', 1))
        ]);
    }

    #[Route('/article/{slug}', name: 'post_details', methods: [Request::METHOD_GET])]
    public function details(Post $post): Response
    {
        return $this->render('post/details.html.twig', ['post' => $post]);
    }
}
