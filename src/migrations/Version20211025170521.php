<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025170521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_search_result (id INT AUTO_INCREMENT NOT NULL, user_from_id INT DEFAULT NULL, user_to_id INT DEFAULT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_2406035C20C3C701 (user_from_id), INDEX IDX_2406035CD2F7B13D (user_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_search_result ADD CONSTRAINT FK_2406035C20C3C701 FOREIGN KEY (user_from_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_search_result ADD CONSTRAINT FK_2406035CD2F7B13D FOREIGN KEY (user_to_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_search_result');
    }
}
