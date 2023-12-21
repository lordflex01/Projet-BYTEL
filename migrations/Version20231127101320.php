<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231127101320 extends AbstractMigration{
    public function up(Schema $schema): void {
        $this->addSql("ALTER TABLE user ADD taux DOUBLE PRECISION DEFAULT NULL ");
        $this->addSql("ALTER TABLE taches ADD statut TINYINT(1) NOT NULL");
        $this->addSql("UPDATE taches SET statut=1");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE `user` DROP taux');
        $this->addSql('ALTER TABLE `taches` DROP statut');
    }
}