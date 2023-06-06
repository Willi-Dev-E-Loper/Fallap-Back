<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605211315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noticia ADD falla_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE noticia ADD CONSTRAINT FK_31205F966709E82D FOREIGN KEY (falla_id) REFERENCES falla (id_falla)');
        $this->addSql('CREATE INDEX IDX_31205F966709E82D ON noticia (falla_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noticia DROP FOREIGN KEY FK_31205F966709E82D');
        $this->addSql('DROP INDEX IDX_31205F966709E82D ON noticia');
        $this->addSql('ALTER TABLE noticia DROP falla_id');
    }
}
