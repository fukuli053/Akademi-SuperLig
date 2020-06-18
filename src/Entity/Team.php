<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
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
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $played;

    /**
     * @ORM\Column(type="integer")
     */
    private $won;

    /**
     * @ORM\Column(type="integer")
     */
    private $drawn;

    /**
     * @ORM\Column(type="integer")
     */
    private $lost;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalsFor;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalsAgainst;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalDifference;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="homeTeam", orphanRemoval=true)
     */
    private $homeGames;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="awayTeam", orphanRemoval=true)
     */
    private $awayGames;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * Team constructor.
     */
    public function __construct()
    {
        $this->played = 0;
        $this->won = 0;
        $this->drawn = 0;
        $this->lost = 0;
        $this->goalsFor = 0;
        $this->goalsAgainst = 0;
        $this->goalDifference = 0;
        $this->homeGames = new ArrayCollection();
        $this->awayGames = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPlayed(): ?int
    {
        return $this->played;
    }

    public function setPlayed(int $played): self
    {
        $this->played = $played;

        return $this;
    }

    public function getWon(): ?int
    {
        return $this->won;
    }

    public function setWon(int $won): self
    {
        $this->won = $won;

        return $this;
    }

    public function getDrawn(): ?int
    {
        return $this->drawn;
    }

    public function setDrawn(int $drawn): self
    {
        $this->drawn = $drawn;

        return $this;
    }

    public function getLost(): ?int
    {
        return $this->lost;
    }

    public function setLost(int $lost): self
    {
        $this->lost = $lost;

        return $this;
    }

    public function getGoalsFor(): ?int
    {
        return $this->goalsFor;
    }

    public function setGoalsFor(int $goalsFor): self
    {
        $this->goalsFor = $goalsFor;

        return $this;
    }

    public function getGoalsAgainst(): ?int
    {
        return $this->goalsAgainst;
    }

    public function setGoalsAgainst(int $goalsAgainst): self
    {
        $this->goalsAgainst = $goalsAgainst;

        return $this;
    }

    public function getGoalDifference(): ?int
    {
        return $this->goalDifference;
    }

    public function setGoalDifference(int $goalDifference): self
    {
        $this->goalDifference = $goalDifference;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getHomeGames(): Collection
    {
        return $this->homeGames;
    }

    public function addHomeGame(Game $homeGame): self
    {
        if (!$this->homeGames->contains($homeGame)) {
            $this->homeGames[] = $homeGame;
            $homeGame->setHomeTeam($this);
        }

        return $this;
    }

    public function removeHomeGame(Game $homeGame): self
    {
        if ($this->homeGames->contains($homeGame)) {
            $this->homeGames->removeElement($homeGame);
            // set the owning side to null (unless already changed)
            if ($homeGame->getHomeTeam() === $this) {
                $homeGame->setHomeTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getAwayGames(): Collection
    {
        return $this->awayGames;
    }

    public function addAwayGame(Game $awayGame): self
    {
        if (!$this->awayGames->contains($awayGame)) {
            $this->awayGames[] = $awayGame;
            $awayGame->setAwayTeam($this);
        }

        return $this;
    }

    public function removeAwayGame(Game $awayGame): self
    {
        if ($this->awayGames->contains($awayGame)) {
            $this->awayGames->removeElement($awayGame);
            // set the owning side to null (unless already changed)
            if ($awayGame->getAwayTeam() === $this) {
                $awayGame->setAwayTeam(null);
            }
        }

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

}
