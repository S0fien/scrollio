<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="posts")
 * @Vich\Uploadable
 * */

class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;
    /**
     * @ORM\Column(type="string", name="title")
     * @Assert\Length(
     *     min = 3,
     *     max = 15,
     *     minMessage = "Ce titre est trop court",
     *     maxMessage = "Ce titre est trop long"
     * )
     */
    public $title;
    /**
     * @ORM\Column(type="text", name="type")
     * @Assert\Choice({"photo", "video"})
     */
    public $type;
    /**
     * @ORM\Column(type="text", name="content")
     *
     */
    public $content;
    /**
     * @ORM\Column(type="datetime", name="date", options={"default": "CURRENT_TIMESTAMP"})
     */
    public $date;
    /**
     * @ORM\Column(type="string", name="author")
     * @Assert\NotBlank(message = "Un auteur doit être associé à l'article")
     */
    public $author;
    /**
     * @ORM\Column(type="string", name="category")
     */
    public $category;

    /**
     * @ORM\Column(type="integer", name="author_id")
     */
    private $author_id;

    /**
     * @ORM\Column(type="string", length=20, name="status")
     */
    private $status;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName", dimensions="image.dimensions")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    private $image;
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->status = "pending";
        $this->image = new EmbeddedFile();
        //$this->date = new \Date('Y-m-d G:i:s');
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor_Id(): ?int
    {
        return $this->author_id;
    }

    public function setAuthor_Id(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    /**
     *@param File|UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            //$this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(EmbeddedFile $image)
    {
        $this->image = $image;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
    }
}