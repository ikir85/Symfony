<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="groups")
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var Collection
     * Relation n-n entre Group et User
     * L'attribut qui fait la relation dans la classe User et groups
     * @ORM\ManyToMany(targetEntity="User", inversedBy="groups")
     * table de relation en bdd
     * @ORM\JoinTable(name="user_group")
     */
    private $users;

    /**
     * Group constructor.
     * @param Collection $users
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();

    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     * @return Group
     */
    public function setUsers(Collection $users): Group
    {
        $this->users = $users;
        return $this;
    }



}
