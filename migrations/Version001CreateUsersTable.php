<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version001CreateUsersTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
            id INTEGER PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            phone VARCHAR(50),
            website VARCHAR(255)
        )');
        $this->addSql('CREATE INDEX idx_users_email ON users(email)');
        $this->addSql('CREATE INDEX idx_users_username ON users(username)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IF EXISTS idx_users_username');
        $this->addSql('DROP INDEX IF EXISTS idx_users_email');
        $this->addSql('DROP TABLE users');
    }
}
