<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520194932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE album_song (album_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_57E658E11137ABCF (album_id), INDEX IDX_57E658E1A0BDB2F3 (song_id), PRIMARY KEY(album_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE album_rating (album_id INT NOT NULL, rating_id INT NOT NULL, INDEX IDX_AF21B951137ABCF (album_id), INDEX IDX_AF21B95A32EFC6 (rating_id), PRIMARY KEY(album_id, rating_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE album_medium (album_id INT NOT NULL, medium_id INT NOT NULL, INDEX IDX_140878001137ABCF (album_id), INDEX IDX_14087800E252B6A5 (medium_id), PRIMARY KEY(album_id, medium_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE album_genre (album_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_F5E879DE1137ABCF (album_id), INDEX IDX_F5E879DE4296D31F (genre_id), PRIMARY KEY(album_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE band_musician (band_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_F13A002D49ABEB17 (band_id), INDEX IDX_F13A002D9523AA8A (musician_id), PRIMARY KEY(band_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE band_album (band_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_C57AD0DF49ABEB17 (band_id), INDEX IDX_C57AD0DF1137ABCF (album_id), PRIMARY KEY(band_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE band_song (band_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_68DB0EFA49ABEB17 (band_id), INDEX IDX_68DB0EFAA0BDB2F3 (song_id), PRIMARY KEY(band_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE band_event (band_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_C74CB43B49ABEB17 (band_id), INDEX IDX_C74CB43B71F7E88B (event_id), PRIMARY KEY(band_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE band_medium (band_id INT NOT NULL, medium_id INT NOT NULL, INDEX IDX_3F36786649ABEB17 (band_id), INDEX IDX_3F367866E252B6A5 (medium_id), PRIMARY KEY(band_id, medium_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE band_genre (band_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_7FB28D6449ABEB17 (band_id), INDEX IDX_7FB28D644296D31F (genre_id), PRIMARY KEY(band_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE custom_list_custom_list_item (custom_list_id INT NOT NULL, custom_list_item_id INT NOT NULL, INDEX IDX_DFB81DEF3AF77F46 (custom_list_id), INDEX IDX_DFB81DEFB5A79848 (custom_list_item_id), PRIMARY KEY(custom_list_id, custom_list_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_medium (event_id INT NOT NULL, medium_id INT NOT NULL, INDEX IDX_F4EA32E571F7E88B (event_id), INDEX IDX_F4EA32E5E252B6A5 (medium_id), PRIMARY KEY(event_id, medium_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE medium (id INT AUTO_INCREMENT NOT NULL, link VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musician_album (musician_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_3AB08EF69523AA8A (musician_id), INDEX IDX_3AB08EF61137ABCF (album_id), PRIMARY KEY(musician_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musician_song (musician_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_ADEF57239523AA8A (musician_id), INDEX IDX_ADEF5723A0BDB2F3 (song_id), PRIMARY KEY(musician_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musician_event (musician_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_3886EA129523AA8A (musician_id), INDEX IDX_3886EA1271F7E88B (event_id), PRIMARY KEY(musician_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musician_medium (musician_id INT NOT NULL, medium_id INT NOT NULL, INDEX IDX_7D7B2A549523AA8A (musician_id), INDEX IDX_7D7B2A54E252B6A5 (medium_id), PRIMARY KEY(musician_id, medium_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musician_genre (musician_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_8078D34D9523AA8A (musician_id), INDEX IDX_8078D34D4296D31F (genre_id), PRIMARY KEY(musician_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_favorite (song_id INT NOT NULL, favorite_id INT NOT NULL, INDEX IDX_C319D891A0BDB2F3 (song_id), INDEX IDX_C319D891AA17481D (favorite_id), PRIMARY KEY(song_id, favorite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_medium (song_id INT NOT NULL, medium_id INT NOT NULL, INDEX IDX_C008543DA0BDB2F3 (song_id), INDEX IDX_C008543DE252B6A5 (medium_id), PRIMARY KEY(song_id, medium_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_genre (song_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_4EF4A6BDA0BDB2F3 (song_id), INDEX IDX_4EF4A6BD4296D31F (genre_id), PRIMARY KEY(song_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_song ADD CONSTRAINT FK_57E658E11137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_song ADD CONSTRAINT FK_57E658E1A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_rating ADD CONSTRAINT FK_AF21B951137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_rating ADD CONSTRAINT FK_AF21B95A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_medium ADD CONSTRAINT FK_140878001137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_medium ADD CONSTRAINT FK_14087800E252B6A5 FOREIGN KEY (medium_id) REFERENCES medium (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_genre ADD CONSTRAINT FK_F5E879DE1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_genre ADD CONSTRAINT FK_F5E879DE4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_musician ADD CONSTRAINT FK_F13A002D49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_musician ADD CONSTRAINT FK_F13A002D9523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_album ADD CONSTRAINT FK_C57AD0DF49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_album ADD CONSTRAINT FK_C57AD0DF1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_song ADD CONSTRAINT FK_68DB0EFA49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_song ADD CONSTRAINT FK_68DB0EFAA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_event ADD CONSTRAINT FK_C74CB43B49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_event ADD CONSTRAINT FK_C74CB43B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_medium ADD CONSTRAINT FK_3F36786649ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_medium ADD CONSTRAINT FK_3F367866E252B6A5 FOREIGN KEY (medium_id) REFERENCES medium (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_genre ADD CONSTRAINT FK_7FB28D6449ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_genre ADD CONSTRAINT FK_7FB28D644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_custom_list_item ADD CONSTRAINT FK_DFB81DEF3AF77F46 FOREIGN KEY (custom_list_id) REFERENCES custom_list (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_custom_list_item ADD CONSTRAINT FK_DFB81DEFB5A79848 FOREIGN KEY (custom_list_item_id) REFERENCES custom_list_item (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_medium ADD CONSTRAINT FK_F4EA32E571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_medium ADD CONSTRAINT FK_F4EA32E5E252B6A5 FOREIGN KEY (medium_id) REFERENCES medium (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_album ADD CONSTRAINT FK_3AB08EF69523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_album ADD CONSTRAINT FK_3AB08EF61137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_song ADD CONSTRAINT FK_ADEF57239523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_song ADD CONSTRAINT FK_ADEF5723A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_event ADD CONSTRAINT FK_3886EA129523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_event ADD CONSTRAINT FK_3886EA1271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_medium ADD CONSTRAINT FK_7D7B2A549523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_medium ADD CONSTRAINT FK_7D7B2A54E252B6A5 FOREIGN KEY (medium_id) REFERENCES medium (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_genre ADD CONSTRAINT FK_8078D34D9523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_genre ADD CONSTRAINT FK_8078D34D4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_favorite ADD CONSTRAINT FK_C319D891A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_favorite ADD CONSTRAINT FK_C319D891AA17481D FOREIGN KEY (favorite_id) REFERENCES favorite (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_medium ADD CONSTRAINT FK_C008543DA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_medium ADD CONSTRAINT FK_C008543DE252B6A5 FOREIGN KEY (medium_id) REFERENCES medium (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_genre ADD CONSTRAINT FK_4EF4A6BDA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_genre ADD CONSTRAINT FK_4EF4A6BD4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_band DROP FOREIGN KEY FK_2CD414AB1137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_band DROP FOREIGN KEY FK_2CD414AB49ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_musician DROP FOREIGN KEY FK_C29DE7329523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_musician DROP FOREIGN KEY FK_C29DE7321137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_custom_list DROP FOREIGN KEY FK_6A81F86D3AF77F46
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_custom_list DROP FOREIGN KEY FK_6A81F86DB5A79848
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_band DROP FOREIGN KEY FK_5714EE4049ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_band DROP FOREIGN KEY FK_5714EE4071F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_musician DROP FOREIGN KEY FK_779DA8F771F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_musician DROP FOREIGN KEY FK_779DA8F79523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite_song DROP FOREIGN KEY FK_DDEBF79EA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite_song DROP FOREIGN KEY FK_DDEBF79EAA17481D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_album DROP FOREIGN KEY FK_2681AAF21137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_album DROP FOREIGN KEY FK_2681AAF2EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_band DROP FOREIGN KEY FK_8B987D38EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_band DROP FOREIGN KEY FK_8B987D3849ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_event DROP FOREIGN KEY FK_24B7CE16EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_event DROP FOREIGN KEY FK_24B7CE1671F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_musician DROP FOREIGN KEY FK_3E106F959523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_musician DROP FOREIGN KEY FK_3E106F95EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_song DROP FOREIGN KEY FK_F0AA3172A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_song DROP FOREIGN KEY FK_F0AA3172EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_band DROP FOREIGN KEY FK_D6DD1B6949ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_band DROP FOREIGN KEY FK_D6DD1B699523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating_album DROP FOREIGN KEY FK_A48E617F1137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating_album DROP FOREIGN KEY FK_A48E617FA32EFC6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_album DROP FOREIGN KEY FK_F43CFB061137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_album DROP FOREIGN KEY FK_F43CFB06A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_band DROP FOREIGN KEY FK_3B4C741E49ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_band DROP FOREIGN KEY FK_3B4C741EA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_musician DROP FOREIGN KEY FK_9AAD176F9523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_musician DROP FOREIGN KEY FK_9AAD176FA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE custom_list_item_custom_list
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE favorite_song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media_song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musician_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rating_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_musician
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE album_band (album_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_2CD414AB1137ABCF (album_id), INDEX IDX_2CD414AB49ABEB17 (band_id), PRIMARY KEY(album_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE album_musician (album_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_C29DE7321137ABCF (album_id), INDEX IDX_C29DE7329523AA8A (musician_id), PRIMARY KEY(album_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE custom_list_item_custom_list (custom_list_item_id INT NOT NULL, custom_list_id INT NOT NULL, INDEX IDX_6A81F86D3AF77F46 (custom_list_id), INDEX IDX_6A81F86DB5A79848 (custom_list_item_id), PRIMARY KEY(custom_list_item_id, custom_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_band (event_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_5714EE4049ABEB17 (band_id), INDEX IDX_5714EE4071F7E88B (event_id), PRIMARY KEY(event_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_musician (event_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_779DA8F771F7E88B (event_id), INDEX IDX_779DA8F79523AA8A (musician_id), PRIMARY KEY(event_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE favorite_song (favorite_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_DDEBF79EA0BDB2F3 (song_id), INDEX IDX_DDEBF79EAA17481D (favorite_id), PRIMARY KEY(favorite_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', link VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_album (media_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_2681AAF21137ABCF (album_id), INDEX IDX_2681AAF2EA9FDD75 (media_id), PRIMARY KEY(media_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_band (media_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_8B987D3849ABEB17 (band_id), INDEX IDX_8B987D38EA9FDD75 (media_id), PRIMARY KEY(media_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_event (media_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_24B7CE1671F7E88B (event_id), INDEX IDX_24B7CE16EA9FDD75 (media_id), PRIMARY KEY(media_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_musician (media_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_3E106F959523AA8A (musician_id), INDEX IDX_3E106F95EA9FDD75 (media_id), PRIMARY KEY(media_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media_song (media_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_F0AA3172A0BDB2F3 (song_id), INDEX IDX_F0AA3172EA9FDD75 (media_id), PRIMARY KEY(media_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musician_band (musician_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_D6DD1B6949ABEB17 (band_id), INDEX IDX_D6DD1B699523AA8A (musician_id), PRIMARY KEY(musician_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rating_album (rating_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_A48E617F1137ABCF (album_id), INDEX IDX_A48E617FA32EFC6 (rating_id), PRIMARY KEY(rating_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_album (song_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_F43CFB061137ABCF (album_id), INDEX IDX_F43CFB06A0BDB2F3 (song_id), PRIMARY KEY(song_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_band (song_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_3B4C741E49ABEB17 (band_id), INDEX IDX_3B4C741EA0BDB2F3 (song_id), PRIMARY KEY(song_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE song_musician (song_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_9AAD176F9523AA8A (musician_id), INDEX IDX_9AAD176FA0BDB2F3 (song_id), PRIMARY KEY(song_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_band ADD CONSTRAINT FK_2CD414AB1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_band ADD CONSTRAINT FK_2CD414AB49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_musician ADD CONSTRAINT FK_C29DE7329523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_musician ADD CONSTRAINT FK_C29DE7321137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_custom_list ADD CONSTRAINT FK_6A81F86D3AF77F46 FOREIGN KEY (custom_list_id) REFERENCES custom_list (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_item_custom_list ADD CONSTRAINT FK_6A81F86DB5A79848 FOREIGN KEY (custom_list_item_id) REFERENCES custom_list_item (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_band ADD CONSTRAINT FK_5714EE4049ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_band ADD CONSTRAINT FK_5714EE4071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_musician ADD CONSTRAINT FK_779DA8F771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_musician ADD CONSTRAINT FK_779DA8F79523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite_song ADD CONSTRAINT FK_DDEBF79EA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite_song ADD CONSTRAINT FK_DDEBF79EAA17481D FOREIGN KEY (favorite_id) REFERENCES favorite (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_album ADD CONSTRAINT FK_2681AAF21137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_album ADD CONSTRAINT FK_2681AAF2EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_band ADD CONSTRAINT FK_8B987D38EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_band ADD CONSTRAINT FK_8B987D3849ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_event ADD CONSTRAINT FK_24B7CE16EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_event ADD CONSTRAINT FK_24B7CE1671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_musician ADD CONSTRAINT FK_3E106F959523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_musician ADD CONSTRAINT FK_3E106F95EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_song ADD CONSTRAINT FK_F0AA3172A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_song ADD CONSTRAINT FK_F0AA3172EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_band ADD CONSTRAINT FK_D6DD1B6949ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_band ADD CONSTRAINT FK_D6DD1B699523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating_album ADD CONSTRAINT FK_A48E617F1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rating_album ADD CONSTRAINT FK_A48E617FA32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_album ADD CONSTRAINT FK_F43CFB061137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_album ADD CONSTRAINT FK_F43CFB06A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_band ADD CONSTRAINT FK_3B4C741E49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_band ADD CONSTRAINT FK_3B4C741EA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_musician ADD CONSTRAINT FK_9AAD176F9523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_musician ADD CONSTRAINT FK_9AAD176FA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_song DROP FOREIGN KEY FK_57E658E11137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_song DROP FOREIGN KEY FK_57E658E1A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_rating DROP FOREIGN KEY FK_AF21B951137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_rating DROP FOREIGN KEY FK_AF21B95A32EFC6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_medium DROP FOREIGN KEY FK_140878001137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_medium DROP FOREIGN KEY FK_14087800E252B6A5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_genre DROP FOREIGN KEY FK_F5E879DE1137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE album_genre DROP FOREIGN KEY FK_F5E879DE4296D31F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_musician DROP FOREIGN KEY FK_F13A002D49ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_musician DROP FOREIGN KEY FK_F13A002D9523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_album DROP FOREIGN KEY FK_C57AD0DF49ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_album DROP FOREIGN KEY FK_C57AD0DF1137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_song DROP FOREIGN KEY FK_68DB0EFA49ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_song DROP FOREIGN KEY FK_68DB0EFAA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_event DROP FOREIGN KEY FK_C74CB43B49ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_event DROP FOREIGN KEY FK_C74CB43B71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_medium DROP FOREIGN KEY FK_3F36786649ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_medium DROP FOREIGN KEY FK_3F367866E252B6A5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_genre DROP FOREIGN KEY FK_7FB28D6449ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_genre DROP FOREIGN KEY FK_7FB28D644296D31F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_custom_list_item DROP FOREIGN KEY FK_DFB81DEF3AF77F46
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_list_custom_list_item DROP FOREIGN KEY FK_DFB81DEFB5A79848
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_medium DROP FOREIGN KEY FK_F4EA32E571F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_medium DROP FOREIGN KEY FK_F4EA32E5E252B6A5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_album DROP FOREIGN KEY FK_3AB08EF69523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_album DROP FOREIGN KEY FK_3AB08EF61137ABCF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_song DROP FOREIGN KEY FK_ADEF57239523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_song DROP FOREIGN KEY FK_ADEF5723A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_event DROP FOREIGN KEY FK_3886EA129523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_event DROP FOREIGN KEY FK_3886EA1271F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_medium DROP FOREIGN KEY FK_7D7B2A549523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_medium DROP FOREIGN KEY FK_7D7B2A54E252B6A5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_genre DROP FOREIGN KEY FK_8078D34D9523AA8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician_genre DROP FOREIGN KEY FK_8078D34D4296D31F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_favorite DROP FOREIGN KEY FK_C319D891A0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_favorite DROP FOREIGN KEY FK_C319D891AA17481D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_medium DROP FOREIGN KEY FK_C008543DA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_medium DROP FOREIGN KEY FK_C008543DE252B6A5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_genre DROP FOREIGN KEY FK_4EF4A6BDA0BDB2F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE song_genre DROP FOREIGN KEY FK_4EF4A6BD4296D31F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_rating
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_medium
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE album_genre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band_musician
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band_song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band_event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band_medium
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band_genre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE custom_list_custom_list_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_medium
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE medium
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musician_album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musician_song
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musician_event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musician_medium
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musician_genre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_favorite
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_medium
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE song_genre
        SQL);
    }
}
