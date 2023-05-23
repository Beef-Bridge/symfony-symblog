<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SearchType;
use App\Form\CommentType;
use App\Model\SearchData;
use App\Entity\Post\Comment;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/article/{slug}', name: 'post_details', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function details(
        Post $post,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $comment = new Comment();
        $comment->setPost($post);
        if($this->getUser()) {
            $comment->setAuthor($this->getUser());
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été enregistré. Il sera soumis à modération dans les plus brefs délais.');

            return $this->redirectToRoute('post.show', ['slug' => $post->getSlug()]);
        }

        return $this->render('post/details.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}
