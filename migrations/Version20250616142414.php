<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616142414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE reponses_creneaux (id INT AUTO_INCREMENT NOT NULL, creneaux_id INT DEFAULT NULL, reponse_id INT DEFAULT NULL, confirmer TINYINT(1) NOT NULL, INDEX IDX_C26EF2439F072641 (creneaux_id), INDEX IDX_C26EF243CF18BB82 (reponse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux ADD CONSTRAINT FK_C26EF2439F072641 FOREIGN KEY (creneaux_id) REFERENCES creneaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux ADD CONSTRAINT FK_C26EF243CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponses (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux DROP FOREIGN KEY FK_C26EF2439F072641
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux DROP FOREIGN KEY FK_C26EF243CF18BB82
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reponses_creneaux
        SQL);
    }
}
