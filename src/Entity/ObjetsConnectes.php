<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ObjetsConnectesRepository::class)]
class ObjetsConnectes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;  // Nom de l'objet connecté

    #[ORM\Column(length: 255)]
    private ?string $type = null;  // Type de l'objet (thermostat, caméra, prise, etc.)

    //Propriétés spécifiques : 
        //Thermostats
            #[ORM\Column(type: "float", nullable: true)]
            private ?float $temperatureActuelle = null; // Température actuelle (thermostat seulement)

            #[ORM\Column(type: "float", nullable: true)]
            private ?float $temperatureCible = null; // Température cible (thermostat seulement)
        
        //Distributeurs / Photocopieuses
            #[ORM\Column(type: "integer", nullable: true)]
            private ?int $niveauStockMAX = null; // Niveau de stock MAX

            #[ORM\Column(type: "integer", nullable: true)]
            private ?int $niveauStock = null; // Niveau de stock actuel


        //Portes
            #[ORM\Column(length: 255, nullable: true)]
            private ?string $etatPorte = null; // État de la porte (ouverte, fermée, verrouillée)


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $connectivite = null; // Connectivité (Wi-Fi, Bluetooth, Zigbee, etc.)

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $etatBatterie = null; // État de la batterie (en pourcentage, si applicable)

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $derniereInteraction = null; // Dernière interaction avec l'objet

    #[ORM\Column(length: 255)]
    private ?string $marque = null; // Marque de l'objet connecté

    #[ORM\Column(length: 255)]
    private ?string $etat = null; // État de l'objet (Connecté/Déconnecté)

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $consommationEnergetique = null; // Consommation énergétique de l'objet en kWh 


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mode = null; // Mode de fonctionnement (ex. : automatique, éco)

    // Getters and Setters

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

    public function getNiveauStock(): ?int
    {
        return $this->niveauStock;
    }

    public function setNiveauStock(?int $niveauStock): static
    {
        $this->niveauStock = $niveauStock;
        return $this;
    }

    public function getNiveauStockMAX(): ?int
    {
        return $this->niveauStockMAX;
    }

    public function setNiveauStockMAX(?int $niveauStockMAX): static
    {
        $this->niveauStock = $niveauStockMAX;
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

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): static
    {
        $this->mode = $mode;
        return $this;
    }
}
