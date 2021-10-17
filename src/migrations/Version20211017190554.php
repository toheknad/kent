<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211017190554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_filter ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_filter ADD CONSTRAINT FK_1A964420A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1A964420A76ED395 ON user_filter (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_filter DROP FOREIGN KEY FK_1A964420A76ED395');
        $this->addSql('DROP INDEX UNIQ_1A964420A76ED395 ON user_filter');
        $this->addSql('ALTER TABLE user_filter DROP user_id');
    }
}
