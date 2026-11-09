<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Migrations;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261109100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Normalize content version identifiers in section references';
    }

    public function up(Schema $schema): void
    {
        $this->changeContentVersionColumn($schema, 'VARCHAR(36)');
    }

    public function down(Schema $schema): void
    {
        $this->changeContentVersionColumn($schema, 'CHAR(36)');
    }

    private function changeContentVersionColumn(Schema $schema, string $type): void
    {
        $platform = $this->connection->getDatabasePlatform();
        $table = $schema->getTable('cms_content_version_sections');
        if ($table->hasForeignKey('FK_4491D9C7D28591F7')) {
            $this->addSql($platform->getDropForeignKeySQL('FK_4491D9C7D28591F7', 'cms_content_version_sections'));
        }

        if ($platform instanceof PostgreSQLPlatform) {
            $this->addSql(sprintf('ALTER TABLE cms_content_version_sections ALTER COLUMN content_version_id TYPE %s', $type));
        } else {
            $this->addSql(sprintf('ALTER TABLE cms_content_version_sections CHANGE content_version_id content_version_id %s NOT NULL', $type));
        }

        $this->addSql('ALTER TABLE cms_content_version_sections ADD CONSTRAINT FK_4491D9C7D28591F7 FOREIGN KEY (content_version_id) REFERENCES cms_content_version (id) ON DELETE CASCADE');
    }
}
