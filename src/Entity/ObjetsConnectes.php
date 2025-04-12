<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ObjetsConnectesRepository;

#[ORM\Entity(repositoryClass: ObjetsConnectesRepository::class)]
class ObjetsConnectes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'objet connecté ne peut pas être vide.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de l'objet est requis.")]
    private ?string $type = null; // Type de l'objet connecté

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La marque de l'objet est requise.")]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'état de l'objet est requis.")]
    private ?string $etat = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $consommationEnergetique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $connectivite = null; // Connectivité (Wi-Fi, Bluetooth,  etc.)

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $derniereInteraction = null; // Dernière interaction

    // Attributs spécifiques au type Thermostat
    #[ORM\Column(type: "float", nullable: true)]
    private ?float $temperatureActuelle = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $temperatureCible = null;

    // Attributs spécifiques au type Porte
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatPorte = null; // Ouverte, Fermée, Verrouillée

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $etatBatterie = null;

    // Attributs spécifiques au type Caméra
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resolutionCamera = null; // Résolution (exemple : 1080p, 4K)

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $angleVision = null; // Angle de vision en degrés

    // Attributs spécifiques au type Photocopieuse
    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $niveauStockMAX = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $niveauStock = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $nombreCopiesParJour = null; // Copies effectuées par jour

    // ---------------------------
    // GETTERS & SETTERS
    // ---------------------------
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getNom(): ?string
    {
        return $this->nom;
    }
    
    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }
    
    public function getType(): ?string
    {
        return $this->type;
    }
    
    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }
    
    public function getTemperatureActuelle(): ?float
    {
        return $this->temperatureActuelle;
    }
    
    public function setTemperatureActuelle(?float $temperatureActuelle): static
    {
        $this->temperatureActuelle = $temperatureActuelle;
        return $this;
    }
    
    public function getTemperatureCible(): ?float
    {
        return $this->temperatureCible;
    }
    
    public function setTemperatureCible(?float $temperatureCible): static
    {
        $this->temperatureCible = $temperatureCible;
        return $this;
    }
    
    public function getNiveauStockMAX(): ?int
    {
        return $this->niveauStockMAX;
    }
    
    public function setNiveauStockMAX(?int $niveauStockMAX): static
    {
        $this->niveauStockMAX = $niveauStockMAX;
        return $this;
    }
    
    public function getNiveauStock(): ?int
    {
        return $this->niveauStock;
    }
    
    public function setNiveauStock(?int $niveauStock): static
    {
        $this->niveauStock = $niveauStock;
        return $this;
    }
    
    public function getEtatPorte(): ?string
    {
        return $this->etatPorte;
    }
    
    public function setEtatPorte(?string $etatPorte): static
    {
        $this->etatPorte = $etatPorte;
        return $this;
    }
    
    public function getConnectivite(): ?string
    {
        return $this->connectivite;
    }
    
    public function setConnectivite(?string $connectivite): static
    {
        $this->connectivite = $connectivite;
        return $this;
    }
    
    public function getEtatBatterie(): ?int
    {
        return $this->etatBatterie;
    }
    
    public function setEtatBatterie(?int $etatBatterie): static
    {
        $this->etatBatterie = $etatBatterie;
        return $this;
    }
    
    public function getDerniereInteraction(): ?\DateTimeInterface
    {
        return $this->derniereInteraction;
    }
    
    public function setDerniereInteraction(?\DateTimeInterface $derniereInteraction): static
    {
        $this->derniereInteraction = $derniereInteraction;
        return $this;
    }
    
    public function getMarque(): ?string
    {
        return $this->marque;
    }
    
    public function setMarque(string $marque): static
    {
        $this->marque = $marque;
        return $this;
    }
    
    public function getEtat(): ?string
    {
        return $this->etat;
    }
    
    public function setEtat(string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }
    
    public function getConsommationEnergetique(): ?float
    {
        return $this->consommationEnergetique;
    }
    
    public function setConsommationEnergetique(?float $consommationEnergetique): static
    {
        $this->consommationEnergetique = $consommationEnergetique;
        return $this;
    }
    
    public function getMode(): ?string
    {
        return $this->mode;
    }
    
    public function setMode(?string $mode): static
    {
        $this->mode = $mode;
        return $this;
    }

public function getResolutionCamera(): ?string
{
    return $this->resolutionCamera;
}

public function setResolutionCamera(?string $resolutionCamera): static
{
    $this->resolutionCamera = $resolutionCamera;
    return $this;
}

public function getAngleVision(): ?int
{
    return $this->angleVision;
}

public function setAngleVision(?int $angleVision): static
{
    $this->angleVision = $angleVision;
    return $this;
}

public function getNombreCopiesParJour(): ?int
{
    return $this->nombreCopiesParJour;
}

public function setNombreCopiesParJour(?int $nombreCopiesParJour): static
{
    $this->nombreCopiesParJour = $nombreCopiesParJour;
    return $this;
}


}
