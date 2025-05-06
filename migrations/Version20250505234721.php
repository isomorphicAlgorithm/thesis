<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250505234721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, release_date DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', genre VARCHAR(128) DEFAULT NULL, duration INT DEFAULT NULL, cover_image VARCHAR(128) DEFAULT NULL, description LONGTEXT DEFAULT NULL, links LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE album_band (album_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_2CD414AB1137ABCF (album_id), INDEX IDX_2CD414AB49ABEB17 (band_id), PRIMARY KEY(album_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE album_musician (album_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_C29DE7321137ABCF (album_id), INDEX IDX_C29DE7329523AA8A (musician_id), PRIMARY KEY(album_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE band (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, bio LONGTEXT DEFAULT NULL, links LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', cover_image VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE custom_list (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, title VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_45BE30E59D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE custom_list_item (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE custom_list_item_custom_list (custom_list_item_id INT NOT NULL, custom_list_id INT NOT NULL, INDEX IDX_6A81F86DB5A79848 (custom_list_item_id), INDEX IDX_6A81F86D3AF77F46 (custom_list_id), PRIMARY KEY(custom_list_item_id, custom_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE custom_list_item_album (custom_list_item_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_AF3EDFB0B5A79848 (custom_list_item_id), INDEX IDX_AF3EDFB01137ABCF (album_id), PRIMARY KEY(custom_list_item_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE custom_list_item_song (custom_list_item_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_9CF493EBB5A79848 (custom_list_item_id), INDEX IDX_9CF493EBA0BDB2F3 (song_id), PRIMARY KEY(custom_list_item_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, location VARCHAR(128) DEFAULT NULL, date DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', description LONGTEXT DEFAULT NULL, links LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_band (event_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_5714EE4071F7E88B (event_id), INDEX IDX_5714EE4049ABEB17 (band_id), PRIMARY KEY(event_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_musician (event_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_779DA8F771F7E88B (event_id), INDEX IDX_779DA8F79523AA8A (musician_id), PRIMARY KEY(event_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_68C58ED99D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE favorite_song (favorite_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_DDEBF79EAA17481D (favorite_id), INDEX IDX_DDEBF79EA0BDB2F3 (song_id), PRIMARY KEY(favorite_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, link VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_band (media_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_8B987D38EA9FDD75 (media_id), INDEX IDX_8B987D3849ABEB17 (band_id), PRIMARY KEY(media_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_musician (media_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_3E106F95EA9FDD75 (media_id), INDEX IDX_3E106F959523AA8A (musician_id), PRIMARY KEY(media_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_album (media_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_2681AAF2EA9FDD75 (media_id), INDEX IDX_2681AAF21137ABCF (album_id), PRIMARY KEY(media_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_song (media_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_F0AA3172EA9FDD75 (media_id), INDEX IDX_F0AA3172A0BDB2F3 (song_id), PRIMARY KEY(media_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_event (media_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_24B7CE16EA9FDD75 (media_id), INDEX IDX_24B7CE1671F7E88B (event_id), PRIMARY KEY(media_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musician (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, bio LONGTEXT DEFAULT NULL, links LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', cover_image VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musician_band (musician_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_D6DD1B699523AA8A (musician_id), INDEX IDX_D6DD1B6949ABEB17 (band_id), PRIMARY KEY(musician_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, rating_score INT NOT NULL, review LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_D88926229D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rating_album (rating_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_A48E617FA32EFC6 (rating_id), INDEX IDX_A48E617F1137ABCF (album_id), PRIMARY KEY(rating_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, release_date DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', genre VARCHAR(128) DEFAULT NULL, duration INT DEFAULT NULL, cover_image VARCHAR(128) DEFAULT NULL, description LONGTEXT DEFAULT NULL, links LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_band (song_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_3B4C741EA0BDB2F3 (song_id), INDEX IDX_3B4C741E49ABEB17 (band_id), PRIMARY KEY(song_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_musician (song_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_9AAD176FA0BDB2F3 (song_id), INDEX IDX_9AAD176F9523AA8A (musician_id), PRIMARY KEY(song_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_album (song_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_F43CFB06A0BDB2F3 (song_id), INDEX IDX_F43CFB061137ABCF (album_id), PRIMARY KEY(song_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, is_verified TINYINT(1) NOT NULL, username VARCHAR(32) NOT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(128) NOT NULL, cover_image VARCHAR(128) DEFAULT NULL, bio LONGTEXT DEFAULT NULL, links LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_band ADD CONSTRAINT FK_2CD414AB1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_band ADD CONSTRAINT FK_2CD414AB49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_musician ADD CONSTRAINT FK_C29DE7321137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_musician ADD CONSTRAINT FK_C29DE7329523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list ADD CONSTRAINT FK_45BE30E59D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_custom_list ADD CONSTRAINT FK_6A81F86DB5A79848 FOREIGN KEY (custom_list_item_id) REFERENCES custom_list_item (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_custom_list ADD CONSTRAINT FK_6A81F86D3AF77F46 FOREIGN KEY (custom_list_id) REFERENCES custom_list (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_album ADD CONSTRAINT FK_AF3EDFB0B5A79848 FOREIGN KEY (custom_list_item_id) REFERENCES custom_list_item (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_album ADD CONSTRAINT FK_AF3EDFB01137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_song ADD CONSTRAINT FK_9CF493EBB5A79848 FOREIGN KEY (custom_list_item_id) REFERENCES custom_list_item (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_song ADD CONSTRAINT FK_9CF493EBA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_band ADD CONSTRAINT FK_5714EE4071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_band ADD CONSTRAINT FK_5714EE4049ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_musician ADD CONSTRAINT FK_779DA8F771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_musician ADD CONSTRAINT FK_779DA8F79523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED99D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite_song ADD CONSTRAINT FK_DDEBF79EAA17481D FOREIGN KEY (favorite_id) REFERENCES favorite (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite_song ADD CONSTRAINT FK_DDEBF79EA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_band ADD CONSTRAINT FK_8B987D38EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_band ADD CONSTRAINT FK_8B987D3849ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_musician ADD CONSTRAINT FK_3E106F95EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_musician ADD CONSTRAINT FK_3E106F959523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_album ADD CONSTRAINT FK_2681AAF2EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_album ADD CONSTRAINT FK_2681AAF21137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_song ADD CONSTRAINT FK_F0AA3172EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_song ADD CONSTRAINT FK_F0AA3172A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_event ADD CONSTRAINT FK_24B7CE16EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_event ADD CONSTRAINT FK_24B7CE1671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_band ADD CONSTRAINT FK_D6DD1B699523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_band ADD CONSTRAINT FK_D6DD1B6949ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating ADD CONSTRAINT FK_D88926229D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating_album ADD CONSTRAINT FK_A48E617FA32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating_album ADD CONSTRAINT FK_A48E617F1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_band ADD CONSTRAINT FK_3B4C741EA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_band ADD CONSTRAINT FK_3B4C741E49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_musician ADD CONSTRAINT FK_9AAD176FA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_musician ADD CONSTRAINT FK_9AAD176F9523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_album ADD CONSTRAINT FK_F43CFB06A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_album ADD CONSTRAINT FK_F43CFB061137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE album_band DROP FOREIGN KEY FK_2CD414AB1137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_band DROP FOREIGN KEY FK_2CD414AB49ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_musician DROP FOREIGN KEY FK_C29DE7321137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_musician DROP FOREIGN KEY FK_C29DE7329523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list DROP FOREIGN KEY FK_45BE30E59D86650F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_custom_list DROP FOREIGN KEY FK_6A81F86DB5A79848
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_custom_list DROP FOREIGN KEY FK_6A81F86D3AF77F46
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_album DROP FOREIGN KEY FK_AF3EDFB0B5A79848
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_album DROP FOREIGN KEY FK_AF3EDFB01137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_song DROP FOREIGN KEY FK_9CF493EBB5A79848
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_song DROP FOREIGN KEY FK_9CF493EBA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_band DROP FOREIGN KEY FK_5714EE4071F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_band DROP FOREIGN KEY FK_5714EE4049ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_musician DROP FOREIGN KEY FK_779DA8F771F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_musician DROP FOREIGN KEY FK_779DA8F79523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED99D86650F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite_song DROP FOREIGN KEY FK_DDEBF79EAA17481D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite_song DROP FOREIGN KEY FK_DDEBF79EA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_band DROP FOREIGN KEY FK_8B987D38EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_band DROP FOREIGN KEY FK_8B987D3849ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_musician DROP FOREIGN KEY FK_3E106F95EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_musician DROP FOREIGN KEY FK_3E106F959523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_album DROP FOREIGN KEY FK_2681AAF2EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_album DROP FOREIGN KEY FK_2681AAF21137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_song DROP FOREIGN KEY FK_F0AA3172EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_song DROP FOREIGN KEY FK_F0AA3172A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_event DROP FOREIGN KEY FK_24B7CE16EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_event DROP FOREIGN KEY FK_24B7CE1671F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_band DROP FOREIGN KEY FK_D6DD1B699523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_band DROP FOREIGN KEY FK_D6DD1B6949ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating DROP FOREIGN KEY FK_D88926229D86650F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating_album DROP FOREIGN KEY FK_A48E617FA32EFC6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating_album DROP FOREIGN KEY FK_A48E617F1137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_band DROP FOREIGN KEY FK_3B4C741EA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_band DROP FOREIGN KEY FK_3B4C741E49ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_musician DROP FOREIGN KEY FK_9AAD176FA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_musician DROP FOREIGN KEY FK_9AAD176F9523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_album DROP FOREIGN KEY FK_F43CFB06A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_album DROP FOREIGN KEY FK_F43CFB061137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE custom_list
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE custom_list_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE custom_list_item_custom_list
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE custom_list_item_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE custom_list_item_song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE favorite
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE favorite_song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musician_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rating
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rating_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
    }
}
