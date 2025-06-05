<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250605143908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE food (id INT AUTO_INCREMENT NOT NULL, sub_sub_group_id INT NOT NULL, code INT NOT NULL, name VARCHAR(255) NOT NULL, name_sci VARCHAR(255) DEFAULT NULL, energy_kcal DOUBLE PRECISION DEFAULT NULL, proteins DOUBLE PRECISION DEFAULT NULL, carbohydrates DOUBLE PRECISION DEFAULT NULL, lipids DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D43829F753A97028 (sub_sub_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE food_group (id INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9CA1812F77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE food_sub_group (id INT AUTO_INCREMENT NOT NULL, food_group_id INT NOT NULL, code INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_197E4DE7D619FE05 (food_group_id), UNIQUE INDEX UNIQ_197E4DE777153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE food_sub_sub_group (id INT AUTO_INCREMENT NOT NULL, food_sub_group_id INT DEFAULT NULL, code INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A9404A5538866E7F (food_sub_group_id), UNIQUE INDEX UNIQ_A9404A5577153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE food ADD CONSTRAINT FK_D43829F753A97028 FOREIGN KEY (sub_sub_group_id) REFERENCES food_sub_sub_group (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE food_sub_group ADD CONSTRAINT FK_197E4DE7D619FE05 FOREIGN KEY (food_group_id) REFERENCES food_group (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE food_sub_sub_group ADD CONSTRAINT FK_A9404A5538866E7F FOREIGN KEY (food_sub_group_id) REFERENCES food_sub_group (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE food DROP FOREIGN KEY FK_D43829F753A97028
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE food_sub_group DROP FOREIGN KEY FK_197E4DE7D619FE05
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE food_sub_sub_group DROP FOREIGN KEY FK_A9404A5538866E7F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE food
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE food_group
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE food_sub_group
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE food_sub_sub_group
        SQL);
    }
}
