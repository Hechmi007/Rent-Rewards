<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223094614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, payement_date INT NOT NULL, INDEX IDX_1CC16EFEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_credit DROP FOREIGN KEY FK_5E852811DF69572F');
        $this->addSql('ALTER TABLE demande_credit DROP FOREIGN KEY FK_5E852811ED766068');
        $this->addSql('DROP TABLE demande_credit');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande_credit (id INT AUTO_INCREMENT NOT NULL, username_id INT NOT NULL, points_id INT DEFAULT NULL, date_remboursement DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, etat TINYINT(1) NOT NULL, duration_credit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_5E852811ED766068 (username_id), UNIQUE INDEX UNIQ_5E852811DF69572F (points_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE demande_credit ADD CONSTRAINT FK_5E852811DF69572F FOREIGN KEY (points_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE demande_credit ADD CONSTRAINT FK_5E852811ED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFEA76ED395');
        $this->addSql('DROP TABLE credit');
    }
}
