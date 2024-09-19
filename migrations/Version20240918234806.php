<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918234806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B6BD307F54177093 (room_id), INDEX IDX_B6BD307F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, picture_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', visibility VARCHAR(10) NOT NULL, INDEX IDX_729F519B7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_admin (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, related_profil_id INT DEFAULT NULL, added_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C2B2ACDD54177093 (room_id), INDEX IDX_C2B2ACDD8F9F8C63 (related_profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_member (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, member_id INT DEFAULT NULL, join_date DATETIME NOT NULL, status VARCHAR(20) DEFAULT NULL, INDEX IDX_31AA3CB954177093 (room_id), INDEX IDX_31AA3CB97597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profil (id INT AUTO_INCREMENT NOT NULL, related_user_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, photo_url VARCHAR(255) DEFAULT NULL, status VARCHAR(5) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8384A9AA98771930 (related_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_profil (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_profil (id)');
        $this->addSql('ALTER TABLE room_admin ADD CONSTRAINT FK_C2B2ACDD54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room_admin ADD CONSTRAINT FK_C2B2ACDD8F9F8C63 FOREIGN KEY (related_profil_id) REFERENCES user_profil (id)');
        $this->addSql('ALTER TABLE room_member ADD CONSTRAINT FK_31AA3CB954177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room_member ADD CONSTRAINT FK_31AA3CB97597D3FE FOREIGN KEY (member_id) REFERENCES user_profil (id)');
        $this->addSql('ALTER TABLE user_profil ADD CONSTRAINT FK_8384A9AA98771930 FOREIGN KEY (related_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F54177093');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F7E3C61F9');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B7E3C61F9');
        $this->addSql('ALTER TABLE room_admin DROP FOREIGN KEY FK_C2B2ACDD54177093');
        $this->addSql('ALTER TABLE room_admin DROP FOREIGN KEY FK_C2B2ACDD8F9F8C63');
        $this->addSql('ALTER TABLE room_member DROP FOREIGN KEY FK_31AA3CB954177093');
        $this->addSql('ALTER TABLE room_member DROP FOREIGN KEY FK_31AA3CB97597D3FE');
        $this->addSql('ALTER TABLE user_profil DROP FOREIGN KEY FK_8384A9AA98771930');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_admin');
        $this->addSql('DROP TABLE room_member');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_profil');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
