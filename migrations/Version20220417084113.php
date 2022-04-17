<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220417084113 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, method VARCHAR(10) NOT NULL, url LONGTEXT NOT NULL, created_at DATETIME NOT NULL, ip VARCHAR(45) DEFAULT NULL, status_code INT NOT NULL, INDEX IDX_AC74095AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE checklist ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE checklist ADD CONSTRAINT FK_5C696D2FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5C696D2FA76ED395 ON checklist (user_id)');
        $this->addSql('ALTER TABLE to_do ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE to_do ADD CONSTRAINT FK_1249EDA0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1249EDA0A76ED395 ON to_do (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity');
        $this->addSql('ALTER TABLE checklist DROP FOREIGN KEY FK_5C696D2FA76ED395');
        $this->addSql('DROP INDEX IDX_5C696D2FA76ED395 ON checklist');
        $this->addSql('ALTER TABLE checklist DROP user_id');
        $this->addSql('ALTER TABLE to_do DROP FOREIGN KEY FK_1249EDA0A76ED395');
        $this->addSql('DROP INDEX IDX_1249EDA0A76ED395 ON to_do');
        $this->addSql('ALTER TABLE to_do DROP user_id');
    }
}
