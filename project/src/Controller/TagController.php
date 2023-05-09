<?php

namespace App\Controller;

use App\Entity\Post\Tag;
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
        $posts = $postRepository->findPublished(
            $request->query->getInt('page', 1), null, $tag
        );

        return $this->render('tag/index.html.twig', [
            'tag' => $tag,
            'posts' => $posts
        ]);
    }
}