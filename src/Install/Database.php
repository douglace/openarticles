<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Install;

class Database
{
    /**
     * @return array
     */
    public static function installQueries(): array
    {
        $queries = [];

        $queries[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'open_articles` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            `position` int(11) NOT NULL DEFAULT 0,
            `active` int(11) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

        $queries[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'open_articles_lang` (
            `open_article_id` int(11) NOT NULL,
            `lang_id` int(11) NOT NULL,
            `title` VARCHAR(250) NULL,
            `resume` TINYTEXT NULL,
            `description` TEXT NULL,
            PRIMARY KEY (`open_article_id`, `lang_id`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


        return $queries;
    }

    /**
     * @return array
     */
    public static function uninstallQueries(): array
    {
        $queries = [];

        $queries[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'open_articles`';
        $queries[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'open_articles_lang`';

        return $queries;
    }

}