<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\user")
     */
    private $managers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\user")
     */
    private $neededUsers;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $backgroundColor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="subteams")
     */
    private $subteams;

    public function __construct()
    {
        $this->managers = new ArrayCollection();
        $this->neededUsers = new ArrayCollection();
    }

    public function getId(): ?int
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
     * @return Collection|user[]
     */
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(user $manager): self
    {
        if (!$this->managers->contains($manager)) {
            $this->managers[] = $manager;
        }

        return $this;
    }

    public function removeManager(user $manager): self
    {
        if ($this->managers->contains($manager)) {
            $this->managers->removeElement($manager);
        }

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getNeededUsers(): Collection
    {
        return $this->neededUsers;
    }

    public function addNeededUser(user $neededUser): self
    {
        if (!$this->neededUsers->contains($neededUser)) {
            $this->neededUsers[] = $neededUser;
        }

        return $this;
    }

    public function removeNeededUser(user $neededUser): self
    {
        if ($this->neededUsers->contains($neededUser)) {
            $this->neededUsers->removeElement($neededUser);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getSubteams(): ?self
    {
        return $this->subteams;
    }

    public function setSubteams(?self $subteams): self
    {
        $this->subteams = $subteams;

        return $this;
    }
}
