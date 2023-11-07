<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107181811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_treatment (patient_id INT NOT NULL, treatment_id INT NOT NULL, INDEX IDX_3E8AEE0E6B899279 (patient_id), INDEX IDX_3E8AEE0E471C0366 (treatment_id), PRIMARY KEY(patient_id, treatment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE treatment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, dosage NUMERIC(10, 0) DEFAULT NULL, unit VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patient_treatment ADD CONSTRAINT FK_3E8AEE0E6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_treatment ADD CONSTRAINT FK_3E8AEE0E471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_traetment DROP FOREIGN KEY FK_5154880B131BF210');
        $this->addSql('ALTER TABLE patient_traetment DROP FOREIGN KEY FK_5154880B6B899279');
        $this->addSql('DROP TABLE patient_traetment');
        $this->addSql('DROP TABLE traetment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_traetment (patient_id INT NOT NULL, traetment_id INT NOT NULL, INDEX IDX_5154880B131BF210 (traetment_id), INDEX IDX_5154880B6B899279 (patient_id), PRIMARY KEY(patient_id, traetment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE traetment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dosage NUMERIC(10, 0) DEFAULT NULL, unit VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE patient_traetment ADD CONSTRAINT FK_5154880B131BF210 FOREIGN KEY (traetment_id) REFERENCES traetment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_traetment ADD CONSTRAINT FK_5154880B6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_treatment DROP FOREIGN KEY FK_3E8AEE0E6B899279');
        $this->addSql('ALTER TABLE patient_treatment DROP FOREIGN KEY FK_3E8AEE0E471C0366');
        $this->addSql('DROP TABLE patient_treatment');
        $this->addSql('DROP TABLE treatment');
    }
}
