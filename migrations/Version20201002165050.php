<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201002165050 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(255)  NULL, ADD lastname VARCHAR(255)  NULL, ADD billing_adress VARCHAR(255)  NULL, ADD billing_city VARCHAR(255)  NULL, ADD billing_postal_code VARCHAR(255)  NULL, ADD shipping_adress VARCHAR(255)  NULL, ADD shipping_city VARCHAR(255)  NULL, ADD shipping_postal_code VARCHAR(255)  NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP firstname, DROP lastname, DROP billing_adress, DROP billing_city, DROP billing_postal_code, DROP shipping_adress, DROP shipping_city, DROP shipping_postal_code');
    }
}
