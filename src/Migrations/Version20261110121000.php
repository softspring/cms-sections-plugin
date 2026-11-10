<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Migrations;

use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261110121000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fence invalidated sections and enforce one compiled section per version/context';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM cms_compiled_data WHERE section_version_id IS NOT NULL');
        $this->addSql("ALTER TABLE cms_section_version ADD compiled_generation VARCHAR(36) DEFAULT '' NOT NULL");
        $this->addSql('CREATE UNIQUE INDEX uniq_cms_section_compiled_key ON cms_compiled_data (section_version_id, compiled_key, generation)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_cms_section_compiled_key'.($this->connection->getDatabasePlatform() instanceof AbstractMySQLPlatform ? ' ON cms_compiled_data' : ''));
        $this->addSql('ALTER TABLE cms_section_version DROP COLUMN compiled_generation');
    }
}
