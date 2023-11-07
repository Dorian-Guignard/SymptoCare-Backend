<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107191134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE treatment_patient (treatment_id INT NOT NULL, patient_id INT NOT NULL, INDEX IDX_621C1527471C0366 (treatment_id), INDEX IDX_621C15276B899279 (patient_id), PRIMARY KEY(treatment_id, patient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE treatment_patient ADD CONSTRAINT FK_621C1527471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE treatment_patient ADD CONSTRAINT FK_621C15276B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_treatment DROP FOREIGN KEY FK_3E8AEE0E6B899279');
        $this->addSql('ALTER TABLE patient_treatment DROP FOREIGN KEY FK_3E8AEE0E471C0366');
        $this->addSql('DROP TABLE patient_treatment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_treatment (patient_id INT NOT NULL, treatment_id INT NOT NULL, INDEX IDX_3E8AEE0E471C0366 (treatment_id), INDEX IDX_3E8AEE0E6B899279 (patient_id), PRIMARY KEY(patient_id, treatment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE patient_treatment ADD CONSTRAINT FK_3E8AEE0E6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_treatment ADD CONSTRAINT FK_3E8AEE0E471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE treatment_patient DROP FOREIGN KEY FK_621C1527471C0366');
        $this->addSql('ALTER TABLE treatment_patient DROP FOREIGN KEY FK_621C15276B899279');
        $this->addSql('DROP TABLE treatment_patient');
    }
}
