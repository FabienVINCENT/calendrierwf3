<?php

namespace App\Entity;

use App\Repository\EndroitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EndroitRepository::class)
 */
class Endroit
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
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Formations::class, mappedBy="localisation")
     */
    private $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|Formations[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formations $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setLocalisation($this);
        }

        return $this;
    }

    public function removeFormation(Formations $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
            // set the owning side to null (unless already changed)
            if ($formation->getLocalisation() === $this) {
                $formation->setLocalisation(null);
            }
        }

        return $this;
    }

    public function __toString () {

        return $this->getVille ();
    }
}
