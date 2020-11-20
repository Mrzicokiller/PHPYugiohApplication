<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201120184053 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card ADD attribute_id INT DEFAULT NULL, DROP attribute');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3B6E62EFA FOREIGN KEY (attribute_id) REFERENCES card_attribute (id)');
        $this->addSql('CREATE INDEX IDX_161498D3B6E62EFA ON card (attribute_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3B6E62EFA');
        $this->addSql('DROP INDEX IDX_161498D3B6E62EFA ON card');
        $this->addSql('ALTER TABLE card ADD attribute VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP attribute_id');
    }
}
