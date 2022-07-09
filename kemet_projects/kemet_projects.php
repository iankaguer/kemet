<?php
/**
 * @package Kemet_Theme
 * @version 1.0.0
 */
/*
Plugin Name: kemet projects
Plugin URI: https://github.com/iankaguer/kemet
Description: my frist plugin
Author: ian kaguer
Version: 1.0
Author URI: "https://github.com/iankaguer"
*/

use App\KmtProjectPlugin;


 if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

$plugin = new KmtProjectPlugin(__FILE__ );