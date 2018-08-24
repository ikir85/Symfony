<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * la classe correspond à une table user en bdd
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * clé primaire
     * @ORM\Id()
     * auto-incremente
     * @ORM\GeneratedValue()
     * champ de type integer en bdd
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * lastname : varchar(100) NOT NULL en bdd
     * @ORM\Column(type="string", length=100)
     */
    private $lastname;

    /**
     * firstname : varchar(100) NOT NULL en bdd
     * @ORM\Column(type="string", length=100)
     */
    private $firstname;

    /**
     * email : varchar(255) NOT NULL unique
     * @ORM\Column(type="string", length=255, unique=false)
     */
    private $email;

    /**
     * @var \Datetime
     * birthdate : date NOT NULL en bdd
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @var ArrayCollection
     * @ ORM\OneToMany (facultatif) permet de pouvoir acceder aux publications depuis un objet user dans cet attribut
     * mappedBy dit auel attribut dans Publication definit la clé etrangere avec ManyToOne ()
     * @ORM\OneToMany(targetEntity="Publication", mappedBy="author", cascade={"persist"})
     *
     */
    private $publications;

    /**
     * @var Collection
     * Relation n_n entre User et Group définie sur l'attribut $users de Group
     * @ORM\ManyToMany(targetEntity="Group", mappedBy="users")
     *
     */

    private $groups;

    public function __construct()
    {

        $this->publications = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    /**
     * @param ArrayCollection $publication
     * @return Publication
     */
    public function setPublications(Collection $publications): User
    {
        $this->publications = $publications;
        return $this;
    }

    public function addPublication(Publication $publication)
    {
        //on ajoute la publication a l'utilisateur
        $this->publications -> add($publication);
       //eq: $this->publications[] = $publication;

        // on definit l'auteur de la publication avec l'objet User qui appelle la methode
        $publication->setAuthor($this);
    }

    /**
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param Collection $groups
     * @return User
     */
    public function setGroups(Collection $groups): User
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @return string
     */
   public function __toString()
   {
      return  $this->firstname. ' '.$this->lastname;
   }

}
