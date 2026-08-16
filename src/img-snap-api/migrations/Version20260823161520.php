<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260823161520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, slug, upstream_url, cached_at FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, upstream_url VARCHAR(255) NOT NULL, cached_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO image (id, slug, upstream_url, cached_at) SELECT id, slug, upstream_url, cached_at FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, slug, upstream_url, cached_at FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, upstream_url VARCHAR(255) NOT NULL, cached_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO image (id, slug, upstream_url, cached_at) SELECT id, slug, upstream_url, cached_at FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
    }
}
