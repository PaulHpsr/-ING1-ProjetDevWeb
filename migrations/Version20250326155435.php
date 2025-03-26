<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326155435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE objets_connectes (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, temperature_actuelle DOUBLE PRECISION DEFAULT NULL, temperature_cible DOUBLE PRECISION DEFAULT NULL, niveau_stock_max INT DEFAULT NULL, niveau_stock INT DEFAULT NULL, etat_porte VARCHAR(255) DEFAULT NULL, connectivite VARCHAR(255) DEFAULT NULL, etat_batterie INT DEFAULT NULL, derniere_interaction DATETIME DEFAULT NULL, marque VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, consommation_energetique DOUBLE PRECISION DEFAULT NULL, mode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE objets_connectes');
    }
}
