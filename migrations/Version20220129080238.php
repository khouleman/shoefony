<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129080238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sto_brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sto_color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sto_product_color (sto_product_id INT NOT NULL, sto_color_id INT NOT NULL, INDEX IDX_5AC117F62A762DFC (sto_product_id), INDEX IDX_5AC117F6F405A59C (sto_color_id), PRIMARY KEY(sto_product_id, sto_color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sto_product_color ADD CONSTRAINT FK_5AC117F62A762DFC FOREIGN KEY (sto_product_id) REFERENCES sto_product (id)');
        $this->addSql('ALTER TABLE sto_product_color ADD CONSTRAINT FK_5AC117F6F405A59C FOREIGN KEY (sto_color_id) REFERENCES sto_color (id)');
        $this->addSql('ALTER TABLE sto_product ADD sto_image_id INT NOT NULL, ADD sto_brand_id INT NOT NULL, ADD slug VARCHAR(255) NOT NULL, ADD long_description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE sto_product ADD CONSTRAINT FK_B21FD430B37A9F44 FOREIGN KEY (sto_image_id) REFERENCES sto_image (id)');
        $this->addSql('ALTER TABLE sto_product ADD CONSTRAINT FK_B21FD430CA2A6A21 FOREIGN KEY (sto_brand_id) REFERENCES sto_brand (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B21FD430B37A9F44 ON sto_product (sto_image_id)');
        $this->addSql('CREATE INDEX IDX_B21FD430CA2A6A21 ON sto_product (sto_brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sto_product DROP FOREIGN KEY FK_B21FD430CA2A6A21');
        $this->addSql('ALTER TABLE sto_product_color DROP FOREIGN KEY FK_5AC117F6F405A59C');
        $this->addSql('DROP TABLE sto_brand');
        $this->addSql('DROP TABLE sto_color');
        $this->addSql('DROP TABLE sto_product_color');
        $this->addSql('ALTER TABLE sto_product DROP FOREIGN KEY FK_B21FD430B37A9F44');
        $this->addSql('DROP INDEX UNIQ_B21FD430B37A9F44 ON sto_product');
        $this->addSql('DROP INDEX IDX_B21FD430CA2A6A21 ON sto_product');
        $this->addSql('ALTER TABLE sto_product DROP sto_image_id, DROP sto_brand_id, DROP slug, DROP long_description');
    }
}
