<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171221133627 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE runs (id INTEGER NOT NULL, user_id INTEGER DEFAULT NULL, date TIME NOT NULL, distance DOUBLE PRECISION NOT NULL, time TIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_803A7B1FA76ED395 ON runs (user_id)');
        $this->addSql('DROP INDEX UNIQ_C2502824E7927C74');
        $this->addSql('DROP INDEX UNIQ_C2502824F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__app_users AS SELECT id, username, password, email, is_active FROM app_users');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('CREATE TABLE app_users (id INTEGER NOT NULL, username VARCHAR(25) NOT NULL COLLATE BINARY, password VARCHAR(64) NOT NULL COLLATE BINARY, email VARCHAR(60) NOT NULL COLLATE BINARY, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO app_users (id, username, password, email, is_active) SELECT id, username, password, email, is_active FROM __temp__app_users');
        $this->addSql('DROP TABLE __temp__app_users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2502824E7927C74 ON app_users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2502824F85E0677 ON app_users (username)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE runs');
        $this->addSql('ALTER TABLE app_users ADD COLUMN run_array CLOB NOT NULL COLLATE BINARY');
    }
}
