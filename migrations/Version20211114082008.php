<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211114082008 extends AbstractMigration
{
 function up(Schema $schema): void
    {

        $this->addSql('ALTER TABLE to_do ADD checklist_id INT NOT NULL');
        $this->addSql('ALTER TABLE to_do ADD CONSTRAINT FK_1249EDA0B16D08A7 FOREIGN KEY (checklist_id) REFERENCES checklist (id)');
        $this->addSql('CREATE INDEX IDX_1249EDA0B16D08A7 ON to_do (checklist_id)');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('ALTER TABLE to_do DROP FOREIGN KEY FK_1249EDA0B16D08A7');
        $this->addSql('DROP INDEX IDX_1249EDA0B16D08A7 ON to_do');
        $this->addSql('ALTER TABLE to_do DROP checklist_id');
    }
}
