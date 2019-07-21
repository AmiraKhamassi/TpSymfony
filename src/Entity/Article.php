<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 10,
     * max = 255,
     * minMessage = "Le titre doit faire plus de 10 caracteres !",
     * maxMessage = "Le titre ne doit pas faire plus de 255 caracteres !"
     *  )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $smallDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $longDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageBigOne = "";

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

    public function getSmallDescription(): ?string
    {
        return $this->smallDescription;
    }

    public function setSmallDescription(string $smallDescription): self
    {
        $this->smallDescription = $smallDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): self
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getImageBigOne(): ?string
    {
        return $this->imageBigOne;
    }

    public function setImageBigOne(string $imageBigOne): self
    {
        $this->imageBigOne = $imageBigOne;

        return $this;
    }
}
