<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vardas negali buti tuscias")
     * @Assert\Length(
     * min = 3,
     * max = 255,
     * minMessage = "Vardas per trumpas, turi buti bent {{ limit }} ilgio",
     * maxMessage = "Vardas per ilgas, maksimalus ilgis {{ limit }}"
     * )
     * @Assert\Regex(
     * pattern     = "/^[a-z]+/i",
     * htmlPattern = "^[a-zA-Z]+",
     * message = "Pavadinime negali būti simbolių" 
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Vardas negali buti tuscias")
     * @Assert\Length(
     * min = 1,
     * max = 10,
     * minMessage = "Puslapių skaičius turi būti bent {{ limit }} ilgio",
     * maxMessage = "Puslapių skaičiaus maksimalus ilgis {{ limit }}"
     * )
     * @Assert\Regex(
     * pattern     = "/^[a-z]+/i",
     * htmlPattern = "^[a-zA-Z]+",
     * message = "Pavadinime negali būti simbolių" 
     * )
     */
    private $pages;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Vardas negali buti tuscias")
     * @Assert\Length(
     * min = 1,
     * max = 64,
     * minMessage = "ISBN ilgis turi būti bent {{ limit }} ilgio",
     * maxMessage = "ISBN ilgis maksimalus {{ limit }}"
     * )
     */
    private $isbn;

    /**
     * @ORM\Column(type="text")
     */
    private $short_description;

    /**
     * @ORM\Column(type="integer")
     */
    private $author_id;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     */
    private $author;

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

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(string $short_description): self
    {
        $this->short_description = $short_description;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
