<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512155244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE date_v (id INT AUTO_INCREMENT NOT NULL, imput_id INT DEFAULT NULL, date DATE DEFAULT NULL, valeur DOUBLE PRECISION DEFAULT NULL, INDEX IDX_A8F119807AA004B (imput_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE date_v ADD CONSTRAINT FK_A8F119807AA004B FOREIGN KEY (imput_id) REFERENCES imput (id)');
        $this->addSql('ALTER TABLE imput DROP valeur, DROP date');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE date_v');
        $this->addSql('ALTER TABLE imput ADD valeur DOUBLE PRECISION DEFAULT NULL, ADD date DATE NOT NULL');
    }
}
