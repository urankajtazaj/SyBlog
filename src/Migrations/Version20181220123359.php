<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181220123359 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_votes ADD post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_votes ADD CONSTRAINT FK_F811E23E4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_F811E23E4B89032C ON comment_votes (post_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_votes DROP FOREIGN KEY FK_F811E23E4B89032C');
        $this->addSql('DROP INDEX IDX_F811E23E4B89032C ON comment_votes');
        $this->addSql('ALTER TABLE comment_votes DROP post_id');
    }
}
