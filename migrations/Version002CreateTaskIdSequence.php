<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version002CreateTaskIdSequence extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS task_id_seq START 1 INCREMENT 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE IF EXISTS task_id_seq');
    }
}
