<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *              normalizationContext={"groups"={"festival"}},
 *              attributes={
 *                  "access_control"="is_granted('ROLE_ADMIN')"
 *              },
 *              collectionOperations={
 *                  "get",
 *                  "post"={
 *                      "access_control"="is_granted('ROLE_ADMIN')",
 *                      "access_control_message"="Only admins can add team."
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
     * @Groups("festival")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $backgroundColor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="teamsThatIManage")
     * @ORM\JoinTable(name="team_manager")
     */
    private $managers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="teamsThatIHelp")
     * @ORM\JoinTable(name="team_volunteer")
     */
    private $volunteers;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $neededVolunteers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Job", mappedBy="team")
     * @Groups("festival")
     */
    private $jobs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="team")
     */
    private $notes;

    /**
     * @var Team $subteams
     */
    private $subteams;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Festival", inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $festival;

    public function __construct()
    {
        $this->managers = new ArrayCollection();
        $this->jobs = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->volunteers = new ArrayCollection();
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

    /**
     * @return Collection|user[]|null
     */
    public function getManagers(): ?Collection
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

    public function getNeededVolunteers(): ?int
    {
        return $this->neededVolunteers;
    }

    public function setNeededVolunteers(?int $neededVolunteers): self
    {
        $this->neededVolunteers = $neededVolunteers;

        return $this;
    }

    /**
     * @return Collection|Job[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setTeam($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->contains($job)) {
            $this->jobs->removeElement($job);
            // set the owning side to null (unless already changed)
            if ($job->getTeam() === $this) {
                $job->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setTeam($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getTeam() === $this) {
                $note->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Team
     */
    public function getSubteams(): Team
    {
        return $this->subteams;
    }

    /**
     * @param Team $team
     * @return Team
     */
    public function addSubteam(Team $team): self
    {
        $this->subteams[] = $team;

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

    /**
     * @return Collection|User[]|null
     */
    public function getVolunteers(): ?Collection
    {
        return $this->volunteers;
    }

    public function addVolunteer(User $volunteer): self
    {
        if (!$this->volunteers->contains($volunteer)) {
            $this->volunteers[] = $volunteer;
        }

        return $this;
    }

    public function removeVolunteer(User $volunteer): self
    {
        if ($this->volunteers->contains($volunteer)) {
            $this->volunteers->removeElement($volunteer);
        }

        return $this;
    }

}
