<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171220205101 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rental (id INT AUTO_INCREMENT NOT NULL, advert_id INT NOT NULL, renter_id INT NOT NULL, beginDate DATETIME NOT NULL, endDate DATETIME NOT NULL, status VARCHAR(25) NOT NULL, INDEX IDX_1619C27DD07ECCB6 (advert_id), INDEX IDX_1619C27DE289A545 (renter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billing (id INT AUTO_INCREMENT NOT NULL, Currency VARCHAR(10) NOT NULL, Price INT NOT NULL, TimeBase VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, language VARCHAR(10) NOT NULL, username VARCHAR(50) NOT NULL, password VARCHAR(64) NOT NULL, phone VARCHAR(15) NOT NULL, mail VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_70E4FA78F85E0677 (username), UNIQUE INDEX UNIQ_70E4FA78444F97DD (phone), UNIQUE INDEX UNIQ_70E4FA785126AC48 (mail), INDEX IDX_70E4FA7864D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(50) NOT NULL, state VARCHAR(50) DEFAULT NULL, town VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, brand VARCHAR(100) NOT NULL, model VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, sits INT NOT NULL, fuel VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advert (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, location_id INT NOT NULL, billing_id INT NOT NULL, owner_id INT NOT NULL, beginDate DATETIME NOT NULL, endDate DATETIME NOT NULL, title VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_54F1F40BC3C6F69F (car_id), INDEX IDX_54F1F40B64D218E (location_id), UNIQUE INDEX UNIQ_54F1F40B3B025C87 (billing_id), INDEX IDX_54F1F40B7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27DD07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27DE289A545 FOREIGN KEY (renter_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA7864D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40BC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40B64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40B3B025C87 FOREIGN KEY (billing_id) REFERENCES billing (id)');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES member (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40B3B025C87');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27DE289A545');
        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40B7E3C61F9');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA7864D218E');
        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40B64D218E');
        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40BC3C6F69F');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27DD07ECCB6');
        $this->addSql('DROP TABLE rental');
        $this->addSql('DROP TABLE billing');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE advert');
    }
}
