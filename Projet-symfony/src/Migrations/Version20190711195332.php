<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190711195332 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employer ADD idservice_id INT NOT NULL');
        $this->addSql('ALTER TABLE employer ADD CONSTRAINT FK_DE4CF06620F5B4DC FOREIGN KEY (idservice_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_DE4CF06620F5B4DC ON employer (idservice_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employer DROP FOREIGN KEY FK_DE4CF06620F5B4DC');
        $this->addSql('DROP INDEX IDX_DE4CF06620F5B4DC ON employer');
        $this->addSql('ALTER TABLE employer DROP idservice_id');
    }
}
