<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="comments")
 * */

class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;
    /**
     * @ORM\Column(type="integer",  name="postId")
     */
    public $postId;
    /**
     * @ORM\Column(type="text", name="content")
     * @Assert\NotBlank(message = "Un contenu doit être associé à l'article")
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

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->category = "test";
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
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @param mixed $postId
     */
    public function setPostId($postId): void
    {
        $this->postId = $postId;
    }
}