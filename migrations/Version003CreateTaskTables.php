<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version003CreateTaskTables extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE event_store (
            id SERIAL PRIMARY KEY,
            aggregate_id INTEGER NOT NULL,
            aggregate_type VARCHAR(255) NOT NULL,
            event_type VARCHAR(255) NOT NULL,
            event_data JSON NOT NULL,
            occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            version INTEGER NOT NULL
        )');
        $this->addSql('CREATE INDEX event_store_aggregate_version_idx ON event_store (aggregate_type, aggregate_id, version)');

        $this->addSql('CREATE TABLE tasks (
            id INTEGER PRIMARY KEY,
            user_id INTEGER NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            status VARCHAR(50) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
        $this->addSql('CREATE INDEX idx_tasks_user_id ON tasks (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IF EXISTS idx_tasks_user_id');
        $this->addSql('DROP TABLE IF EXISTS tasks');
        $this->addSql('DROP INDEX IF EXISTS event_store_aggregate_version_idx');
        $this->addSql('DROP TABLE IF EXISTS event_store');
    }
}
