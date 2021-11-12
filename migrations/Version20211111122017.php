<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211111122017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_plan_day DROP FOREIGN KEY FK_E24815ABF396750');
        $this->addSql('ALTER TABLE training_plan_day ADD id_training_plan INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_plan_day ADD CONSTRAINT FK_E24815A6CE8CD5B FOREIGN KEY (id_training_plan) REFERENCES training_plan_list (id)');
        $this->addSql('CREATE INDEX IDX_E24815A6CE8CD5B ON training_plan_day (id_training_plan)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_plan_day DROP FOREIGN KEY FK_E24815A6CE8CD5B');
        $this->addSql('DROP INDEX IDX_E24815A6CE8CD5B ON training_plan_day');
        $this->addSql('ALTER TABLE training_plan_day DROP id_training_plan');
        $this->addSql('ALTER TABLE training_plan_day ADD CONSTRAINT FK_E24815ABF396750 FOREIGN KEY (id) REFERENCES training_plan_list (id)');
    }
}
