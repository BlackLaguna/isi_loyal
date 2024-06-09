<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603140632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            <<<SQL
                CREATE TABLE loyalty_program_client (
                    client_id VARCHAR(255) NOT NULL,
                    loyalty_program_id UUID NOT NULL,
                    value_factor INTEGER DEFAULT 0,
                    loyalty_level_id UUID DEFAULT NULL,
                    PRIMARY KEY (client_id, loyalty_program_id),
                    FOREIGN KEY (client_id) REFERENCES clients(email) ON DELETE CASCADE,
                    FOREIGN KEY (loyalty_program_id) REFERENCES loyalty_programs(id) ON DELETE CASCADE
                );
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE loyalty_program_client');
    }
}
