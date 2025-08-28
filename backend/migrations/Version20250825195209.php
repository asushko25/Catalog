<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825195209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discount_rule (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(20) NOT NULL, value INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, external_id VARCHAR(255) DEFAULT NULL, yes VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, price_gross NUMERIC(10, 2) NOT NULL, currency VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , product VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE discount_rule');
        $this->addSql('DROP TABLE product');
    }
}
