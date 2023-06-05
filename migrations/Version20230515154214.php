<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515154214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE encuesta ADD respuestas LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE falla CHANGE imagen_portada imagen_portada VARCHAR(255) NOT NULL, CHANGE logo logo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE noticia CHANGE imagen imagen VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE foto foto VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE encuesta DROP respuestas');
        $this->addSql('ALTER TABLE falla CHANGE imagen_portada imagen_portada LONGBLOB DEFAULT NULL, CHANGE logo logo LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE noticia CHANGE imagen imagen LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE foto foto LONGBLOB DEFAULT NULL');
    }
}
