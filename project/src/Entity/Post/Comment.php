<?php

namespace App\Entity\Comment;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\Comment\CommentRepository;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{

}