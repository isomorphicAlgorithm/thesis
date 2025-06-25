<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616023005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE song_favorite DROP FOREIGN KEY FK_C319D891A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_favorite DROP FOREIGN KEY FK_C319D891AA17481D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_favorite
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite ADD song_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_68C58ED9A0BDB2F3 ON favorite (song_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE song_favorite (song_id INT NOT NULL, favorite_id INT NOT NULL, INDEX IDX_C319D891A0BDB2F3 (song_id), INDEX IDX_C319D891AA17481D (favorite_id), PRIMARY KEY(song_id, favorite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_favorite ADD CONSTRAINT FK_C319D891A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_favorite ADD CONSTRAINT FK_C319D891AA17481D FOREIGN KEY (favorite_id) REFERENCES favorite (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_68C58ED9A0BDB2F3 ON favorite
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite DROP song_id
        SQL);
    }
}
