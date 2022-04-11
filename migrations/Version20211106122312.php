<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211106122312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create todo entity';
    }
    public function up(Schema $schema): void
    {
        $this->addSql('
           CREATE TABLE to_do (
               id INT AUTO_INCREMENT NOT NULL, 
               text LONGTEXT NOT NULL, 
               PRIMARY KEY(id)
                  )
                   DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
               ');
    }
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE to_do');
    }
}
