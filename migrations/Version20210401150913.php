<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401150913 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, firstname, lastname, username, password, salt, role FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL COLLATE BINARY, lastname VARCHAR(255) NOT NULL COLLATE BINARY, username VARCHAR(50) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, salt VARCHAR(32) NOT NULL COLLATE BINARY, role INTEGER NOT NULL)');
        $this->addSql('INSERT INTO client (id, firstname, lastname, username, password, salt, role) SELECT id, firstname, lastname, username, password, salt, role FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455F85E0677 ON client (username)');
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
        $this->addSql('DROP INDEX UNIQ_C7440455F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, firstname, lastname, username, password, salt, role FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(32) NOT NULL, role INTEGER NOT NULL)');
        $this->addSql('INSERT INTO client (id, firstname, lastname, username, password, salt, role) SELECT id, firstname, lastname, username, password, salt, role FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('DROP INDEX IDX_9474526C8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, city_id, text FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER NOT NULL, text CLOB NOT NULL)');
        $this->addSql('INSERT INTO comment (id, city_id, text) SELECT id, city_id, text FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C8BAC62AF ON comment (city_id)');
    }
}
