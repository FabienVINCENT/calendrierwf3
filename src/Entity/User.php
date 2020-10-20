<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Votre email est déjà enregistré")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "Le mail '{{ value }}' n'est pas valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string confirm password
     */
    private $confirmPassword;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=17)
     * @Assert\Regex(
     *     pattern="/^((\+)33)[1-9](\d{2}){4}$/",
     *     match=true,
     *     message="Votre numero doit être sour forme : '+330612345678'"
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="users")
     */
    private $talents;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Animer::class, mappedBy="fkAnimerUser")
     */
    private $animers;

    public function __construct()
    {
        $this->talents = new ArrayCollection();
        $this->animers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getTalents(): Collection
    {
        return $this->talents;
    }

    public function addTalent(Competence $talent): self
    {
        if (!$this->talents->contains($talent)) {
            $this->talents[] = $talent;
        }

        return $this;
    }

    public function removeTalent(Competence $talent): self
    {
        if ($this->talents->contains($talent)) {
            $this->talents->removeElement($talent);
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setPseudo(): self
    {
        $this->pseudo = ucfirst(substr($this->getFirstname(), 0, 1)) . '. ' . ucfirst($this->getLastname());

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
            $animer->setFkAnimerUser($this);
        }

        return $this;
    }

    public function removeAnimer(Animer $animer): self
    {
        if ($this->animers->contains($animer)) {
            $this->animers->removeElement($animer);
            // set the owning side to null (unless already changed)
            if ($animer->getFkAnimerUser() === $this) {
                $animer->setFkAnimerUser(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        $competences = $this->getTalents();
        $talentStr = '';
        foreach ($competences as $talent) {
            $talentStr .= $talent->getNom() . ' - ';
        }
        return $this->getPseudo() . ' : ' . substr($talentStr, 0, -3);
    }
}
