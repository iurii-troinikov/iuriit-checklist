<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220129105745 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE to_do_user (to_do_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_833B66BD5BE9ECD7 (to_do_id), INDEX IDX_833B66BDA76ED395 (user_id), PRIMARY KEY(to_do_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE to_do_user ADD CONSTRAINT FK_833B66BD5BE9ECD7 FOREIGN KEY (to_do_id) REFERENCES to_do (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE to_do_user ADD CONSTRAINT FK_833B66BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE to_do CHANGE user_id owner_id INT NOT NULL');
        $this->addSql('
            INSERT INTO to_do_user (to_do_id, user_id)
            SELECT to_do.id AS to_do_id, to_do.owner_id AS user_id
            FROM to_do
        ');
        $this->addSql('ALTER TABLE to_do RENAME INDEX idx_1249eda0a76ed395 TO IDX_1249EDA07E3C61F9');

    }
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE to_do CHANGE owner_id user_id INT NOT NULL');
        $this->addSql('DROP TABLE to_do_user');
        $this->addSql('ALTER TABLE to_do RENAME INDEX idx_1249eda07e3c61f9 TO IDX_1249EDA0A76ED395');
    }
}
