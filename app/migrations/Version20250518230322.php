<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250518230322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE album CHANGE music_brainz_id music_brainz_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band CHANGE music_brainz_id music_brainz_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician CHANGE music_brainz_id music_brainz_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating DROP FOREIGN KEY FK_D88926229D86650F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D88926229D86650F ON rating
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating CHANGE user_id_id user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D8892622A76ED395 ON rating (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song ADD music_brainz_id VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_33EDEEA1119931F5 ON song (music_brainz_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE album CHANGE music_brainz_id music_brainz_id VARCHAR(36) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_48DFA2EB119931F5 ON band
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band CHANGE music_brainz_id music_brainz_id VARCHAR(36) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician CHANGE music_brainz_id music_brainz_id VARCHAR(36) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D8892622A76ED395 ON rating
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating CHANGE user_id user_id_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating ADD CONSTRAINT FK_D88926229D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D88926229D86650F ON rating (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_33EDEEA1119931F5 ON song
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song DROP music_brainz_id
        SQL);
    }
}
