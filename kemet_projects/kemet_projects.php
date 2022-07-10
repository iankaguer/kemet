<?php
/**
 * @package Kemet_Theme
 * @version 1.0.0
 */
/*
Plugin Name: kemet projects
Plugin URI: https://github.com/iankaguer/kemet
Description: my frist plugin • kemet projects . This is a simple plugin to create a projects table in the database. It also allows you to add, edit, delete and display projects. made 4 Kemet_Studio.
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

function kemet_rea(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'kemet_projects';
	
	$result = $wpdb->get_results("SELECT * FROM $table_name  where description = 'realisation' order by id desc");
	ob_start();
	?>
	
	<style>
        .kemet-project-content{
            display: none;
            flex-direction: column;
            align-items: baseline;
	        bottom: 0;
	        position: absolute;
            width: 100%;
            background-color: #000000a3;
            color: white;
        }
        .kemet-project-wrapper{
            width: 300px;
            aspect-ratio: 4/3;
	        transition: all 0.5s ease-out;
            overflow: hidden;
	        position: relative;
		}
        .kemet-project{
            display: block;
           position: relative;
            width: 100%;
	        height: 100%;
            background-size: cover;
            background-position: center;
            transition: all 0.5s ease-out;
         
        }
		.kemet-project-content h4{
			font-size: 1.1em;
			margin: 0;
			padding: .5em;
			text-transform: uppercase;
		}
        .kemet-project-content p{
            font-size: .9em;
	        margin: 0;
	        padding:0 .5em;
	        color: #DBA80A;
        }
        .kemet-project-wrapper:hover .kemet-project-content,
        .kemet-project-wrapper:focus .kemet-project-content {
            display: flex;
        }
        
        .kemet-project-wrapper:hover .kemet-project,
        .kemet-project-wrapper:focus .kemet-project {
            transform: scale(1.2);
        }
        .kemet-realisation{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
	        gap: 1em;
        }
	
	</style>
	<div class="kemet-realisation">
		<?php  foreach ($result as $project) : ?>
			<div class="kemet-project-wrapper">
				<div class='kemet-project' style="background-image: url(<?php echo $project->img1; ?>)">
				
				</div>
				<div class='kemet-project-content'>
					<h4><?php echo $project->name; ?></h4>
					<p><?php echo $project->localisation; ?></p>
				</div>
			</div>
			
		<?php endforeach; ?>
	</div>
	
	<?php
	return ob_get_clean();
}
function kemet_3d(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'kemet_projects';
	
	$result = $wpdb->get_results("SELECT * FROM $table_name   where description = 'montage3d' order by id desc");
	ob_start();
	?>
	
	<style>
        .kemet-project-content{
            display: none;
            flex-direction: column;
            align-items: baseline;
            bottom: 0;
            position: absolute;
            width: 100%;
            background-color: #000000a3;
            color: white;
        }
        .kemet-project-wrapper{
            width: 300px;
            aspect-ratio: 4/3;
            transition: all 0.5s ease-out;
            overflow: hidden;
            position: relative;
        }
        .kemet-project{
            display: block;
            position: relative;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: all 0.5s ease-out;

        }
        .kemet-project-content h4{
            font-size: 1.1em;
            margin: 0;
            padding: .5em;
            text-transform: uppercase;
        }
        .kemet-project-content p{
            font-size: .9em;
            margin: 0;
            padding:0 .5em;
            color: #DBA80A;
        }
        .kemet-project-wrapper:hover .kemet-project-content,
        .kemet-project-wrapper:focus .kemet-project-content {
            display: flex;
        }

        .kemet-project-wrapper:hover .kemet-project,
        .kemet-project-wrapper:focus .kemet-project {
            transform: scale(1.2);
        }
        .kemet-realisation{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 1em;
        }
	
	</style>
	<div class="kemet-realisation">
		<?php  foreach ($result as $project) : ?>
			<div class="kemet-project-wrapper">
				<div class='kemet-project' style="background-image: url(<?php echo $project->img1; ?>)">
				
				</div>
				<div class='kemet-project-content'>
					<h4><?php echo $project->name; ?></h4>
					<p><?php echo $project->localisation; ?></p>
				</div>
			</div>
		
		<?php endforeach; ?>
	</div>
	
	<?php
	return ob_get_clean();
}
function kemet_design(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'kemet_projects';
	
	$result = $wpdb->get_results("SELECT * FROM $table_name   where description = 'designinterieur' order by id desc");
	ob_start();
	?>
	
	<style>
        .kemet-project-content{
            display: none;
            flex-direction: column;
            align-items: baseline;
            bottom: 0;
            position: absolute;
            width: 100%;
            background-color: #000000a3;
            color: white;
        }
        .kemet-project-wrapper{
            width: 300px;
            aspect-ratio: 4/3;
            transition: all 0.5s ease-out;
            overflow: hidden;
            position: relative;
        }
        .kemet-project{
            display: block;
            position: relative;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: all 0.5s ease-out;

        }
        .kemet-project-content h4{
            font-size: 1.1em;
            margin: 0;
            padding: .5em;
            text-transform: uppercase;
        }
        .kemet-project-content p{
            font-size: .9em;
            margin: 0;
            padding:0 .5em;
            color: #DBA80A;
        }
        .kemet-project-wrapper:hover .kemet-project-content,
        .kemet-project-wrapper:focus .kemet-project-content {
            display: flex;
        }

        .kemet-project-wrapper:hover .kemet-project,
        .kemet-project-wrapper:focus .kemet-project {
            transform: scale(1.2);
        }
        .kemet-realisation{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 1em;
        }
	
	</style>
	<div class="kemet-realisation">
		<?php  foreach ($result as $project) : ?>
			<div class="kemet-project-wrapper">
				<div class='kemet-project' style="background-image: url(<?php echo $project->img1; ?>)">
				
				</div>
				<div class='kemet-project-content'>
					<h4><?php echo $project->name; ?></h4>
					<p><?php echo $project->localisation; ?></p>
				</div>
			</div>
		
		<?php endforeach; ?>
	</div>
	
	<?php
	return ob_get_clean();
}
function kemet_proj(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'kemet_projects';
	
	$result = $wpdb->get_results("SELECT * FROM $table_name  where description = 'projet' order by id desc ");
	ob_start();
	?>
	
	<style>
        .kemet-project-content{
            display: none;
            flex-direction: column;
            align-items: baseline;
            bottom: 0;
            position: absolute;
            width: 100%;
            background-color: #000000a3;
            color: white;
        }
        .kemet-project-wrapper{
            width: 300px;
            aspect-ratio: 4/3;
            transition: all 0.5s ease-out;
            overflow: hidden;
            position: relative;
        }
        .kemet-project{
            display: block;
            position: relative;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: all 0.5s ease-out;

        }
        .kemet-project-content h4{
            font-size: 1.1em;
            margin: 0;
            padding: .5em;
            text-transform: uppercase;
        }
        .kemet-project-content p{
            font-size: .9em;
            margin: 0;
            padding:0 .5em;
            color: #DBA80A;
        }
        .kemet-project-wrapper:hover .kemet-project-content,
        .kemet-project-wrapper:focus .kemet-project-content {
            display: flex;
        }

        .kemet-project-wrapper:hover .kemet-project,
        .kemet-project-wrapper:focus .kemet-project {
            transform: scale(1.2);
        }
        .kemet-realisation{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 1em;
        }
	
	</style>
	<div class="kemet-realisation">
		<?php  foreach ($result as $project) : ?>
			<div class="kemet-project-wrapper">
				<div class='kemet-project' style="background-image: url(<?php echo $project->img1; ?>)">
				
				</div>
				<div class='kemet-project-content'>
					<h4><?php echo $project->name; ?></h4>
					<p><?php echo $project->localisation; ?></p>
				</div>
			</div>
		
		<?php endforeach; ?>
	</div>
	
	<?php
	return ob_get_clean();
}
	
	add_shortcode('kemet_realisation', 'kemet_rea');
	add_shortcode('kemet_3d', 'kemet_3d');
	add_shortcode('kemet_design', 'kemet_design');
	add_shortcode('kemet_projet', 'kemet_proj');