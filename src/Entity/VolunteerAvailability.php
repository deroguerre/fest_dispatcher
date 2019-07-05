<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *              attributes={
 *                  "access_control"="is_granted('ROLE_USER')"
 *              },
 *              collectionOperations={
 *                  "get",
 *                  "post"={
 *                      "access_control"="is_granted('ROLE_USER')",
 *                   }
 *              },
 *              itemOperations={
 *                  "get"={
 *                      "access_control"="is_granted('ROLE_USER') and object.owner == user"
 *                  },
 *                  "put"={
 *                      "access_control"="is_granted('ROLE_USER') and previous_object.owner == user"
 *                  },
 *                  "delete"={
 *                      "access_control"="is_granted('ROLE_USER') and previous_object.owner == user"
 *                  },
 *              }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\VolunteerAvailabilityRepository")
 */
class VolunteerAvailability
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Festival", inversedBy="volunteerAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $festival;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="volunteerAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("festival")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): self
    {
        $this->festival = $festival;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
