<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220212085024 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            UPDATE activity 
            SET changes = JSON_REMOVE(changes, \'$.checklist\')
            WHERE `type` = \'edit_todo\'
        ');
    }

    public function down(Schema $schema): void
    {
    }
}
