<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415224605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(78) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_pers (id INT AUTO_INCREMENT NOT NULL, personne_id_id INT NOT NULL, group_group_id_id INT NOT NULL, INDEX IDX_F93816C96BA58F3E (personne_id_id), INDEX IDX_F93816C9AD0AAF1 (group_group_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_pers ADD CONSTRAINT FK_F93816C96BA58F3E FOREIGN KEY (personne_id_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE group_pers ADD CONSTRAINT FK_F93816C9AD0AAF1 FOREIGN KEY (group_group_id_id) REFERENCES group_group (id)');
        $this->addSql('ALTER TABLE reception DROP FOREIGN KEY FK_50D6852FB5BE640A');
        $this->addSql('DROP INDEX IDX_50D6852FB5BE640A ON reception');
        $this->addSql('ALTER TABLE reception CHANGE destinataire_s_id group_group_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE reception ADD CONSTRAINT FK_50D6852FAD0AAF1 FOREIGN KEY (group_group_id_id) REFERENCES group_group (id)');
        $this->addSql('CREATE INDEX IDX_50D6852FAD0AAF1 ON reception (group_group_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_pers DROP FOREIGN KEY FK_F93816C9AD0AAF1');
        $this->addSql('ALTER TABLE reception DROP FOREIGN KEY FK_50D6852FAD0AAF1');
        $this->addSql('DROP TABLE group_group');
        $this->addSql('DROP TABLE group_pers');
        $this->addSql('DROP INDEX IDX_50D6852FAD0AAF1 ON reception');
        $this->addSql('ALTER TABLE reception CHANGE group_group_id_id destinataire_s_id INT NOT NULL');
        $this->addSql('ALTER TABLE reception ADD CONSTRAINT FK_50D6852FB5BE640A FOREIGN KEY (destinataire_s_id) REFERENCES personne (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_50D6852FB5BE640A ON reception (destinataire_s_id)');
    }
}
