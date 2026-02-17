<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version004CreateMessengerTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE messenger_messages (
            id BIGSERIAL PRIMARY KEY,
            body TEXT NOT NULL,
            headers TEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        )');
        $this->addSql('CREATE INDEX idx_messenger_messages_queue_name ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX idx_messenger_messages_available_at ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX idx_messenger_messages_delivered_at ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IF EXISTS idx_messenger_messages_delivered_at');
        $this->addSql('DROP INDEX IF EXISTS idx_messenger_messages_available_at');
        $this->addSql('DROP INDEX IF EXISTS idx_messenger_messages_queue_name');
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
    }
}
