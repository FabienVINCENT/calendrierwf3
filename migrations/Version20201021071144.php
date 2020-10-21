<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201021071144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animer (id INT AUTO_INCREMENT NOT NULL, fk_animer_formation_id INT NOT NULL, fk_animer_user_id INT NOT NULL, date DATE NOT NULL, type_journee SMALLINT NOT NULL, INDEX IDX_F4C8DB78112A9EFF (fk_animer_formation_id), INDEX IDX_F4C8DB7830566CDF (fk_animer_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE endroit (id INT AUTO_INCREMENT NOT NULL, ville VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_7B44825A43C3D9C3 (ville), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formations (id INT AUTO_INCREMENT NOT NULL, localisation_id INT NOT NULL, nom VARCHAR(60) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, UNIQUE INDEX UNIQ_409021376C6E55B5 (nom), INDEX IDX_40902137C68BE09C (localisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, phone_number VARCHAR(17) NOT NULL, pseudo VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_competence (user_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_33B3AE93A76ED395 (user_id), INDEX IDX_33B3AE9315761DAB (competence_id), PRIMARY KEY(user_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animer ADD CONSTRAINT FK_F4C8DB78112A9EFF FOREIGN KEY (fk_animer_formation_id) REFERENCES formations (id)');
        $this->addSql('ALTER TABLE animer ADD CONSTRAINT FK_F4C8DB7830566CDF FOREIGN KEY (fk_animer_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE formations ADD CONSTRAINT FK_40902137C68BE09C FOREIGN KEY (localisation_id) REFERENCES endroit (id)');
        $this->addSql('ALTER TABLE user_competence ADD CONSTRAINT FK_33B3AE93A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_competence ADD CONSTRAINT FK_33B3AE9315761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_competence DROP FOREIGN KEY FK_33B3AE9315761DAB');
        $this->addSql('ALTER TABLE formations DROP FOREIGN KEY FK_40902137C68BE09C');
        $this->addSql('ALTER TABLE animer DROP FOREIGN KEY FK_F4C8DB78112A9EFF');
        $this->addSql('ALTER TABLE animer DROP FOREIGN KEY FK_F4C8DB7830566CDF');
        $this->addSql('ALTER TABLE user_competence DROP FOREIGN KEY FK_33B3AE93A76ED395');
        $this->addSql('DROP TABLE animer');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE endroit');
        $this->addSql('DROP TABLE formations');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_competence');
    }
}
