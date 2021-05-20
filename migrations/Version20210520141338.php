<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520141338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_projet DROP FOREIGN KEY FK_2F93C40BC18272');
        $this->addSql('DROP INDEX IDX_2F93C40BC18272 ON code_projet');
        $this->addSql('ALTER TABLE code_projet ADD budget_nrj DOUBLE PRECISION DEFAULT NULL, ADD budget_deco DOUBLE PRECISION DEFAULT NULL, ADD chage_jh DOUBLE PRECISION DEFAULT NULL, ADD chage_nrj DOUBLE PRECISION DEFAULT NULL, ADD chage_deco DOUBLE PRECISION DEFAULT NULL, DROP projet_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_projet ADD projet_id INT NOT NULL, DROP budget_nrj, DROP budget_deco, DROP chage_jh, DROP chage_nrj, DROP chage_deco');
        $this->addSql('ALTER TABLE code_projet ADD CONSTRAINT FK_2F93C40BC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('CREATE INDEX IDX_2F93C40BC18272 ON code_projet (projet_id)');
    }
}
