<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806124237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, depertement VARCHAR(255) DEFAULT NULL, cni VARCHAR(255) DEFAULT NULL, field VARCHAR(255) DEFAULT NULL, examination_center VARCHAR(255) DEFAULT NULL, certificate VARCHAR(255) DEFAULT NULL, date_of_birth DATETIME DEFAULT NULL, place_of_birth VARCHAR(255) DEFAULT NULL, certificate_year VARCHAR(255) DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, transaction_number VARCHAR(255) DEFAULT NULL, payement_receipt VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE candidate');
    }
}
