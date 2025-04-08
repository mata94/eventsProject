<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241005181011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, name VARCHAR(255) NOT NULL, date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_3BAE0AA76BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slot (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, time_start DATETIME NOT NULL, time_end DATETIME NOT NULL, seats INT NOT NULL, INDEX IDX_AC0E206771F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slot_user (id INT AUTO_INCREMENT NOT NULL, slot_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', qr_code VARCHAR(255) DEFAULT NULL, scan_time DATETIME DEFAULT NULL, INDEX IDX_53BA7CDE59E5119C (slot_id), INDEX IDX_53BA7CDEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, verified TINYINT(1) NOT NULL, user_token VARCHAR(255) DEFAULT NULL, token_expiration DATETIME DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', full_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA76BF700BD FOREIGN KEY (status_id) REFERENCES event_status (id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E206771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE slot_user ADD CONSTRAINT FK_53BA7CDE59E5119C FOREIGN KEY (slot_id) REFERENCES slot (id)');
        $this->addSql('ALTER TABLE slot_user ADD CONSTRAINT FK_53BA7CDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA76BF700BD');
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E206771F7E88B');
        $this->addSql('ALTER TABLE slot_user DROP FOREIGN KEY FK_53BA7CDE59E5119C');
        $this->addSql('ALTER TABLE slot_user DROP FOREIGN KEY FK_53BA7CDEA76ED395');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_status');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE slot');
        $this->addSql('DROP TABLE slot_user');
        $this->addSql('DROP TABLE user');
    }
}
