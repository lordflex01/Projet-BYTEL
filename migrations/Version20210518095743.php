<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518095743 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date_v ADD tache_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE date_v ADD CONSTRAINT FK_A8F11980D2235D39 FOREIGN KEY (tache_id) REFERENCES taches (id)');
        $this->addSql('CREATE INDEX IDX_A8F11980D2235D39 ON date_v (tache_id)');
        $this->addSql('ALTER TABLE imput DROP FOREIGN KEY FK_CA9D9D39D2235D39');
        $this->addSql('DROP INDEX IDX_CA9D9D39D2235D39 ON imput');
        $this->addSql('ALTER TABLE imput DROP tache_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date_v DROP FOREIGN KEY FK_A8F11980D2235D39');
        $this->addSql('DROP INDEX IDX_A8F11980D2235D39 ON date_v');
        $this->addSql('ALTER TABLE date_v DROP tache_id');
        $this->addSql('ALTER TABLE imput ADD tache_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE imput ADD CONSTRAINT FK_CA9D9D39D2235D39 FOREIGN KEY (tache_id) REFERENCES taches (id)');
        $this->addSql('CREATE INDEX IDX_CA9D9D39D2235D39 ON imput (tache_id)');
    }
}
