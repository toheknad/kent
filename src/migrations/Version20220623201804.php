<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623201804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_filter_id INT DEFAULT NULL, chat_id INT NOT NULL, stage VARCHAR(255) DEFAULT NULL, step INT NOT NULL, name VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, is_auth TINYINT(1) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, about VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649D3753FBA (user_filter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_filter (id INT AUTO_INCREMENT NOT NULL, age_from INT DEFAULT NULL, age_to INT DEFAULT NULL, gender VARCHAR(15) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_search_result (id INT AUTO_INCREMENT NOT NULL, user_from_id INT DEFAULT NULL, user_to_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_2406035C20C3C701 (user_from_id), INDEX IDX_2406035CD2F7B13D (user_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D3753FBA FOREIGN KEY (user_filter_id) REFERENCES user_filter (id)');
        $this->addSql('ALTER TABLE user_search_result ADD CONSTRAINT FK_2406035C20C3C701 FOREIGN KEY (user_from_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_search_result ADD CONSTRAINT FK_2406035CD2F7B13D FOREIGN KEY (user_to_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_search_result DROP FOREIGN KEY FK_2406035C20C3C701');
        $this->addSql('ALTER TABLE user_search_result DROP FOREIGN KEY FK_2406035CD2F7B13D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D3753FBA');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_filter');
        $this->addSql('DROP TABLE user_search_result');
    }
}
