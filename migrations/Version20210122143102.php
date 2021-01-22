<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210122143102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE liste ADD CONSTRAINT FK_FCF22AF47E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FCF22AF47E3C61F9 ON liste (owner_id)');
        $this->addSql('ALTER TABLE objet ADD reserved_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C38BCDB4AF4 FOREIGN KEY (reserved_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_46CD4C38BCDB4AF4 ON objet (reserved_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste DROP FOREIGN KEY FK_FCF22AF47E3C61F9');
        $this->addSql('DROP INDEX IDX_FCF22AF47E3C61F9 ON liste');
        $this->addSql('ALTER TABLE liste DROP owner_id');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C38BCDB4AF4');
        $this->addSql('DROP INDEX IDX_46CD4C38BCDB4AF4 ON objet');
        $this->addSql('ALTER TABLE objet DROP reserved_by_id');
    }
}
