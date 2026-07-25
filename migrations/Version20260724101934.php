<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260724101934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE producto (id INT AUTO_INCREMENT NOT NULL, categoria_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, precio NUMERIC(10, 2) NOT NULL, stock INT NOT NULL, imagen_url VARCHAR(255) NOT NULL, activo TINYINT(1) NOT NULL, destacado TINYINT(1) NOT NULL, INDEX IDX_A7BB06153397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proveedor (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, telefono VARCHAR(20) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, notas LONGTEXT DEFAULT NULL, activo TINYINT(1) NOT NULL, cif VARCHAR(20) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE producto ADD CONSTRAINT FK_A7BB06153397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE categoria ADD proveedor_id INT DEFAULT NULL, DROP cantidad');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122DCB305D73 FOREIGN KEY (proveedor_id) REFERENCES proveedor (id)');
        $this->addSql('CREATE INDEX IDX_4E10122DCB305D73 ON categoria (proveedor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122DCB305D73');
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB06153397707A');
        $this->addSql('DROP TABLE producto');
        $this->addSql('DROP TABLE proveedor');
        $this->addSql('DROP INDEX IDX_4E10122DCB305D73 ON categoria');
        $this->addSql('ALTER TABLE categoria ADD cantidad INT NOT NULL, DROP proveedor_id');
    }
}
