<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220417085552 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE to_do (id INT AUTO_INCREMENT NOT NULL, checklist_id INT NOT NULL, text VARCHAR(100) NOT NULL, INDEX IDX_1249EDA0B16D08A7 (checklist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE to_do ADD CONSTRAINT FK_1249EDA0B16D08A7 FOREIGN KEY (checklist_id) REFERENCES checklist (id)');
        $this->addSql('DROP TABLE todo');
        $this->addSql('ALTER TABLE checklist DROP todos, CHANGE title title VARCHAR(255) NOT NULL');
    }
    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE todo (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE to_do');
        $this->addSql('ALTER TABLE checklist ADD todos VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(100) DEFAULT NULL');
    }
}
