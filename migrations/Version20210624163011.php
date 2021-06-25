<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624163011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_projet ADD budget_consomme DOUBLE PRECISION DEFAULT NULL, ADD budget_nrjconsomme DOUBLE PRECISION DEFAULT NULL, ADD budget_decoconsomme DOUBLE PRECISION DEFAULT NULL, ADD budget_cloeconsomme DOUBLE PRECISION DEFAULT NULL, ADD charge_consomme DOUBLE PRECISION DEFAULT NULL, ADD charge_nrjconsomme DOUBLE PRECISION DEFAULT NULL, ADD charge_decoconsomme DOUBLE PRECISION DEFAULT NULL, ADD charge_cloeconsomme DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD capit VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_projet DROP budget_consomme, DROP budget_nrjconsomme, DROP budget_decoconsomme, DROP budget_cloeconsomme, DROP charge_consomme, DROP charge_nrjconsomme, DROP charge_decoconsomme, DROP charge_cloeconsomme');
        $this->addSql('ALTER TABLE `user` DROP capit');
    }
}
