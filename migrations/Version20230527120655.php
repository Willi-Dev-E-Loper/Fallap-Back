<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527120655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE encuesta CHANGE fecha_creacion fecha_creacion VARCHAR(255) NOT NULL, CHANGE fecha_caducidad fecha_caducidad VARCHAR(255) NOT NULL, CHANGE contador contador INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE encuesta CHANGE fecha_creacion fecha_creacion DATETIME NOT NULL, CHANGE fecha_caducidad fecha_caducidad DATETIME NOT NULL, CHANGE contador contador INT NOT NULL');
    }
}
