<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526205930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B05AAAC586');
        $this->addSql('DROP INDEX UNIQ_47860B05AAAC586 ON evento');
        $this->addSql('ALTER TABLE evento CHANGE fecha_caducidad fecha_caducidad VARCHAR(255) NOT NULL, CHANGE pagos_id pago INT DEFAULT NULL');
        $this->addSql('ALTER TABLE falla ADD email VARCHAR(100) NOT NULL, ADD telefono INT NOT NULL, ADD sitio_web VARCHAR(100) NOT NULL, CHANGE imagen_portada imagen_portada VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento CHANGE fecha_caducidad fecha_caducidad DATETIME DEFAULT NULL, CHANGE pago pagos_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B05AAAC586 FOREIGN KEY (pagos_id) REFERENCES pago (id_pago)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47860B05AAAC586 ON evento (pagos_id)');
        $this->addSql('ALTER TABLE falla DROP email, DROP telefono, DROP sitio_web, CHANGE imagen_portada imagen_portada VARCHAR(255) NOT NULL, CHANGE logo logo VARCHAR(255) NOT NULL');
    }
}
