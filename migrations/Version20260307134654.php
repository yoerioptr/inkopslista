<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260307134654 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return 'Create basket, basket_item and product tables, and add created column to user table';
    }

    #[\Override]
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE basket (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE basket_item (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, unit VARCHAR(255) NOT NULL, weight INT NOT NULL, created DATETIME NOT NULL, in_cart TINYINT NOT NULL, product_id INT NOT NULL, basket_id INT NOT NULL, INDEX IDX_D4943C2B4584665A (product_id), INDEX IDX_D4943C2B1BE1FB52 (basket_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2B1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE user ADD created DATETIME NOT NULL');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE basket_item DROP FOREIGN KEY FK_D4943C2B4584665A');
        $this->addSql('ALTER TABLE basket_item DROP FOREIGN KEY FK_D4943C2B1BE1FB52');
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE basket_item');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE user DROP created');
    }
}
