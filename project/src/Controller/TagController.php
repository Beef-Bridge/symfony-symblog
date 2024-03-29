<?php

namespace App\Controller;

use App\Entity\Post\Tag;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/etiquettes')]
class TagController extends AbstractController
{
    #[Route('/{slug}', name: 'tag_index', methods: [Request::METHOD_GET])]
    public function index(
        Request $request,
        Tag $tag,
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
                'tag' => $tag,
                'posts' => $posts
            ]);
        }

        $posts = $postRepository->findPublished(
            $request->query->getInt('page', 1), null, $tag
        );

        return $this->render('tag/index.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
            'posts' => $posts
        ]);
    }
}