<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413111149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brands_car (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_190037DD5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, car_model_id INT NOT NULL, region_id INT NOT NULL, departement_id INT NOT NULL, city_id INT NOT NULL, order_car_id INT DEFAULT NULL, year_of_manufacture INT NOT NULL, color VARCHAR(15) NOT NULL, price DOUBLE PRECISION NOT NULL, fuel_type VARCHAR(20) NOT NULL, mileage INT NOT NULL, img VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_773DE69D44F5D008 (brand_id), INDEX IDX_773DE69DF64382E3 (car_model_id), INDEX IDX_773DE69D98260155 (region_id), INDEX IDX_773DE69DCCF9E01E (departement_id), INDEX IDX_773DE69D8BAC62AF (city_id), INDEX IDX_773DE69D49E64A (order_car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, code_departement_id INT NOT NULL, code_region_id INT NOT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, zip_code INT NOT NULL, INDEX IDX_2D5B023487C027E4 (code_departement_id), INDEX IDX_2D5B02343463796D (code_region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(5) NOT NULL, INDEX IDX_C1765B6398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE models_car (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_1330118544F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, price INT NOT NULL, purchase_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, stripe_id VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, age INT NOT NULL, is_verified TINYINT(1) NOT NULL, verify_email_token VARCHAR(255) DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D44F5D008 FOREIGN KEY (brand_id) REFERENCES brands_car (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DF64382E3 FOREIGN KEY (car_model_id) REFERENCES models_car (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D49E64A FOREIGN KEY (order_car_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B023487C027E4 FOREIGN KEY (code_departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B02343463796D FOREIGN KEY (code_region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B6398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE models_car ADD CONSTRAINT FK_1330118544F5D008 FOREIGN KEY (brand_id) REFERENCES brands_car (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D44F5D008');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DF64382E3');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D98260155');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DCCF9E01E');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D8BAC62AF');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D49E64A');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B023487C027E4');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B02343463796D');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B6398260155');
        $this->addSql('ALTER TABLE models_car DROP FOREIGN KEY FK_1330118544F5D008');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('DROP TABLE brands_car');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE models_car');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
