<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411082230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AA76ED395');
        $this->addSql('ALTER TABLE checklist DROP FOREIGN KEY FK_5C696D2FA76ED395');
        $this->addSql('ALTER TABLE to_do DROP FOREIGN KEY FK_1249EDA0A76ED395');
        $this->addSql('ALTER TABLE to_do_user DROP FOREIGN KEY FK_833B66BDA76ED395');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE to_do_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_5C696D2FA76ED395 ON checklist');
        $this->addSql('ALTER TABLE checklist DROP user_id');
        $this->addSql('DROP INDEX IDX_1249EDA07E3C61F9 ON to_do');
        $this->addSql('ALTER TABLE to_do DROP owner_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, to_do_id INT DEFAULT NULL, created_at DATETIME NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, method VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, url LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status_code INT DEFAULT NULL, changes JSON DEFAULT NULL, INDEX IDX_AC74095A5BE9ECD7 (to_do_id), INDEX IDX_AC74095AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE to_do_user (to_do_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_833B66BD5BE9ECD7 (to_do_id), INDEX IDX_833B66BDA76ED395 (user_id), PRIMARY KEY(to_do_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A5BE9ECD7 FOREIGN KEY (to_do_id) REFERENCES to_do (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE to_do_user ADD CONSTRAINT FK_833B66BD5BE9ECD7 FOREIGN KEY (to_do_id) REFERENCES to_do (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE to_do_user ADD CONSTRAINT FK_833B66BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE checklist ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE checklist ADD CONSTRAINT FK_5C696D2FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5C696D2FA76ED395 ON checklist (user_id)');
        $this->addSql('ALTER TABLE to_do ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE to_do ADD CONSTRAINT FK_1249EDA0A76ED395 FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1249EDA07E3C61F9 ON to_do (owner_id)');
    }
}
