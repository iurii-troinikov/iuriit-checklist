<?php
/*
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20220129121222 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A5BE9ECD7');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A5BE9ECD7 FOREIGN KEY (to_do_id) REFERENCES to_do (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A5BE9ECD7');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A5BE9ECD7 FOREIGN KEY (to_do_id) REFERENCES to_do (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}*/
