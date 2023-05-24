<?php

namespace App\Controller;

use App\Entity\Post\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/comment/{id}', name: 'comment_delete')]
    public function delete(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        $params = [];

        return $this->redirectToRoute('post_details', $params);
    }
}