<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513201231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_48DFA2EB989D9B62 ON band (slug)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician ADD slug VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_31714127989D9B62 ON musician (slug)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song ADD slug VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_33EDEEA1989D9B62 ON song (slug)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_48DFA2EB989D9B62 ON band
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_31714127989D9B62 ON musician
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_33EDEEA1989D9B62 ON song
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song DROP slug
        SQL);
    }
}
