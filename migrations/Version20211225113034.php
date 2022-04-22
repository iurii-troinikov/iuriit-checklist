<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211225113034 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE activity ADD to_do_id INT DEFAULT NULL, ADD type VARCHAR(255) NOT NULL AFTER id, CHANGE method method VARCHAR(10) DEFAULT NULL, CHANGE url url LONGTEXT DEFAULT NULL, CHANGE status_code status_code INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A5BE9ECD7 FOREIGN KEY (to_do_id) REFERENCES to_do (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A5BE9ECD7 ON activity (to_do_id)');
        $this->addSql('UPDATE activity SET type = \'visit\'');
    }
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A5BE9ECD7');
        $this->addSql('DROP INDEX IDX_AC74095A5BE9ECD7 ON activity');
        $this->addSql('ALTER TABLE activity DROP to_do_id, DROP type, CHANGE method method VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE url url LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE status_code status_code INT NOT NULL');
    }
}
