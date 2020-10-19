<?php

namespace App\Entity;

use App\Repository\AnimerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnimerRepository::class)
 */
class Animer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $demiJournee;

    /**
     * @ORM\ManyToOne(targetEntity=Formations::class, inversedBy="animers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkAnimerFormation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="animers")
     */
    private $fkAnimerUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDemiJournee(): ?bool
    {
        return $this->demiJournee;
    }

    public function setDemiJournee(bool $demiJournee): self
    {
        $this->demiJournee = $demiJournee;

        return $this;
    }

    public function getFkAnimerFormation(): ?Formations
    {
        return $this->fkAnimerFormation;
    }

    public function setFkAnimerFormation(?Formations $fkAnimerFormation): self
    {
        $this->fkAnimerFormation = $fkAnimerFormation;

        return $this;
    }

    public function getFkAnimerUser(): ?User
    {
        return $this->fkAnimerUser;
    }

    public function setFkAnimerUser(?User $fkAnimerUser): self
    {
        $this->fkAnimerUser = $fkAnimerUser;

        return $this;
    }
}
