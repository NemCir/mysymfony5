<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210406100150 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX airport_id_index');
        $this->addSql('CREATE TEMPORARY TABLE __temp__airport AS SELECT id, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source, city FROM airport');
        $this->addSql('DROP TABLE airport');
        $this->addSql('CREATE TABLE airport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, airport_id INTEGER NOT NULL, iata VARCHAR(3) DEFAULT NULL COLLATE BINARY, icao VARCHAR(4) DEFAULT NULL COLLATE BINARY, latitude NUMERIC(9, 6) NOT NULL, longitude NUMERIC(9, 6) NOT NULL, altitude INTEGER NOT NULL, timezone INTEGER NOT NULL, dst VARCHAR(1) NOT NULL COLLATE BINARY, tz VARCHAR(50) NOT NULL COLLATE BINARY, type VARCHAR(25) NOT NULL COLLATE BINARY, source VARCHAR(25) NOT NULL COLLATE BINARY, name VARCHAR(100) NOT NULL, country VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO airport (id, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source, city) SELECT id, airport_id, name, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source, city FROM __temp__airport');
        $this->addSql('DROP TABLE __temp__airport');
        $this->addSql('CREATE UNIQUE INDEX airport_id_index ON airport (airport_id)');
        $this->addSql('DROP INDEX city_country_index');
        $this->addSql('CREATE TEMPORARY TABLE __temp__city AS SELECT id, name, country, description FROM city');
        $this->addSql('DROP TABLE city');
        $this->addSql('CREATE TABLE city (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, country VARCHAR(50) NOT NULL, description VARCHAR(500) NOT NULL)');
        $this->addSql('INSERT INTO city (id, name, country, description) SELECT id, name, country, description FROM __temp__city');
        $this->addSql('DROP TABLE __temp__city');
        $this->addSql('CREATE UNIQUE INDEX city_country_index ON city (name, country)');
        $this->addSql('DROP INDEX UNIQ_C7440455F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, firstname, lastname, username, password, salt, role FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, salt VARCHAR(32) NOT NULL COLLATE BINARY, role INTEGER NOT NULL, firstname VARCHAR(60) NOT NULL, lastname VARCHAR(60) NOT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO client (id, firstname, lastname, username, password, salt, role) SELECT id, firstname, lastname, username, password, salt, role FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455F85E0677 ON client (username)');
        $this->addSql('DROP INDEX IDX_9474526CDC2902E0');
        $this->addSql('DROP INDEX IDX_9474526C8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, city_id, client_id_id, text, insert_date, modified_date FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER NOT NULL, client_id_id INTEGER NOT NULL, text CLOB NOT NULL COLLATE BINARY, insert_date DATETIME NOT NULL, modified_date DATETIME DEFAULT NULL, CONSTRAINT FK_9474526C8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526CDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, city_id, client_id_id, text, insert_date, modified_date) SELECT id, city_id, client_id_id, text, insert_date, modified_date FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526CDC2902E0 ON comment (client_id_id)');
        $this->addSql('CREATE INDEX IDX_9474526C8BAC62AF ON comment (city_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX airport_id_index');
        $this->addSql('CREATE TEMPORARY TABLE __temp__airport AS SELECT id, airport_id, name, city, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source FROM airport');
        $this->addSql('DROP TABLE airport');
        $this->addSql('CREATE TABLE airport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, airport_id INTEGER NOT NULL, iata VARCHAR(3) DEFAULT NULL, icao VARCHAR(4) DEFAULT NULL, latitude NUMERIC(9, 6) NOT NULL, longitude NUMERIC(9, 6) NOT NULL, altitude INTEGER NOT NULL, timezone INTEGER NOT NULL, dst VARCHAR(1) NOT NULL, tz VARCHAR(50) NOT NULL, type VARCHAR(25) NOT NULL, source VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, country VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO airport (id, airport_id, name, city, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source) SELECT id, airport_id, name, city, country, iata, icao, latitude, longitude, altitude, timezone, dst, tz, type, source FROM __temp__airport');
        $this->addSql('DROP TABLE __temp__airport');
        $this->addSql('CREATE UNIQUE INDEX airport_id_index ON airport (airport_id)');
        $this->addSql('DROP INDEX city_country_index');
        $this->addSql('CREATE TEMPORARY TABLE __temp__city AS SELECT id, name, country, description FROM city');
        $this->addSql('DROP TABLE city');
        $this->addSql('CREATE TABLE city (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, country VARCHAR(128) NOT NULL COLLATE BINARY, description VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO city (id, name, country, description) SELECT id, name, country, description FROM __temp__city');
        $this->addSql('DROP TABLE __temp__city');
        $this->addSql('CREATE UNIQUE INDEX city_country_index ON city (name, country)');
        $this->addSql('DROP INDEX UNIQ_C7440455F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, firstname, lastname, username, password, salt, role FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, salt VARCHAR(32) NOT NULL, role INTEGER NOT NULL, firstname VARCHAR(255) NOT NULL COLLATE BINARY, lastname VARCHAR(255) NOT NULL COLLATE BINARY, username VARCHAR(50) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO client (id, firstname, lastname, username, password, salt, role) SELECT id, firstname, lastname, username, password, salt, role FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455F85E0677 ON client (username)');
        $this->addSql('DROP INDEX IDX_9474526C8BAC62AF');
        $this->addSql('DROP INDEX IDX_9474526CDC2902E0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, city_id, client_id_id, text, insert_date, modified_date FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER NOT NULL, client_id_id INTEGER NOT NULL, text CLOB NOT NULL, insert_date DATETIME NOT NULL, modified_date DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO comment (id, city_id, client_id_id, text, insert_date, modified_date) SELECT id, city_id, client_id_id, text, insert_date, modified_date FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C8BAC62AF ON comment (city_id)');
        $this->addSql('CREATE INDEX IDX_9474526CDC2902E0 ON comment (client_id_id)');
    }
}
