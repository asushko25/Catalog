<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250828113514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__discount_rule AS SELECT id, type, value FROM discount_rule');
        $this->addSql('DROP TABLE discount_rule');
        $this->addSql('CREATE TABLE discount_rule (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(32) NOT NULL, value NUMERIC(10, 2) NOT NULL)');
        $this->addSql('INSERT INTO discount_rule (id, type, value) SELECT id, type, value FROM __temp__discount_rule');
        $this->addSql('DROP TABLE __temp__discount_rule');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__discount_rule AS SELECT id, type, value FROM discount_rule');
        $this->addSql('DROP TABLE discount_rule');
        $this->addSql('CREATE TABLE discount_rule (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(20) NOT NULL, value INTEGER NOT NULL)');
        $this->addSql('INSERT INTO discount_rule (id, type, value) SELECT id, type, value FROM __temp__discount_rule');
        $this->addSql('DROP TABLE __temp__discount_rule');
    }
}
