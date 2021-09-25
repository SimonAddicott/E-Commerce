<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210925140448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(180) NOT NULL, ADD last_name VARCHAR(180) NOT NULL, ADD address_line_one VARCHAR(180) NOT NULL, ADD address_line_two VARCHAR(180) NOT NULL, ADD address_line_three VARCHAR(180) NOT NULL, ADD address_city VARCHAR(180) NOT NULL, ADD address_county VARCHAR(180) NOT NULL, ADD address_postcode VARCHAR(180) NOT NULL, ADD address_country_code VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP first_name, DROP last_name, DROP address_line_one, DROP address_line_two, DROP address_line_three, DROP address_city, DROP address_county, DROP address_postcode, DROP address_country_code');
    }
}
