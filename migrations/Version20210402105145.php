<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402105145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_7E91F7C2BD6D436E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__airport AS SELECT id, city_name, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source FROM airport');
        $this->addSql('DROP TABLE airport');
        $this->addSql('CREATE TABLE airport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city VARCHAR(255) DEFAULT NULL, airport_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, country VARCHAR(255) NOT NULL COLLATE BINARY, iata VARCHAR(3) DEFAULT NULL COLLATE BINARY, icao VARCHAR(4) DEFAULT NULL COLLATE BINARY, latitude NUMERIC(9, 6) NOT NULL, longitude NUMERIC(9, 6) NOT NULL, altitude INTEGER NOT NULL, timezone INTEGER NOT NULL, dst VARCHAR(1) NOT NULL COLLATE BINARY, tz VARCHAR(50) NOT NULL COLLATE BINARY, type VARCHAR(25) NOT NULL COLLATE BINARY, source VARCHAR(25) NOT NULL COLLATE BINARY, CONSTRAINT FK_7E91F7C22D5B0234 FOREIGN KEY (city) REFERENCES city (name) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO airport (id, city, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source) SELECT id, city_name, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source FROM __temp__airport');
        $this->addSql('DROP TABLE __temp__airport');
        $this->addSql('CREATE INDEX IDX_7E91F7C22D5B0234 ON airport (city)');
        $this->addSql('DROP INDEX IDX_9474526C8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, city_id, text FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER NOT NULL, text CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_9474526C8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, city_id, text) SELECT id, city_id, text FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C8BAC62AF ON comment (city_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_7E91F7C22D5B0234');
        $this->addSql('CREATE TEMPORARY TABLE __temp__airport AS SELECT id, city, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source FROM airport');
        $this->addSql('DROP TABLE airport');
        $this->addSql('CREATE TABLE airport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, airport_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, iata VARCHAR(3) DEFAULT NULL, icao VARCHAR(4) DEFAULT NULL, latitude NUMERIC(9, 6) NOT NULL, longitude NUMERIC(9, 6) NOT NULL, altitude INTEGER NOT NULL, timezone INTEGER NOT NULL, dst VARCHAR(1) NOT NULL, tz VARCHAR(50) NOT NULL, type VARCHAR(25) NOT NULL, source VARCHAR(25) NOT NULL, city_name VARCHAR(255) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO airport (id, city_name, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source) SELECT id, city, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source FROM __temp__airport');
        $this->addSql('DROP TABLE __temp__airport');
        $this->addSql('CREATE INDEX IDX_7E91F7C2BD6D436E ON airport (city_name)');
        $this->addSql('DROP INDEX IDX_9474526C8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, city_id, text FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER NOT NULL, text CLOB NOT NULL)');
        $this->addSql('INSERT INTO comment (id, city_id, text) SELECT id, city_id, text FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C8BAC62AF ON comment (city_id)');
    }
}
