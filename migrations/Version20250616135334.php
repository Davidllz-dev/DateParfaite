<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616135334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE invitations (id INT AUTO_INCREMENT NOT NULL, reunion_id INT DEFAULT NULL, invite_email VARCHAR(50) NOT NULL, token VARCHAR(100) NOT NULL, INDEX IDX_232710AE4E9B7368 (reunion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE invitations ADD CONSTRAINT FK_232710AE4E9B7368 FOREIGN KEY (reunion_id) REFERENCES reunions (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE invitations DROP FOREIGN KEY FK_232710AE4E9B7368
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE invitations
        SQL);
    }
}
