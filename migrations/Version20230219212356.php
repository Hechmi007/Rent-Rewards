<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230219212356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charity_demand (id INT AUTO_INCREMENT NOT NULL, username_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, receiver VARCHAR(255) NOT NULL, pointsdemanded DOUBLE PRECISION NOT NULL, datedemand DATE NOT NULL, INDEX IDX_753E6705ED766068 (username_id), INDEX IDX_753E670512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE charitycategory (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, date_charity DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, idpost_id INT NOT NULL, username_id INT NOT NULL, contentcomment VARCHAR(255) NOT NULL, upvotes INT NOT NULL, createdatcomment DATE NOT NULL, INDEX IDX_9474526C948D5142 (idpost_id), INDEX IDX_9474526CED766068 (username_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_credit (id INT AUTO_INCREMENT NOT NULL, username_id INT NOT NULL, points_id INT DEFAULT NULL, date_remboursement DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, etat TINYINT(1) NOT NULL, duration_credit VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5E852811ED766068 (username_id), UNIQUE INDEX UNIQ_5E852811DF69572F (points_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donation (id INT AUTO_INCREMENT NOT NULL, username_id INT DEFAULT NULL, title_id INT DEFAULT NULL, pointsdonated DOUBLE PRECISION NOT NULL, datedonation DATE NOT NULL, INDEX IDX_31E581A0ED766068 (username_id), UNIQUE INDEX UNIQ_31E581A0A9F87BD (title_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, idproduit_id INT DEFAULT NULL, idproduit INT NOT NULL, nomproduit VARCHAR(255) NOT NULL, idclient INT NOT NULL, prixproduit DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, totalfacture DOUBLE PRECISION NOT NULL, idlocataire INT NOT NULL, date_facture DATE NOT NULL, adressfacture VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FE866410C29D63C1 (idproduit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fidelity_card (id INT AUTO_INCREMENT NOT NULL, username_id INT DEFAULT NULL, numcarte INT NOT NULL, datedebut DATE NOT NULL, datefin DATE NOT NULL, UNIQUE INDEX UNIQ_FB041D2ED766068 (username_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack (id INT AUTO_INCREMENT NOT NULL, numcarte_id INT DEFAULT NULL, INDEX IDX_97DE5E23788C3522 (numcarte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE packs (id INT AUTO_INCREMENT NOT NULL, nompacks VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, datedebutpacks DATE NOT NULL, datefinpacks DATE NOT NULL, etatpacks TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, username_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, rating INT NOT NULL, createdat DATE NOT NULL, INDEX IDX_5A8A6C8DED766068 (username_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, categoryname_id INT DEFAULT NULL, namecateg VARCHAR(255) NOT NULL, typecategory VARCHAR(255) NOT NULL, INDEX IDX_CDFC735687CCB12E (categoryname_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, productname VARCHAR(255) NOT NULL, rent_price DOUBLE PRECISION NOT NULL, availabilitydate DATE NOT NULL, product_type VARCHAR(255) NOT NULL, product_picture VARCHAR(255) NOT NULL, product_adress VARCHAR(255) NOT NULL, still_available TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nomproduit VARCHAR(255) NOT NULL, prixproduit DOUBLE PRECISION NOT NULL, dateproduit DATE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traitement_credit (id INT AUTO_INCREMENT NOT NULL, username_id INT NOT NULL, status VARCHAR(255) NOT NULL, penality VARCHAR(255) NOT NULL, restab VARCHAR(255) DEFAULT NULL, restant DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_FDCCEA47ED766068 (username_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, username_id INT DEFAULT NULL, solde DOUBLE PRECISION NOT NULL, points DOUBLE PRECISION NOT NULL, plan VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7C68921FED766068 (username_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE charity_demand ADD CONSTRAINT FK_753E6705ED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE charity_demand ADD CONSTRAINT FK_753E670512469DE2 FOREIGN KEY (category_id) REFERENCES charitycategory (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C948D5142 FOREIGN KEY (idpost_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_credit ADD CONSTRAINT FK_5E852811ED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_credit ADD CONSTRAINT FK_5E852811DF69572F FOREIGN KEY (points_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0ED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0A9F87BD FOREIGN KEY (title_id) REFERENCES charity_demand (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410C29D63C1 FOREIGN KEY (idproduit_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE fidelity_card ADD CONSTRAINT FK_FB041D2ED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pack ADD CONSTRAINT FK_97DE5E23788C3522 FOREIGN KEY (numcarte_id) REFERENCES fidelity_card (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735687CCB12E FOREIGN KEY (categoryname_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE traitement_credit ADD CONSTRAINT FK_FDCCEA47ED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921FED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charity_demand DROP FOREIGN KEY FK_753E6705ED766068');
        $this->addSql('ALTER TABLE charity_demand DROP FOREIGN KEY FK_753E670512469DE2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C948D5142');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CED766068');
        $this->addSql('ALTER TABLE demande_credit DROP FOREIGN KEY FK_5E852811ED766068');
        $this->addSql('ALTER TABLE demande_credit DROP FOREIGN KEY FK_5E852811DF69572F');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0ED766068');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0A9F87BD');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410C29D63C1');
        $this->addSql('ALTER TABLE fidelity_card DROP FOREIGN KEY FK_FB041D2ED766068');
        $this->addSql('ALTER TABLE pack DROP FOREIGN KEY FK_97DE5E23788C3522');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DED766068');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735687CCB12E');
        $this->addSql('ALTER TABLE traitement_credit DROP FOREIGN KEY FK_FDCCEA47ED766068');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921FED766068');
        $this->addSql('DROP TABLE charity_demand');
        $this->addSql('DROP TABLE charitycategory');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE demande_credit');
        $this->addSql('DROP TABLE donation');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE fidelity_card');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE pack');
        $this->addSql('DROP TABLE packs');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE traitement_credit');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
