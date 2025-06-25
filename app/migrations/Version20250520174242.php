<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520174242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE band ADD active_from DATE DEFAULT NULL, ADD active_until DATE DEFAULT NULL, ADD is_disbanded TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician ADD active_from DATE DEFAULT NULL, ADD active_until DATE DEFAULT NULL, ADD is_disbanded TINYINT(1) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE band DROP active_from, DROP active_until, DROP is_disbanded
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE musician DROP active_from, DROP active_until, DROP is_disbanded
        SQL);
    }
}
