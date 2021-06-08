<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608133207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE date_v ADD codeprojet_id INT DEFAULT NULL, ADD activite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE date_v ADD CONSTRAINT FK_A8F11980D780BF39 FOREIGN KEY (codeprojet_id) REFERENCES code_projet (id)');
        $this->addSql('ALTER TABLE date_v ADD CONSTRAINT FK_A8F119809B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('CREATE INDEX IDX_A8F11980D780BF39 ON date_v (codeprojet_id)');
        $this->addSql('CREATE INDEX IDX_A8F119809B0F88B1 ON date_v (activite_id)');
        $this->addSql('ALTER TABLE taches DROP FOREIGN KEY FK_3BF2CD98D780BF39');
        $this->addSql('DROP INDEX IDX_3BF2CD98D780BF39 ON taches');
        $this->addSql('ALTER TABLE taches DROP codeprojet_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date_v DROP FOREIGN KEY FK_A8F119809B0F88B1');
        $this->addSql('DROP TABLE activite');
        $this->addSql('ALTER TABLE date_v DROP FOREIGN KEY FK_A8F11980D780BF39');
        $this->addSql('DROP INDEX IDX_A8F11980D780BF39 ON date_v');
        $this->addSql('DROP INDEX IDX_A8F119809B0F88B1 ON date_v');
        $this->addSql('ALTER TABLE date_v DROP codeprojet_id, DROP activite_id');
        $this->addSql('ALTER TABLE taches ADD codeprojet_id INT NOT NULL');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD98D780BF39 FOREIGN KEY (codeprojet_id) REFERENCES code_projet (id)');
        $this->addSql('CREATE INDEX IDX_3BF2CD98D780BF39 ON taches (codeprojet_id)');
    }
}
