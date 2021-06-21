<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621104406 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches ADD code_projet_id INT DEFAULT NULL, ADD domaine VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD98AC84FA4A FOREIGN KEY (code_projet_id) REFERENCES code_projet (id)');
        $this->addSql('CREATE INDEX IDX_3BF2CD98AC84FA4A ON taches (code_projet_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches DROP FOREIGN KEY FK_3BF2CD98AC84FA4A');
        $this->addSql('DROP INDEX IDX_3BF2CD98AC84FA4A ON taches');
        $this->addSql('ALTER TABLE taches DROP code_projet_id, DROP domaine');
    }
}
