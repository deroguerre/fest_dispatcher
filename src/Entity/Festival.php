<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *              attributes={
 *                  "access_control"="is_granted('ROLE_ADMIN')"
 *              },
 *              collectionOperations={
 *                  "get",
 *                  "post"={
 *                      "access_control"="is_granted('ROLE_ADMIN')",
 *                      "access_control_message"="Only admins can add job."
 *                   }
 *              },
 *              itemOperations={
 *                  "get"={
 *                      "access_control"="is_granted('ROLE_ADMIN')"
 *                  },
 *                  "put"={
 *                      "access_control"="is_granted('ROLE_ADMIN')"
 *                  },
 *                  "delete"={
 *                      "access_control"="is_granted('ROLE_ADMIN')"
 *                  },
 *              }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\FestivalRepository")
 */

class Festival
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255),
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VolunteerAvailability", mappedBy="festival", cascade={"remove"})
     */
    private $volunteerAvailabilities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="festival", cascade={"remove"})
     */
    private $teams;

    public function __construct()
    {
        $this->volunteerAvailabilities = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->name;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|VolunteerAvailability[]
     */
    public function getVolunteerAvailabilities(): Collection
    {
        return $this->volunteerAvailabilities;
    }

    public function addVolunteerAvailability(VolunteerAvailability $volunteerAvailability): self
    {
        if (!$this->volunteerAvailabilities->contains($volunteerAvailability)) {
            $this->volunteerAvailabilities[] = $volunteerAvailability;
            $volunteerAvailability->setFestival($this);
        }

        return $this;
    }

    public function removeVolunteerAvailability(VolunteerAvailability $volunteerAvailability): self
    {
        if ($this->volunteerAvailabilities->contains($volunteerAvailability)) {
            $this->volunteerAvailabilities->removeElement($volunteerAvailability);
            // set the owning side to null (unless already changed)
            if ($volunteerAvailability->getFestival() === $this) {
                $volunteerAvailability->setFestival(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setFestival($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getFestival() === $this) {
                $team->setFestival(null);
            }
        }

        return $this;
    }

}
