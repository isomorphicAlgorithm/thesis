<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250522021642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE album_rating DROP FOREIGN KEY FK_AF21B951137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_rating DROP FOREIGN KEY FK_AF21B95A32EFC6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_rating
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band CHANGE active_from active_from DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE active_until active_until DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician CHANGE active_from active_from DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE active_until active_until DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating ADD album_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating ADD CONSTRAINT FK_D88926221137ABCF FOREIGN KEY (album_id) REFERENCES album (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D88926221137ABCF ON rating (album_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE album_rating (album_id INT NOT NULL, rating_id INT NOT NULL, INDEX IDX_AF21B951137ABCF (album_id), INDEX IDX_AF21B95A32EFC6 (rating_id), PRIMARY KEY(album_id, rating_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_rating ADD CONSTRAINT FK_AF21B951137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_rating ADD CONSTRAINT FK_AF21B95A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band CHANGE active_from active_from DATE DEFAULT NULL, CHANGE active_until active_until DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician CHANGE active_from active_from DATE DEFAULT NULL, CHANGE active_until active_until DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating DROP FOREIGN KEY FK_D88926221137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D88926221137ABCF ON rating
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating DROP album_id
        SQL);
    }
}
