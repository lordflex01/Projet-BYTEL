<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420153542 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE code_projet (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, statut TINYINT(1) NOT NULL, budget DOUBLE PRECISION DEFAULT NULL, date_d DATE NOT NULL, date_f DATE DEFAULT NULL, INDEX IDX_2F93C40BC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imputation (id INT AUTO_INCREMENT NOT NULL, codeprojet_id INT NOT NULL, user_id INT DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, date_d DATE NOT NULL, date_f DATE DEFAULT NULL, INDEX IDX_AE81A25AD780BF39 (codeprojet_id), INDEX IDX_AE81A25AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, statut TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taches (id INT AUTO_INCREMENT NOT NULL, codeprojet_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_3BF2CD98D780BF39 (codeprojet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE code_projet ADD CONSTRAINT FK_2F93C40BC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE imputation ADD CONSTRAINT FK_AE81A25AD780BF39 FOREIGN KEY (codeprojet_id) REFERENCES code_projet (id)');
        $this->addSql('ALTER TABLE imputation ADD CONSTRAINT FK_AE81A25AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD98D780BF39 FOREIGN KEY (codeprojet_id) REFERENCES code_projet (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE imputation DROP FOREIGN KEY FK_AE81A25AD780BF39');
        $this->addSql('ALTER TABLE taches DROP FOREIGN KEY FK_3BF2CD98D780BF39');
        $this->addSql('ALTER TABLE code_projet DROP FOREIGN KEY FK_2F93C40BC18272');
        $this->addSql('DROP TABLE code_projet');
        $this->addSql('DROP TABLE imputation');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE taches');
    }
}
