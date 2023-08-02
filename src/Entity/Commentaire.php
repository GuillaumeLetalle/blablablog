<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contenu = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?User $fk_user = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Article $fk_article = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $isvalide = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getFkUser(): ?user
    {
        return $this->fk_user;
    }

    public function setFkUser(?user $fk_user): static
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    public function getFkArticle(): ?article
    {
        return $this->fk_article;
    }

    public function setFkArticle(?article $fk_article): static
    {
        $this->fk_article = $fk_article;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIsvalide(): ?bool
    {
        return $this->isvalide;
    }

    public function setIsvalide(bool $isvalide): static
    {
        $this->isvalide = $isvalide;

        return $this;
    }

}
