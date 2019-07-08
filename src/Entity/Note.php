<?php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
 *                      "access_control_message"="Only admins can add note."
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
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 */
class Note
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getTeam(): ?team
    {
        return $this->team;
    }

    public function setTeam(?team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
