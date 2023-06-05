<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525190447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento CHANGE fecha_caducidad fecha_caducidad DATETIME DEFAULT NULL, CHANGE participantes participantes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE tiene_pago tiene_pago TINYINT(1) DEFAULT NULL, CHANGE contador contador INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento CHANGE fecha_caducidad fecha_caducidad DATETIME NOT NULL, CHANGE participantes participantes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE tiene_pago tiene_pago TINYINT(1) NOT NULL, CHANGE contador contador INT NOT NULL');
    }
}
