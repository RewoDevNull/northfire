<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170410221121 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `member_stream`");
    }

    /**
     * TODO switch triggered_by type from varchar to int (or keep varchar if id will be a uuid?) when the custom metadata enricher will have a working token storage
     *
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `member_stream` (
              `event_id` CHAR(36) COLLATE utf8_unicode_ci NOT NULL,
              `version` INT(11) UNSIGNED NOT NULL,
              `event_name` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL,
              `payload` text COLLATE utf8_unicode_ci NOT NULL,
              `created_at` CHAR(26) COLLATE utf8_unicode_ci NOT NULL,
              `aggregate_id` CHAR(36) COLLATE utf8_unicode_ci NOT NULL,
              `aggregate_type` VARCHAR(150) COLLATE utf8_unicode_ci NOT NULL,
              `triggered_by` VARCHAR(150) COLLATE utf8_unicode_ci NOT NULL,
              PRIMARY KEY (`event_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
        );
    }
}
