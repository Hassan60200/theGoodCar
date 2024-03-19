<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319205838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD region_id INT NOT NULL, ADD departement_id INT NOT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('CREATE INDEX IDX_773DE69D98260155 ON car (region_id)');
        $this->addSql('CREATE INDEX IDX_773DE69DCCF9E01E ON car (departement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D98260155');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DCCF9E01E');
        $this->addSql('DROP INDEX IDX_773DE69D98260155 ON car');
        $this->addSql('DROP INDEX IDX_773DE69DCCF9E01E ON car');
        $this->addSql('ALTER TABLE car DROP region_id, DROP departement_id');
    }
}
