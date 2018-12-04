<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181122145105 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE report ALTER date DROP NOT NULL');
        $this->addSql('ALTER TABLE report ALTER patient_sex DROP NOT NULL');
        $this->addSql('ALTER TABLE report ALTER patient_age DROP NOT NULL');
        $this->addSql('ALTER TABLE report ALTER contributing_factors DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE report ALTER date SET NOT NULL');
        $this->addSql('ALTER TABLE report ALTER patient_sex SET NOT NULL');
        $this->addSql('ALTER TABLE report ALTER patient_age SET NOT NULL');
        $this->addSql('ALTER TABLE report ALTER contributing_factors SET NOT NULL');
    }
}
