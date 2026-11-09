<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Migrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\Query\Query;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Softspring\CmsSectionsPlugin\Migrations\Version20261109100000;

class Version20261109100000Test extends TestCase
{
    private const string ADD_FOREIGN_KEY = 'ALTER TABLE cms_content_version_sections ADD CONSTRAINT FK_4491D9C7D28591F7 FOREIGN KEY (content_version_id) REFERENCES cms_content_version (id) ON DELETE CASCADE';

    public function testChangesTheOwnedColumnAndRestoresItsForeignKey(): void
    {
        foreach ([new MySQLPlatform(), new PostgreSQLPlatform()] as $platform) {
            foreach (['up' => 'VARCHAR(36)', 'down' => 'CHAR(36)'] as $direction => $type) {
                $migration = $this->createMigration($platform);
                $migration->$direction($this->createSchema(true));
                $statements = $this->statements($migration);

                self::assertCount(3, $statements);
                $drop = $platform instanceof PostgreSQLPlatform ? 'DROP CONSTRAINT' : 'DROP FOREIGN KEY';
                self::assertSame('ALTER TABLE cms_content_version_sections '.$drop.' FK_4491D9C7D28591F7', $statements[0]);
                $alter = $platform instanceof PostgreSQLPlatform
                    ? 'ALTER COLUMN content_version_id TYPE '.$type
                    : 'CHANGE content_version_id content_version_id '.$type.' NOT NULL';
                self::assertSame('ALTER TABLE cms_content_version_sections '.$alter, $statements[1]);
                self::assertSame(self::ADD_FOREIGN_KEY, $statements[2]);
            }
        }
    }

    public function testResumesWhenTheForeignKeyWasAlreadyDropped(): void
    {
        foreach ([new MySQLPlatform(), new PostgreSQLPlatform()] as $platform) {
            foreach (['up', 'down'] as $direction) {
                $migration = $this->createMigration($platform);
                $migration->$direction($this->createSchema(false));
                $statements = $this->statements($migration);

                self::assertCount(2, $statements);
                self::assertSame(self::ADD_FOREIGN_KEY, $statements[1]);
            }
        }
    }

    private function createSchema(bool $withForeignKey): Schema
    {
        $schema = new Schema();
        $version = $schema->createTable('cms_content_version');
        $version->addColumn('id', 'string', ['length' => 36]);
        $version->setPrimaryKey(['id']);
        $sections = $schema->createTable('cms_content_version_sections');
        $sections->addColumn('content_version_id', 'string', ['length' => 36]);
        if ($withForeignKey) {
            $sections->addForeignKeyConstraint('cms_content_version', ['content_version_id'], ['id'], ['onDelete' => 'CASCADE'], 'FK_4491D9C7D28591F7');
        }

        return $schema;
    }

    private function createMigration(AbstractPlatform $platform): Version20261109100000
    {
        $connection = $this->createStub(Connection::class);
        $connection->method('createSchemaManager')->willReturn($this->createStub(AbstractSchemaManager::class));
        $connection->method('getDatabasePlatform')->willReturn($platform);

        return new Version20261109100000($connection, new NullLogger());
    }

    /** @return list<string> */
    private function statements(Version20261109100000 $migration): array
    {
        return array_map(static fn (Query $query): string => $query->getStatement(), $migration->getSql());
    }
}
