<?php

namespace App\Entity;

use App\Repository\FormationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormationsRepository::class)
 */
class Formations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank (message = "Champ obligatoire")
     * @Assert\Length(min = 5, max = 60,
     *      minMessage = "Veuillez entrer 5 caractère au minimum",
     *      maxMessage = "Veuillez entrer 60 caractère au maximum"
     * )
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Endroit::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $localisation;

    /**
     * @ORM\OneToMany(targetEntity=Animer::class, mappedBy="fkAnimerFormation")
     */
    private $animers;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    public function __construct()
    {
        $this->animers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLocalisation(): ?Endroit
    {
        return $this->localisation;
    }

    public function setLocalisation(?Endroit $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * @return Collection|Animer[]
     */
    public function getAnimers(): Collection
    {
        return $this->animers;
    }

    public function addAnimer(Animer $animer): self
    {
        if (!$this->animers->contains($animer)) {
            $this->animers[] = $animer;
            $animer->setFkAnimerFormation($this);
        }

        return $this;
    }

    public function removeAnimer(Animer $animer): self
    {
        if ($this->animers->contains($animer)) {
            $this->animers->removeElement($animer);
            // set the owning side to null (unless already changed)
            if ($animer->getFkAnimerFormation() === $this) {
                $animer->setFkAnimerFormation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNom() . ' - ' . $this->getLocalisation()->getVille();
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

}
