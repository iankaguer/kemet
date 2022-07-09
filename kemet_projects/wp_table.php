<?php

/**
 * Si inexistante, on créée la table SQL "commissions" après l'activation du thème
 */
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$commissions_table_name = $wpdb->prefix . 'wp_kemet_projects`';

$commissions_sql = "CREATE TABLE IF NOT EXISTS $commissions_table_name (
    `id` int(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(254) NOT NULL,
    `description` text,
    `localisation` varchar(254) DEFAULT NULL,
    `img1` varchar(254) NOT NULL,
    `img2` varchar(254) NOT NULL,
    `img3` varchar(254) DEFAULT NULL,
    `img4` varchar(254) DEFAULT NULL,
    `img5` varchar(254) DEFAULT NULL,
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
   )$charset_collate
";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

dbDelta($commissions_sql);