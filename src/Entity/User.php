<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use DateTime;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Stocké sous forme de JSON pour permettre de gérer plusieurs rôles
    #[ORM\Column(type: "json")]
    private array $roles = ['ROLE_SIMPLE'];

    #[ORM\Column(type: "string", length: 20, options: ["default" => "pending"])]
    private string $status = "pending";  // "pending" pour en attente de validation, "active" pour validé

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }
    
    // Propriétés publiques

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;  // Pseudonyme (login)

    #[ORM\Column(type: "integer")]
    private ?int $age = null;  // Âge de l'utilisateur

    #[ORM\Column(type: "string", length: 20)]
    private ?string $sex = null;  // Sexe/Genre

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $birthdate = null;  // Date de naissance

    #[ORM\Column(type: "string", length: 255)]
    private ?string $memberType = null;  // Type de membre (développeur, testeur, etc.)

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $profilePicture = null;  // URL de la photo de profil

    // Système de points
    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $points = 0;  

    // Couleur du site (par exemple un code ou une valeur numérique)
    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $color = 0; 

    #[ORM\Column(type: "string", length: 20, options: ["default" => "débutant"])]
    private string $experienceLevel = "débutant"; // débutant, intermédiaire, avancé, expert

    // Gestion des points et mise à jour du niveau d'expérience
    public function getPoints(): int
    {
        return $this->points;
    }

    public function getColor(): int
    {
        return $this->color;
    }

    public function setColor(int $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;
        $this->updateExperienceLevel();
        return $this;
    }

    public function addPoints(int $points): static
    {
        $this->points += $points;
        $this->updateExperienceLevel();
        return $this;
    }

    public function getExperienceLevel(): string
    {
        return $this->experienceLevel;
    }

    public function setExperienceLevel(string $experienceLevel): static
    {
        $this->experienceLevel = $experienceLevel;
        return $this;
    }

    // Mise à jour du niveau d'expérience en fonction des points obtenus
    private function updateExperienceLevel(): void
    {
        if ($this->points >= 10) {
            $this->experienceLevel = "expert";
        } elseif ($this->points >= 5) {
            $this->experienceLevel = "advanced";
            $this->roles = ["ROLE_COMPLEX"]; // Rôle complexe pour les utilisateurs avancés
        } elseif ($this->points >= 3) {
            $this->experienceLevel = "intermediate";
        } else {
            $this->experienceLevel = "beginner";
            $this->roles = ["ROLE_SIMPLE"]; // Rôle débutant pour les utilisateurs débutants
        }
    }

    // Retourne les rôles de l'utilisateur en ajoutant toujours "ROLE_USER"
    public function getRoles(): array
    {
        return array_merge($this->roles, ['ROLE_USER']);
    }

    public function setRoles(array $roles): self
{
    $this->roles = $roles;
    return $this;
}

    // Propriétés privées

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;  // Prénom de l'utilisateur

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;  // Nom de l'utilisateur

    #[ORM\Column]
    private ?string $password = null;  // Mot de passe

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    private ?string $email = null; // Email de l'utilisateur

    // Getters / Setters pour les propriétés publiques et privées

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function __toString(): string
    {
        return $this->getUsername();
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;
        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): static
    {
        $this->sex = $sex;
        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

     // Calculer l'âge à partir de la date de naissance
        $now = new DateTime();
        $this->age = $now->diff($birthdate)->y;

        return $this;
    }

    public function getMemberType(): ?string
    {
        return $this->memberType;
    }

    public function setMemberType(string $memberType): static
    {
        $this->memberType = $memberType;
        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function eraseCredentials(): void
    {
        
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }
}
