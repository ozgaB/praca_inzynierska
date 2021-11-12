<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211111143833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_plan_day DROP FOREIGN KEY FK_E24815A6CE8CD5B');
        $this->addSql('ALTER TABLE training_plan_day ADD CONSTRAINT FK_E24815A6CE8CD5B FOREIGN KEY (id_training_plan) REFERENCES training_plan_list (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_plan_day DROP FOREIGN KEY FK_E24815A6CE8CD5B');
        $this->addSql('ALTER TABLE training_plan_day ADD CONSTRAINT FK_E24815A6CE8CD5B FOREIGN KEY (id_training_plan) REFERENCES training_plan_list (id)');
    }
}
