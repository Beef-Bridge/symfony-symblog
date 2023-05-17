<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue('CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $avatar;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lastname = null;
    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_USER'];

    private string $plainPassword = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $passord;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updateddAt;

    public function getId(): ?int
    {
        return $this->id;
    }
}
