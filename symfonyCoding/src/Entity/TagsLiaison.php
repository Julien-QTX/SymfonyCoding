<?php

namespace App\Entity;

use App\Repository\TagsLiaisonRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Tags;
use App\Entity\Articles;

#[ORM\Entity(repositoryClass: TagsLiaisonRepository::class)]
class TagsLiaison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Tags::class)]
    #[ORM\JoinColumn(name: 'id_tag', referencedColumnName: 'id')]
    private ?Tags $id_tag = null;

    #[ORM\ManyToOne(targetEntity: Articles::class)]
    #[ORM\JoinColumn(name: 'id_article', referencedColumnName: 'id')]
    private ?Articles $id_article = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getIdTag(): ?int
    {
        return $this->id_tag ? $this->id_tag->getId() : null;
    }

    // public function getIdTag(): ?int
    // {
    //     return $this->id_tag;
    // }

    public function setIdTag(?Tags $id_tag): static
    {
        $this->id_tag = $id_tag;

        return $this;
    }

    public function getIdArticle(): ?int
    {
        return $this->id_article;
    }

    public function setIdArticle(?Articles $id_article): static
    {
        $this->id_article = $id_article;

        return $this;
    }
}