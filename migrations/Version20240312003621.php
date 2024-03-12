<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240312003621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, code_departement_id INT NOT NULL, code_region_id INT NOT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, zip_code INT NOT NULL, INDEX IDX_2D5B023487C027E4 (code_departement_id), INDEX IDX_2D5B02343463796D (code_region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B023487C027E4 FOREIGN KEY (code_departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B02343463796D FOREIGN KEY (code_region_id) REFERENCES region (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B023487C027E4');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B02343463796D');
        $this->addSql('DROP TABLE city');
    }
}
