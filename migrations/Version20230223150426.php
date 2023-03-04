<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223150426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payement_credit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, credit_id INT NOT NULL, montantpaye DOUBLE PRECISION NOT NULL, dateremboursement DATE NOT NULL, INDEX IDX_25665D34A76ED395 (user_id), UNIQUE INDEX UNIQ_25665D34CE062FF9 (credit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payement_credit ADD CONSTRAINT FK_25665D34A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payement_credit ADD CONSTRAINT FK_25665D34CE062FF9 FOREIGN KEY (credit_id) REFERENCES credit (id)');
        $this->addSql('ALTER TABLE comment CHANGE username_id username_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payement_credit DROP FOREIGN KEY FK_25665D34A76ED395');
        $this->addSql('ALTER TABLE payement_credit DROP FOREIGN KEY FK_25665D34CE062FF9');
        $this->addSql('DROP TABLE payement_credit');
        $this->addSql('ALTER TABLE comment CHANGE username_id username_id INT DEFAULT NULL');
    }
}
