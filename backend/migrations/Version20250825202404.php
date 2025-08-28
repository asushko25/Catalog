<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825202404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, external_id, name, category, price_gross, currency, created_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, external_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, price_gross NUMERIC(10, 2) NOT NULL, currency VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO product (id, external_id, name, category, price_gross, currency, created_at) SELECT id, external_id, name, category, price_gross, currency, created_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD9F75D7B0 ON product (external_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, external_id, name, category, price_gross, currency, created_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, external_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, price_gross NUMERIC(10, 2) NOT NULL, currency VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , yes VARCHAR(255) NOT NULL, product VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO product (id, external_id, name, category, price_gross, currency, created_at) SELECT id, external_id, name, category, price_gross, currency, created_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }
}
