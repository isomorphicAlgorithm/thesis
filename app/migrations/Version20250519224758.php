<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519224758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_39986E43119931F5 ON album
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album CHANGE music_brainz_id spotify_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_39986E43A905FC5C ON album (spotify_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band ADD spotify_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_48DFA2EBA905FC5C ON band (spotify_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician ADD spotify_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_31714127A905FC5C ON musician (spotify_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_33EDEEA1119931F5 ON song
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song CHANGE music_brainz_id spotify_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_33EDEEA1A905FC5C ON song (spotify_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_39986E43A905FC5C ON album
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album CHANGE spotify_id music_brainz_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_39986E43119931F5 ON album (music_brainz_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_48DFA2EBA905FC5C ON band
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band DROP spotify_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_31714127A905FC5C ON musician
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician DROP spotify_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_33EDEEA1A905FC5C ON song
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song CHANGE spotify_id music_brainz_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_33EDEEA1119931F5 ON song (music_brainz_id)
        SQL);
    }
}
