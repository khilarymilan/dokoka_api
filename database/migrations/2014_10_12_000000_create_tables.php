<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TABLE `stores` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `active` tinyint(1) DEFAULT '1',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
        );

        DB::unprepared(
            "CREATE TABLE `categories` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
        );

        DB::unprepared(
            "CREATE TABLE `branches` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `store_id` int(11) DEFAULT NULL,
              `latitude` float DEFAULT NULL,
              `longitude` float DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `index_branches_on_store_id` (`store_id`),
              CONSTRAINT `fk_rails_e0bc8394fe` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
        );

        DB::unprepared(
            "CREATE TABLE `users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
              `encrypted_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
              `reset_password_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `reset_password_sent_at` datetime DEFAULT NULL,
              `remember_created_at` datetime DEFAULT NULL,
              `sign_in_count` int(11) NOT NULL DEFAULT '0',
              `current_sign_in_at` datetime DEFAULT NULL,
              `last_sign_in_at` datetime DEFAULT NULL,
              `current_sign_in_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `last_sign_in_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `created_at` datetime NOT NULL,
              `updated_at` datetime NOT NULL,
              `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `account_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `branch_id` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `index_users_on_email` (`email`),
              UNIQUE KEY `index_users_on_reset_password_token` (`reset_password_token`),
              KEY `index_users_on_branch_id` (`branch_id`),
              CONSTRAINT `fk_rails_bda81e12f5` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
        );

        DB::unprepared(
            "CREATE TABLE `products` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `price` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `details` text COLLATE utf8_unicode_ci,
              `image` blob,
              `available` tinyint(1) DEFAULT '1',
              `category_id` int(11) DEFAULT NULL,
              `branch_id` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `index_products_on_category_id` (`category_id`),
              KEY `index_products_on_branch_id` (`branch_id`),
              CONSTRAINT `fk_rails_cf6b956e9c` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
              CONSTRAINT `fk_rails_fb915499a4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
