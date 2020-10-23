<?php

namespace App\Entity;

use App\Repository\AnimerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnimerRepository::class)
 */
class Animer
{
    // Définition des variables d'heure des journée/demi-journée
    const DEBUT_MATINNEE = 9;
    const FIN_MATINNEE = 13;
    const DEBUT_APRESMIDI = 14;
    const FIN_APRESMIDI = 17;

    const JOURNEE = [0 => 'Journée complète', 1 => 'Matin', 2 => 'Après-midi'];


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
     * @ORM\ManyToOne(targetEntity=Formations::class, inversedBy="animers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkAnimerFormation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="animers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkAnimerUser;

    /**
     * @ORM\Column(type="smallint")
     *      * @Assert\Range(min = 0,max = 2)
     */
    private $typeJournee;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getTypeJournee(): ?int
    {
        return $this->typeJournee;
    }

    public function getTypeFormatedJournee(): ?string
    {
        return self::JOURNEE[$this->typeJournee];
    }

    public function setTypeJournee(int $typeJournee): self
    {
        $this->typeJournee = $typeJournee;

        return $this;
    }
}
