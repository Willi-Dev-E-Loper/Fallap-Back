<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527100326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento DROP participantes');
        $this->addSql('ALTER TABLE usuario ADD evento_id INT DEFAULT NULL, ADD pago_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id_evento)');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D63FB8380 FOREIGN KEY (pago_id) REFERENCES evento (id_evento)');
        $this->addSql('CREATE INDEX IDX_2265B05D87A5F842 ON usuario (evento_id)');
        $this->addSql('CREATE INDEX IDX_2265B05D63FB8380 ON usuario (pago_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento ADD participantes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05D87A5F842');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05D63FB8380');
        $this->addSql('DROP INDEX IDX_2265B05D87A5F842 ON usuario');
        $this->addSql('DROP INDEX IDX_2265B05D63FB8380 ON usuario');
        $this->addSql('ALTER TABLE usuario DROP evento_id, DROP pago_id');
    }
}
