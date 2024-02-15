<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212161734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kplocal_films ADD poster VARCHAR(255) DEFAULT NULL, ADD year INT NOT NULL, ADD genres JSON NOT NULL, ADD alternative_name VARCHAR(255) DEFAULT NULL, ADD age_rating VARCHAR(255) DEFAULT NULL, ADD series_length INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kplocal_films DROP poster, DROP year, DROP genres, DROP alternative_name, DROP age_rating, DROP series_length');
    }
}
