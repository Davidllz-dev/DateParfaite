<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616141306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE reponses (id INT AUTO_INCREMENT NOT NULL, invitation_id INT DEFAULT NULL, commentaires VARCHAR(180) NOT NULL, nom VARCHAR(45) NOT NULL, prenom VARCHAR(45) NOT NULL, valider TINYINT(1) NOT NULL, date_reponse DATETIME NOT NULL, INDEX IDX_1E512EC6A35D7AF0 (invitation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6A35D7AF0 FOREIGN KEY (invitation_id) REFERENCES invitations (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6A35D7AF0
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reponses
        SQL);
    }
}
