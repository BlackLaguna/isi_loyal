<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240602140646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invitations (uuid UUID NOT NULL, partner_id VARCHAR(180) DEFAULT NULL, loyalty_program_id UUID DEFAULT NULL, invitation_status enum_invitation_status NOT NULL, client_email VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_232710AE9393F8FE ON invitations (partner_id)');
        $this->addSql('CREATE INDEX IDX_232710AE8364CCED ON invitations (loyalty_program_id)');
        $this->addSql('COMMENT ON COLUMN invitations.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invitations.loyalty_program_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invitations.invitation_status IS \'(DC2Type:enum_invitation_status)\'');
        $this->addSql('CREATE TABLE loyalty_level (id UUID NOT NULL, loyalty_program_id UUID DEFAULT NULL, interval_start INT NOT NULL, interval_end INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_301C2C688364CCED ON loyalty_level (loyalty_program_id)');
        $this->addSql('COMMENT ON COLUMN loyalty_level.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN loyalty_level.loyalty_program_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE loyalty_programs (id UUID NOT NULL, partner_id VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A34D469B9393F8FE ON loyalty_programs (partner_id)');
        $this->addSql('COMMENT ON COLUMN loyalty_programs.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE partners (email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(email))');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE9393F8FE FOREIGN KEY (partner_id) REFERENCES partners (email) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE8364CCED FOREIGN KEY (loyalty_program_id) REFERENCES loyalty_programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE loyalty_level ADD CONSTRAINT FK_301C2C688364CCED FOREIGN KEY (loyalty_program_id) REFERENCES loyalty_programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE loyalty_programs ADD CONSTRAINT FK_A34D469B9393F8FE FOREIGN KEY (partner_id) REFERENCES partners (email) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invitations DROP CONSTRAINT FK_232710AE9393F8FE');
        $this->addSql('ALTER TABLE invitations DROP CONSTRAINT FK_232710AE8364CCED');
        $this->addSql('ALTER TABLE loyalty_level DROP CONSTRAINT FK_301C2C688364CCED');
        $this->addSql('ALTER TABLE loyalty_programs DROP CONSTRAINT FK_A34D469B9393F8FE');
        $this->addSql('DROP TABLE invitations');
        $this->addSql('DROP TABLE loyalty_level');
        $this->addSql('DROP TABLE loyalty_programs');
        $this->addSql('DROP TABLE partners');
    }
}
