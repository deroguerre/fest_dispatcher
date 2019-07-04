<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
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
 *                      "access_control_message"="Only admins can add Festival."
 *                   }
 *              },
 *              itemOperations={
 *                  "get"={
 *                      "access_control"="is_granted('ROLE_ADMIN') and object.owner == user"
 *                  },
 *                  "put"={
 *                      "access_control"="is_granted('ROLE_ADMIN') and previous_object.owner == user"
 *                  },
 *                  "delete"={
 *                      "access_control"="is_granted('ROLE_ADMIN') and previous_object.owner == user"
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
     * @ORM\Column(type="string", length=255)
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
     * @ORM\OneToMany(targetEntity="App\Entity\VolunteerAvailability", mappedBy="festival")
     */
    private $volunteerAvailabilities;

    public function __construct()
    {
        $this->volunteerAvailabilities = new ArrayCollection();
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

}
