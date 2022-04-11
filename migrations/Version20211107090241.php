<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211107090241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create checklist Entity';
    }
    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TABLE checklist (
            id INT AUTO_INCREMENT NOT NULL, 
            title VARCHAR(100) DEFAULT NULL, 
            todos VARCHAR(255) DEFAULT NULL, 
            PRIMARY KEY(id)
           ) 
                DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
           ');
    }
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE checklist');
    }
}
