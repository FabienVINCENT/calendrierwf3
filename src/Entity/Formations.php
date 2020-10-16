<?php

namespace App\Entity;

use App\Repository\FormationsRepository;
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
}
