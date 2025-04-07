<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\InfosRepository;

#[ORM\Entity(repositoryClass: InfosRepository::class)]
class Infos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide.")]
    private ?string $title = null;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le contenu est obligatoire.")]
    private ?string $content = null;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank(message: "La date de publication est requise.")]
    private ?\DateTimeInterface $publishDate = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\User")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $publisher = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Assert\File(
        mimeTypes: ["image/png", "image/jpg", "image/jpeg", "image/gif"],
        mimeTypesMessage: "Le format de l'image est invalide. Formats acceptés : PNG, JPG, JPEG, GIF."
    )]
    private ?string $imagePath = null;

    // ----------------------
    // Getters et setters
    // ----------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;
        return $this;
    }

    public function getPublisher(): ?User
    {
        return $this->publisher;
    }

    public function setPublisher(?User $publisher): self
    {
        $this->publisher = $publisher;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }
}
