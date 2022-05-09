<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220423132650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_relations DROP FOREIGN KEY FK_148C329CA76ED395');
        $this->addSql('ALTER TABLE user_relations DROP FOREIGN KEY FK_148C329C6A5458E8');
        $this->addSql('DROP INDEX IDX_148C329C6A5458E8 ON user_relations');
        $this->addSql('DROP INDEX IDX_148C329CA76ED395 ON user_relations');
        $this->addSql('ALTER TABLE user_relations CHANGE user_id user_id INT NOT NULL, CHANGE friend_id friend_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_relations CHANGE user_id user_id INT DEFAULT NULL, CHANGE friend_id friend_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_relations ADD CONSTRAINT FK_148C329CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_relations ADD CONSTRAINT FK_148C329C6A5458E8 FOREIGN KEY (friend_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_148C329C6A5458E8 ON user_relations (friend_id)');
        $this->addSql('CREATE INDEX IDX_148C329CA76ED395 ON user_relations (user_id)');
    }
}
