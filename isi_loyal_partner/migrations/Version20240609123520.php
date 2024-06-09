<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240609123520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE client_purchases (uuid UUID NOT NULL, created_at DATE NOT NULL, loyalty_program_client_loyalty_program_id UUID NOT NULL, loyalty_program_client_client_id VARCHAR(255) NOT NULL, value_factor INTEGER NOT NULL, PRIMARY KEY(uuid))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE client_purchases');
    }
}
