<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108134451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_pathology (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, pathology_id INT DEFAULT NULL, INDEX IDX_7A9CCCAC6B899279 (patient_id), INDEX IDX_7A9CCCACCE86795D (pathology_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patient_pathology ADD CONSTRAINT FK_7A9CCCAC6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE patient_pathology ADD CONSTRAINT FK_7A9CCCACCE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient_pathology DROP FOREIGN KEY FK_7A9CCCAC6B899279');
        $this->addSql('ALTER TABLE patient_pathology DROP FOREIGN KEY FK_7A9CCCACCE86795D');
        $this->addSql('DROP TABLE patient_pathology');
    }
}
