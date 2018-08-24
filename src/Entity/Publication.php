<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicationRepository")
 */
class Publication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;



    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var User
     * en bdd, une clé etrangere vers la table user
     * inversedBy() doit ajouté si on a ajouté un OnetoMany côté user (facultatif)
     * @ORM\ManyToOne(targetEntity="User", inversedBy="publications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;


    public function getId()
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

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     * @return Publication
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }




}
