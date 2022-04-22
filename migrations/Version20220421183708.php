<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220421183708 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE to_do CHANGE text text VARCHAR(250) NOT NULL');
    }
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE to_do CHANGE text text VARCHAR(100) NOT NULL');
    }
}
