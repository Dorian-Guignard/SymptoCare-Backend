<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107153706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE antecedent (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, year INT DEFAULT NULL, INDEX IDX_3166BE7C6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clinic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(255) NOT NULL, phone_number INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE constant (id INT AUTO_INCREMENT NOT NULL, constant_type_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, value NUMERIC(10, 0) DEFAULT NULL, date DATE NOT NULL, time TIME NOT NULL, INDEX IDX_CB6AD5D8AAADD953 (constant_type_id), INDEX IDX_CB6AD5D86B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE constant_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, normal_range VARCHAR(255) NOT NULL, unit VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, speciality VARCHAR(50) NOT NULL, address VARCHAR(255) NOT NULL, phone_number INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pathology (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, pathology_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, date_birth VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_1ADAD7EBCE86795D (pathology_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_symptom (patient_id INT NOT NULL, symptom_id INT NOT NULL, INDEX IDX_B9A1A54B6B899279 (patient_id), INDEX IDX_B9A1A54BDEEFDA95 (symptom_id), PRIMARY KEY(patient_id, symptom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_treatment (patient_id INT NOT NULL, treatment_id INT NOT NULL, INDEX IDX_5154880B6B899279 (patient_id), INDEX IDX_5154880B131BF210 (treatment_id), PRIMARY KEY(patient_id, treatment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_doctor (patient_id INT NOT NULL, doctor_id INT NOT NULL, INDEX IDX_148E1A906B899279 (patient_id), INDEX IDX_148E1A9087F4FB17 (doctor_id), PRIMARY KEY(patient_id, doctor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_clinic (patient_id INT NOT NULL, clinic_id INT NOT NULL, INDEX IDX_CCD114E6B899279 (patient_id), INDEX IDX_CCD114ECC22AD4 (clinic_id), PRIMARY KEY(patient_id, clinic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE symptom (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, time TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE treatment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, dosage NUMERIC(10, 0) DEFAULT NULL, unit VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE antecedent ADD CONSTRAINT FK_3166BE7C6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE constant ADD CONSTRAINT FK_CB6AD5D8AAADD953 FOREIGN KEY (constant_type_id) REFERENCES constant_type (id)');
        $this->addSql('ALTER TABLE constant ADD CONSTRAINT FK_CB6AD5D86B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBCE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');
        $this->addSql('ALTER TABLE patient_symptom ADD CONSTRAINT FK_B9A1A54B6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_symptom ADD CONSTRAINT FK_B9A1A54BDEEFDA95 FOREIGN KEY (symptom_id) REFERENCES symptom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_treatment ADD CONSTRAINT FK_5154880B6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_treatment ADD CONSTRAINT FK_5154880B131BF210 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_doctor ADD CONSTRAINT FK_148E1A906B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_doctor ADD CONSTRAINT FK_148E1A9087F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_clinic ADD CONSTRAINT FK_CCD114E6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_clinic ADD CONSTRAINT FK_CCD114ECC22AD4 FOREIGN KEY (clinic_id) REFERENCES clinic (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE antecedent DROP FOREIGN KEY FK_3166BE7C6B899279');
        $this->addSql('ALTER TABLE constant DROP FOREIGN KEY FK_CB6AD5D8AAADD953');
        $this->addSql('ALTER TABLE constant DROP FOREIGN KEY FK_CB6AD5D86B899279');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBCE86795D');
        $this->addSql('ALTER TABLE patient_symptom DROP FOREIGN KEY FK_B9A1A54B6B899279');
        $this->addSql('ALTER TABLE patient_symptom DROP FOREIGN KEY FK_B9A1A54BDEEFDA95');
        $this->addSql('ALTER TABLE patient_treatment DROP FOREIGN KEY FK_5154880B6B899279');
        $this->addSql('ALTER TABLE patient_treatment DROP FOREIGN KEY FK_5154880B131BF210');
        $this->addSql('ALTER TABLE patient_doctor DROP FOREIGN KEY FK_148E1A906B899279');
        $this->addSql('ALTER TABLE patient_doctor DROP FOREIGN KEY FK_148E1A9087F4FB17');
        $this->addSql('ALTER TABLE patient_clinic DROP FOREIGN KEY FK_CCD114E6B899279');
        $this->addSql('ALTER TABLE patient_clinic DROP FOREIGN KEY FK_CCD114ECC22AD4');
        $this->addSql('DROP TABLE antecedent');
        $this->addSql('DROP TABLE clinic');
        $this->addSql('DROP TABLE constant');
        $this->addSql('DROP TABLE constant_type');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE pathology');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_symptom');
        $this->addSql('DROP TABLE patient_treatment');
        $this->addSql('DROP TABLE patient_doctor');
        $this->addSql('DROP TABLE patient_clinic');
        $this->addSql('DROP TABLE symptom');
        $this->addSql('DROP TABLE treatment');
    }
}
