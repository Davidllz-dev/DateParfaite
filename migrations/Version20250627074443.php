<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627074443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux DROP FOREIGN KEY FK_C26EF2439F072641
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux CHANGE creneaux_id creneaux_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux ADD CONSTRAINT FK_C26EF2439F072641 FOREIGN KEY (creneaux_id) REFERENCES creneaux (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux DROP FOREIGN KEY FK_C26EF2439F072641
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux CHANGE creneaux_id creneaux_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponses_creneaux ADD CONSTRAINT FK_C26EF2439F072641 FOREIGN KEY (creneaux_id) REFERENCES creneaux (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
    }
}
