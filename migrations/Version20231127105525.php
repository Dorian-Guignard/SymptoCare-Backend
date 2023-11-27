<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127105525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY patient_ibfk_2');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY patient_ibfk_1');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBCE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBCE86795D');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA76ED395');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT patient_ibfk_2 FOREIGN KEY (pathology_id) REFERENCES pathology (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT patient_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
