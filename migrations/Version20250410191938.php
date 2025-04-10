<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410191938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE objets_connectes ADD angle_vision INT DEFAULT NULL, ADD nombre_copies_par_jour INT DEFAULT NULL, CHANGE mode resolution_camera VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT 'public/icones/black/profil.png'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objets_connectes DROP angle_vision, DROP nombre_copies_par_jour, CHANGE resolution_camera mode VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
