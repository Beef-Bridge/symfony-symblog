<?php

namespace App\Entity\Comment;

use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Comment\CommentRepository;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    private ?int $id = null;

    private string $content;

    private bool $isApproved = false;

    private User $author;

    private Post $post;

    private DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

}