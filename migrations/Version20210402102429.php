<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402102429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE route (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, airline VARCHAR(3) NOT NULL, airline_id INTEGER NOT NULL, source_airport VARCHAR(4) NOT NULL, source_airport_id INTEGER NOT NULL, destination_airport VARCHAR(4) NOT NULL, destination_airport_id INTEGER NOT NULL, codeshare VARCHAR(1) DEFAULT NULL, stops INTEGER NOT NULL, equipment VARCHAR(30) NOT NULL, price NUMERIC(11, 2) NOT NULL)');
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
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP INDEX IDX_9474526C8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, city_id, text FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER NOT NULL, text CLOB NOT NULL)');
        $this->addSql('INSERT INTO comment (id, city_id, text) SELECT id, city_id, text FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C8BAC62AF ON comment (city_id)');
    }
}
