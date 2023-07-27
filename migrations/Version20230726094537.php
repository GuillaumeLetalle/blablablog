<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726094537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, fk_user_id INT DEFAULT NULL, fk_article_id INT DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, date DATE DEFAULT NULL, INDEX IDX_67F068BC5741EEB9 (fk_user_id), INDEX IDX_67F068BC82FA4C0F (fk_article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC82FA4C0F FOREIGN KEY (fk_article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5741EEB9');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC82FA4C0F');
        $this->addSql('DROP TABLE commentaire');
    }
}
