<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214122751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_pathology (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, pathology_id INT DEFAULT NULL, INDEX IDX_7A9CCCAC6B899279 (patient_id), INDEX IDX_7A9CCCACCE86795D (pathology_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE treatment_patient (treatment_id INT NOT NULL, patient_id INT NOT NULL, INDEX IDX_621C1527471C0366 (treatment_id), INDEX IDX_621C15276B899279 (patient_id), PRIMARY KEY(treatment_id, patient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6496B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patient_pathology ADD CONSTRAINT FK_7A9CCCAC6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE patient_pathology ADD CONSTRAINT FK_7A9CCCACCE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');
        $this->addSql('ALTER TABLE treatment_patient ADD CONSTRAINT FK_621C1527471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE treatment_patient ADD CONSTRAINT FK_621C15276B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_treatment DROP FOREIGN KEY FK_5154880B131BF210');
        $this->addSql('ALTER TABLE patient_treatment DROP FOREIGN KEY FK_5154880B6B899279');
        $this->addSql('DROP TABLE patient_treatment');
        $this->addSql('ALTER TABLE clinic CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE constant CHANGE value value NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE doctor CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD user_id INT DEFAULT NULL, DROP email, DROP password');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EBA76ED395 ON patient (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA76ED395');
        $this->addSql('CREATE TABLE patient_treatment (patient_id INT NOT NULL, treatment_id INT NOT NULL, INDEX IDX_5154880B6B899279 (patient_id), INDEX IDX_5154880B131BF210 (treatment_id), PRIMARY KEY(patient_id, treatment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE patient_treatment ADD CONSTRAINT FK_5154880B131BF210 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_treatment ADD CONSTRAINT FK_5154880B6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_pathology DROP FOREIGN KEY FK_7A9CCCAC6B899279');
        $this->addSql('ALTER TABLE patient_pathology DROP FOREIGN KEY FK_7A9CCCACCE86795D');
        $this->addSql('ALTER TABLE treatment_patient DROP FOREIGN KEY FK_621C1527471C0366');
        $this->addSql('ALTER TABLE treatment_patient DROP FOREIGN KEY FK_621C15276B899279');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496B899279');
        $this->addSql('DROP TABLE patient_pathology');
        $this->addSql('DROP TABLE treatment_patient');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE clinic CHANGE phone_number phone_number INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_1ADAD7EBA76ED395 ON patient');
        $this->addSql('ALTER TABLE patient ADD email VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE doctor CHANGE phone_number phone_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE constant CHANGE value value NUMERIC(10, 0) DEFAULT NULL');
    }
}
