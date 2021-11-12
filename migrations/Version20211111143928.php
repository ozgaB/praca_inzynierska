<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211111143928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_plan_exercise DROP FOREIGN KEY FK_DE168DF719F12383');
        $this->addSql('ALTER TABLE training_plan_exercise ADD CONSTRAINT FK_DE168DF719F12383 FOREIGN KEY (training_plan_day_id) REFERENCES training_plan_day (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_plan_exercise DROP FOREIGN KEY FK_DE168DF719F12383');
        $this->addSql('ALTER TABLE training_plan_exercise ADD CONSTRAINT FK_DE168DF719F12383 FOREIGN KEY (training_plan_day_id) REFERENCES training_plan_day (id)');
    }
}
