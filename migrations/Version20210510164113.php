<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510164113 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE imput (id INT AUTO_INCREMENT NOT NULL, tache_id INT DEFAULT NULL, user_id INT DEFAULT NULL, valeur DOUBLE PRECISION DEFAULT NULL, date DATE NOT NULL, INDEX IDX_CA9D9D39D2235D39 (tache_id), INDEX IDX_CA9D9D39A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE imput ADD CONSTRAINT FK_CA9D9D39D2235D39 FOREIGN KEY (tache_id) REFERENCES taches (id)');
        $this->addSql('ALTER TABLE imput ADD CONSTRAINT FK_CA9D9D39A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE imput');
    }
}
