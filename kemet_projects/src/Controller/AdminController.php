<?php

namespace App\Controller;

use App\KmtProjectPlugin;

class AdminController{

    const REDIRECT_LIST_PAGE = 'admin.php?page=kemet_projects_list';
    const REDIRECT_NEW_PAGE = 'admin.php?page=kemet_projects_new';
    const REDIRECT_EDIT_PAGE = 'admin.php?page=kemet_projects_edit';

    public function __construct(){
        $this->init_hooks();
    }

    public function init_hooks(): void
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_action_project_new', array($this, 'admin_action_project_new'));
	    add_shortcode('kemet_realisation', 'kemet_rea');

    }

    public function admin_menu(){
       // add_options_page('KmtProject', 'Kemet Projects', 'manage_options', 'kmtproject', array($this, 'config_page'));
        add_menu_page('KmtProjectList', 'Kemet Projects', 'manage_options', 'kmtproject', array($this, 'kmtproject_action_list'), 'dashicons-admin-generic');
        //add a new link to page
	    add_shortcode('kemet_realisation', 'kemet_rea');
        add_submenu_page('kmtproject', 'Add new project', 'New Project', 'manage_options', 'kmtproject_new', array($this, 'admin_action_project_new'));
    }

    public function config_page(){
        KmtProjectPlugin::render('config');
    }

    public function admin_init(){
        register_setting('kmtproject_options', 'kmtproject_options');
        add_settings_section('kmtproject_section', null, null, 'kmtproject_action');
        
        add_settings_field('redirect_to', 'Kemet Projects', array($this, 'redirect_render'), 'kmtproject', 'kmtproject_section');
        add_settings_field('admin_action_project_new', 'Add a Projecs', array($this, 'admin_action_project_new'), 'admin_action_project_new', '');
    }

    public function redirect_render( $redirect_to, $request, $user ){
        $general_options = get_option('kmtproject_options', [
			'redirect_to' => 0
		]);

		$selectedValue = $general_options['redirect_to'];

		?>
		<select name="kmtproject_options[redirect_to]">
			<option value="<?= self::REDIRECT_LIST_PAGE ?>" <?= selected(self::REDIRECT_LIST_PAGE, $selectedValue) ?>>Vers la liste des articles</option>
			<option value="<?= self::REDIRECT_EDIT_PAGE ?>" <?= selected(self::REDIRECT_EDIT_PAGE, $selectedValue) ?>>Vers l'écran de modification de l'article dupliqué</option>
		</select>
		<?php


    }



    public function kmtproject_action_list(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'kemet_projects';

        $result = $wpdb->get_results("SELECT * FROM $table_name");

        ?>

        <div class="wrap">
            <h1>Kemet Projects</h1>
            <a class="button button-primary" href="admin.php?page=kmtproject_new">Ajouter un projet</a>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="manage-column column-title column-primary">Title</th>
                        <th class="manage-column column-title">Description</th>
                        <th class="manage-column column-title">Localisation</th>
                        <th class="manage-column column-title">Image principale</th>
                        <th class="manage-column column-title">Image 2</th>
                        <th class="manage-column column-title">Image 3</th>
                        <th class="manage-column column-title">Image 4</th>
                        <th class="manage-column column-title">Image 5</th>
                        <th class="manage-column column-title">Date creation</th>
                        <th class="manage-column column-title">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $row): ?>
                        <tr>
                            <td><?= $row->name ?></td>
                            <td><?= $row->description ?></td>
                            <td><?= $row->localisation ?></td>
                            <td><img src="<?= $row->img1 ?>" style="width: 100px; height: auto;" /> </td>
                            <td><?= $row->img2 ?></td>
                            <td><?= $row->img3 ?></td>
                            <td><?= $row->img4 ?></td>
                            <td><?= $row->img5 ?></td>
                            <td><?= $row->created ?></td>
                            <td>
                                <a href="admin.php?page=kemet_projects_edit&id=<?= $row->id ?>">Edit</a>
                                <a href="admin.php?page=kemet_projects_delete&id=<?= $row->id ?>">Delete</a>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>


       <?php
    }

    public function admin_action_project_new(){
        ?>
            <style>
                .form-group input[type="text"], .form-group textarea {
                    border: 1px solid #ccc;
                    padding: 2px;
                    border-radius: 5px;
                }
                .form-group input{
                    width: 40%;
                }
                .form-group{
                    margin: 5px;
                    display: flex;
                }
                .form-group label{
                    margin: 5px;
                    text-transform: capitalize;
                }
            </style>

            <form action="" enctype="multipart/form-data" method="post">
        <div class="wrap">
            <h1>Kemet Projects</h1>
            
            <div class="form-group">
                <label for="title">title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="project title">
                <input type="hidden" name="form_type" value="ajout">
            </div>
            <div class="form-group">
                <label for="description">description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="localisation">localisation</label>
                <input type="text" class="form-control" id="localisation" name="localisation" placeholder="ville - pays">
            </div>
            <div class="form-group">
                <label for="img1">image principale</label>
                <input type="file" class="form-control" id="img1" name="img1" placeholder="img1" required>
	            <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img1' ); ?>
            </div>
            <div class="form-group">
                <label for="img2">image 2</label>
                <input type="file" class="form-control" id="img2" name="img2" placeholder="img2" required>
	            <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img2' ); ?>
            </div>
            <div class="form-group">
                <label for="img3">image 3</label>
                <input type="file" class="form-control" id="img3" name="img3" placeholder="img3" required>
	            <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img3' ); ?>
            </div>
            <div class="form-group">
                <label for="img4">image 4</label>
                <input type="file" class="form-control" id="img4" name="img4" placeholder="img4">
	            <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img4' ); ?>
            </div>
            <div class="form-group">
                <label for="img5">image 5</label>
                <input type="file" class="form-control" id="img5" name="img5" placeholder="img5">
	            <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img5' ); ?>
            </div>
            <button type="submit" class="button button-primary">Enregistrer</button>
        </div>
    </form>

        <?php
            if (isset($_POST['form_type']) && ($_POST['form_type'] === 'ajout')){
                global $wpdb;
	            $table_name = $wpdb->prefix .'kemet_projects';
          
                $title = $_POST['title'];
                $description = $_POST['description'];
                $localisation = $_POST['localisation'];
                $img1_file = $_FILES['img1'];
                $img2_file = $_FILES['img2'];
                $img3_file = $_FILES['img3'];
                $img4_file = $_FILES['img4'];
                $img5_file = $_FILES['img5'];
            
                //upload images
                $img1_url = wp_upload_bits($img1_file['name'], null, file_get_contents($img1_file['tmp_name']))['url'];
                $img2_url = wp_upload_bits($img2_file['name'], null, file_get_contents($img2_file['tmp_name']))['url'];
                $img3_url = wp_upload_bits($img3_file['name'], null, file_get_contents($img3_file['tmp_name']))['url'];
                $img4_url = ($img4_file['name'] !== '' && $img4_file !== null) ? wp_upload_bits($img4_file['name'], null, file_get_contents($img4_file['tmp_name']))['url'] : '';
                $img5_url = ($img5_file['name'] !== '' && $img5_file !== null) ? wp_upload_bits($img5_file['name'], null, file_get_contents($img5_file['tmp_name']))['url'] : '';
                
             
            
                //add project to db
	
	          
	            $wpdb->query("INSERT INTO $table_name
                        (name, description, localisation, img1, img2, img3, img4, img5, created)
                        VALUES
                            ('$title', '$description', '$localisation', '$img1_url', '$img2_url', '$img3_url', '$img4_url', '$img5_url', NOW())");
	           echo "<script>location.replace('admin.php?page=kmtproject');</script>";
            
            }

        
    }
    
    public function kemet_rea(){
	    global $wpdb;
	    $table_name = $wpdb->prefix . 'kemet_projects';
	
	    $result = $wpdb->get_results("SELECT * FROM $table_name");
	    ob_start();
        ?>
        
        <style>
            .kemet-project-content{
                display: flex;
                flex-direction: column;
                align-items: baseline;
            }
            .kemet-realisation{
                display: flex;
            }
            
        </style>
        <div class="kemet-realisation">
        <?php  foreach ($result as $project) : ?>
            <div class="kemet-project">
                <div class="kemet-project-content">
                    <h2><?php echo $project->name; ?></h2>
                    <p><?php echo $project->localisation; ?></p>
                </div>
            </div>
     <?php endforeach; ?>
        </div>
	
    <?php
	    return ob_get_clean();
    }
    
 
}