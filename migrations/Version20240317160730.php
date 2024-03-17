<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317160730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE models_car (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_1330118544F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE models_car ADD CONSTRAINT FK_1330118544F5D008 FOREIGN KEY (brand_id) REFERENCES brands_car (id)');
        $this->addSql('ALTER TABLE car ADD car_model_id INT NOT NULL, DROP model');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DF64382E3 FOREIGN KEY (car_model_id) REFERENCES models_car (id)');
        $this->addSql('CREATE INDEX IDX_773DE69DF64382E3 ON car (car_model_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DF64382E3');
        $this->addSql('ALTER TABLE models_car DROP FOREIGN KEY FK_1330118544F5D008');
        $this->addSql('DROP TABLE models_car');
        $this->addSql('DROP INDEX IDX_773DE69DF64382E3 ON car');
        $this->addSql('ALTER TABLE car ADD model VARCHAR(50) NOT NULL, DROP car_model_id');
    }
}
