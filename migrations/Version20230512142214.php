<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230512142214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comentario (id_comentario INT AUTO_INCREMENT NOT NULL, falla_id INT DEFAULT NULL, contenido VARCHAR(255) NOT NULL, fecha_comentario DATETIME NOT NULL, contador INT NOT NULL, INDEX IDX_4B91E7026709E82D (falla_id), PRIMARY KEY(id_comentario)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE encuesta (id_encuesta INT AUTO_INCREMENT NOT NULL, falla_id INT DEFAULT NULL, titulo VARCHAR(100) NOT NULL, fecha_creacion DATETIME NOT NULL, fecha_caducidad DATETIME NOT NULL, opciones LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', contador INT NOT NULL, INDEX IDX_B25B68416709E82D (falla_id), PRIMARY KEY(id_encuesta)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evento (id_evento INT AUTO_INCREMENT NOT NULL, pagos_id INT DEFAULT NULL, falla_id INT DEFAULT NULL, titulo VARCHAR(100) NOT NULL, contenido LONGTEXT NOT NULL, fecha_creacion DATETIME NOT NULL, fecha_caducidad DATETIME NOT NULL, participantes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', imagen LONGBLOB DEFAULT NULL, tiene_pago TINYINT(1) NOT NULL, contador INT NOT NULL, UNIQUE INDEX UNIQ_47860B05AAAC586 (pagos_id), INDEX IDX_47860B056709E82D (falla_id), PRIMARY KEY(id_evento)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE falla (id_falla INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, direccion VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, cargos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', premios LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', imagen_portada LONGBLOB DEFAULT NULL, logo LONGBLOB DEFAULT NULL, PRIMARY KEY(id_falla)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noticia (id_noticia INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(100) NOT NULL, contenido LONGTEXT NOT NULL, imagen LONGBLOB DEFAULT NULL, PRIMARY KEY(id_noticia)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pago (id_pago INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(100) NOT NULL, fecha_creacion DATETIME NOT NULL, fecha_caducidad DATETIME NOT NULL, cantidad INT NOT NULL, pagadores LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', contador INT NOT NULL, PRIMARY KEY(id_pago)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, falla_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nombre VARCHAR(100) NOT NULL, apellidos VARCHAR(100) NOT NULL, nombre_usuario VARCHAR(100) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, foto LONGBLOB DEFAULT NULL, telefono INT DEFAULT NULL, id_admin INT DEFAULT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), INDEX IDX_2265B05D6709E82D (falla_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7026709E82D FOREIGN KEY (falla_id) REFERENCES falla (id_falla)');
        $this->addSql('ALTER TABLE encuesta ADD CONSTRAINT FK_B25B68416709E82D FOREIGN KEY (falla_id) REFERENCES falla (id_falla)');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B05AAAC586 FOREIGN KEY (pagos_id) REFERENCES pago (id_pago)');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B056709E82D FOREIGN KEY (falla_id) REFERENCES falla (id_falla)');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D6709E82D FOREIGN KEY (falla_id) REFERENCES falla (id_falla)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7026709E82D');
        $this->addSql('ALTER TABLE encuesta DROP FOREIGN KEY FK_B25B68416709E82D');
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B05AAAC586');
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B056709E82D');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05D6709E82D');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE encuesta');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE falla');
        $this->addSql('DROP TABLE noticia');
        $this->addSql('DROP TABLE pago');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
