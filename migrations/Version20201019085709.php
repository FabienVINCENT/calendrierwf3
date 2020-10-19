<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201019085709 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animer (id INT AUTO_INCREMENT NOT NULL, fk_animer_formation_id INT NOT NULL, fk_animer_user_id INT DEFAULT NULL, date DATE NOT NULL, demi_journee TINYINT(1) NOT NULL, INDEX IDX_F4C8DB78112A9EFF (fk_animer_formation_id), INDEX IDX_F4C8DB7830566CDF (fk_animer_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animer ADD CONSTRAINT FK_F4C8DB78112A9EFF FOREIGN KEY (fk_animer_formation_id) REFERENCES formations (id)');
        $this->addSql('ALTER TABLE animer ADD CONSTRAINT FK_F4C8DB7830566CDF FOREIGN KEY (fk_animer_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE animer');
    }
}
