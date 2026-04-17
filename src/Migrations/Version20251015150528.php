<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\Migrations\AbstractMigration;

final class Version20251015150528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add notes field to cms_section table';
    }

    public function up(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE cms_section ADD notes TEXT DEFAULT NULL');

            return;
        }

        $this->addSql('ALTER TABLE cms_section ADD notes LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cms_section DROP notes');
    }
}
