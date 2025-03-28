<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 20, options: ["default" => "ROLE_SIMPLE"])]
    private string $roles = "ROLE_SIMPLE"; // Rôle par défaut


    // Prop. publiques

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;  // Pseudonyme (login)


    #[ORM\Column(type: "integer")]
    private ?int $age = null;  // Age de l'utilisateur


    #[ORM\Column(type: "string", length: 20)]
    private ?string $sex = null;  // Sexe/Genre


    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $birthdate = null;  // Date de naissance

    #[ORM\Column(type: "string", length: 255)]
    private ?string $memberType = null;  // Type de membre (développeur, testeur, etc.)


    #[ORM\Column(type: "string", nullable: true)]
    private ?string $profilePicture = null;  // URL de la photo de profil

    //Système de pts
    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $points = 0;  



    #[ORM\Column(type: "string", length: 20, options: ["default" => "débutant"])]
    private string $experienceLevel = "débutant"; // débutant, intermédiaire, avancé, expert

    // Méthodes de gestion des points et des niveaux
    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        // Màj de l'expérience en fonct° des pts
        $this->updateExperienceLevel();

        return $this;
    }

    public function addPoints(int $points): static
    {
        $this->points += $points;

        // Màj de l'xp de l'utilisateur
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

    // Màj du niveau d'expérience en fonction des points
    private function updateExperienceLevel(): void
    {
        if ($this->points >= 10) {
            $this->experienceLevel = "expert";
            $this->roles = ["ROLE_ADMIN"]; // Rôle d'administrateur pour les utilisateurs experts
        } elseif ($this->points >= 5) {
            $this->experienceLevel = "advanced";
            $this->roles = ["ROLE_COMPLEX"]; // Rôle complexe pour les utilisateurs avancés
        } elseif ($this->points >= 3) {
            $this->experienceLevel = "intermediate";
            $this->roles = ["ROLE_SIMPLE"]; // Rôle simple pour les utilisateurs intermédiaires
        } else {
            $this->experienceLevel = "beginner";
            $this->roles = ["ROLE_SIMPLE"]; // Rôle débutant pour les utilisateurs débutants
        }
    }

    public function getRoles(): array
    {
        return array_merge($this->roles, ['ROLE_USER']);
    }


    // Prop. privées

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;  // Prénom de l'utilisateur (privé)

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;  // Nom de l'utilisateur (privé)

    #[ORM\Column]
    private ?string $password = null;  // Mot de passe

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    private ?string $email = null; // Email de l'utilisateur


    // Getters / Setters pour les propriétés

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

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;
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

    public function setPassword(string $password, UserPasswordEncoderInterface $passwordEncoder): static
{
    // Hashage du mdp
    $this->password = $passwordEncoder->encodePassword($this, $password);
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
        // Méthode vide pour effacer les données sensibles
    }

    // Méthode pour l'authentification
    public function getUserIdentifier(): string
    {
        return $this->username; // Utilisé pour l'authentification
    }
}
