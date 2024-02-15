<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212152325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE kplocal_actors (id INT AUTO_INCREMENT NOT NULL, film_id INT NOT NULL, name VARCHAR(255) NOT NULL, en_name VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kplocal_films (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, short_description LONGTEXT NOT NULL, countries VARCHAR(255) DEFAULT NULL, kinopoisk_rating DOUBLE PRECISION NOT NULL, imdb_rating DOUBLE PRECISION NOT NULL, duration INT NOT NULL, is_season TINYINT(1) NOT NULL, name_orig VARCHAR(255) DEFAULT NULL, parent_control VARCHAR(255) NOT NULL, director VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE kplocal_actors');
        $this->addSql('DROP TABLE kplocal_films');
    }
}
