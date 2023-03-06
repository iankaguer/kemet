<?php

namespace App\Controller;

use App\KmtProjectPlugin;

class AdminController{

    const REDIRECT_LIST_PAGE = 'admin.php?page=kemet_projects_list';
    const REDIRECT_LiST_DETAILLED = 'admin.php?page=admin_action_project_detaille';
    const REDIRECT_EDIT_PAGE = 'admin.php?page=kemet_projects_edit';

    public function __construct(){
        $this->init_hooks();
    }

    public function init_hooks(): void
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
	 //   add_action('admin_action_project_new', array($this, 'admin_action_project_new'));
	    
	    //add_shortcode('kemet_realisation', 'kemet_rea'); admin_action_add_image_project_detaille

    }
    
  

    public function admin_menu(){
       // add_options_page('KmtProject', 'Kemet Projects', 'manage_options', 'kmtproject', array($this, 'config_page'));
        add_menu_page('KmtProjectList', 'Kemet Projects', 'manage_options', 'kmtproject', array($this, 'kmtproject_action_list'), 'dashicons-admin-generic');
        //add a new link to page
	
	    add_submenu_page('kmtproject', 'Add new project', 'New Project', 'manage_options', 'kmtproject_new', array($this, 'admin_action_project_new'));
	    add_submenu_page('kmtproject', 'Detailled projects', 'Detailled projects', 'manage_options', 'admin_action_project_detaille', array($this, 'admin_action_project_detaille'));
	    add_submenu_page('kmtproject', 'Detailled projects', 'New Detailled projects', 'manage_options', 'admin_action_new_project_detaille', array($this, 'admin_action_new_project_detaille'));
	    add_submenu_page( '', 'add_image_project_detaille', 'add_image_project_detaille', 'manage_options', 'add_image_pd', array($this, 'add_image_pd') );
	
	    /*add_submenu_page( 'dev7d-father','Daktarai','Daktarai', 'manage_options' , 'dev7d-sub-doctors', 'doctors_options');
	    add_submenu_page( 'dev7d-sub-doctors','Daktarų redagavimas','Daktarų redagavimas', 'manage_options' , 'dev7d-sub-doctors-edit', 'doctors_edit');*/
    }

    public function config_page(){
        KmtProjectPlugin::render('config');
    }

    public function admin_init(){
        register_setting('kmtproject_options', 'kmtproject_options');
        add_settings_section('kmtproject_section', null, null, 'kmtproject_action');
        
        add_settings_field('redirect_to', 'Kemet Projects', array($this, 'redirect_render'), 'kmtproject', 'kmtproject_section');
	    add_settings_field('admin_action_project_new', 'Add a Project', array($this, 'admin_action_project_new'), 'admin_action_project_new', '');
	    add_settings_field('admin_action_project_detaille', 'Detailed Projects', array($this, 'admin_action_project_detaille'), 'admin_action_project_detaille', '');
	    add_settings_field('admin_action_new_project_detaille', 'New Detailed Projects', array($this, 'admin_action_new_project_detaille'), 'admin_action_new_project_detaille', '');
	    add_settings_field(  'add_image_project_detaille', 'add_image_project_detaille', 'add_image_project_detaille', 'add_image_project_detaille', 'add_image_project_detaille' );
    }
    
 

    public function redirect_render( $redirect_to, $request, $user ){
        $general_options = get_option('kmtproject_options', [
			'redirect_to' => 0
		]);

		$selectedValue = $general_options['redirect_to'];

		?>
		<!--select-- name="kmtproject_options[redirect_to]">
			<option value="<?= self::REDIRECT_LIST_PAGE ?>" <?= selected(self::REDIRECT_LIST_PAGE, $selectedValue) ?>>Vers la liste des articles</option>
			<option  value="<?= self::REDIRECT_EDIT_PAGE ?>" <?= selected(self::REDIRECT_EDIT_PAGE, $selectedValue) ?>>Vers l'écran de modification de l'article dupliqué</option>
		</select-->
		<?php


    }
   
    public function kmtproject_action_list(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'kemet_projects';
        
        if (isset($_GET['delete'])) {
            $wpdb->query("DELETE FROM $table_name WHERE id = " . $_GET['delete']);
            wp_redirect(self::REDIRECT_LIST_PAGE);
        }

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
                                <!--a href="admin.php?page=kemet_projects_edit&id=<?= $row->id ?>">Edit</a-->
                                <a href="admin.php?page=kmtproject&delete=<?= $row->id ?>">Delete</a>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>


       <?php
    }
	
	
	
	public function admin_action_project_detaille(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'kemet_projects_detailles';
        $table_name_2 = $wpdb->prefix . 'kemet_projects_detailles_images';
		
		if (isset($_GET['delete'])) {
			$wpdb->query("DELETE FROM $table_name_2 WHERE id_project = " . $_GET['delete']);
			$wpdb->query("DELETE FROM $table_name WHERE id = " . $_GET['delete']);
			wp_redirect(self::REDIRECT_LiST_DETAILLED);
		}
		
		$result = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        $resultats = [];
        foreach ($result as $row) {
	        $resultImage  = $wpdb->get_results( "SELECT * FROM $table_name_2 WHERE id_project  = " . $row['id'], ARRAY_A );
	        $row['image'] = $resultImage;
	        $resultats[]  = $row;
        }
		?>
        

        <div class="wrap">
            <h1>Kemet Projects</h1>
            <a class="button button-primary" href="admin.php?page=admin_action_new_project_detaille">Ajouter un projet</a>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="manage-column column-title column-primary">Title</th>
                        <th class="manage-column column-title">Statut</th>
                        <th class="manage-column column-title">Date</th>
                        <th class="manage-column column-title">Site</th>
                        <th class="manage-column column-title">Taille</th>
                        <th class="manage-column column-title">Client</th>
                        <th class='manage-column column-title'>Collaborateurs</th>
                        <th class='manage-column column-title'>Images</th>
                        <th class="manage-column column-title"></th>
                    </tr>
                </thead>
                <tbody>
				<?php foreach($resultats as $resultat): ?>
                    <tr>
                        <td><?= $resultat['title'] ?></td>
                        <td><?= $resultat['status'] ?></td>
                        <td><?= $resultat['date'] ?></td>
                        <td><?= $resultat['site'] ?></td>
                        <td><?= $resultat['taille'] ?></td>
                        <td><?= $resultat['client'] ?></td>
                        <td><?= $resultat['collaborateurs'] ?></td>
                      
                        <td>
                            <img src="<?= $resultat['image'][0]['img'] ?>" style='width: 100px; height: auto;'/>
                            
                        </td>
                        <td>
                            <a class="button" href="admin.php?page=add_image_pd&id=<?= $resultat['id'] ?>">Join images</a>
                            <a class='button' href="admin.php?page=admin_action_project_detaille&delete=<?= $resultat['id'] ?>">Delete</a>
                            
                        </td>
                    </tr>
                  
				<?php endforeach; ?>
                </tbody>
            </table>

        </div>
		
		
		<?php
	}
	
	function add_image_pd(){
		//get url var
		$id = $_GET['id'];
		?>
        <style>
            
            .form-group input {
                width: 40%;
            }

            .form-group {
                margin: 5px;
                display: flex;
            }

            .form-group label {
                margin: 5px;
                text-transform: capitalize;
            }
        </style>

        <form action='' enctype='multipart/form-data' method='post'>
            <div class='wrap'>
                <h1>Kemet Projects</h1>
                <input type='hidden' name='project_id' id='project_id'
                       value="<?php echo $id; ?>"/>
                <input type='hidden' name='form_type' value='ajout'
                <div class='form-group'>
                    <label for='img1'>image 1</label>
                    <input type='file' class='form-control' id='img1' name='img1' placeholder='img1' required>
					<?php wp_nonce_field( plugin_basename( __FILE__ ), 'img1' ); ?>
                </div>
                <div class="form-group">
                    <label for="img2">image 2</label>
                    <input type="file" class="form-control" id="img2" name="img2" placeholder="img2" >
					<?php wp_nonce_field( plugin_basename( __FILE__ ), 'img2' ); ?>
                </div>
                <div class="form-group">
                    <label for="img3">image 3</label>
                    <input type="file" class="form-control" id="img3" name="img3" placeholder="img3" >
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
			//die(json_encode($_POST));
			global $wpdb;
			$table_name2 = $wpdb->prefix .'kemet_projects_detailles_images';
			
			$project_id = $_POST['project_id'];
			
			$img1_file = $_FILES['img1'];
			$img2_file = $_FILES['img2'];
			$img3_file = $_FILES['img3'];
			$img4_file = $_FILES['img4'];
			$img5_file = $_FILES['img5'];
			
			//upload images
			$img1_url = wp_upload_bits($img1_file['name'], null, file_get_contents($img1_file['tmp_name']))['url'];
			$img4_url = ($img4_file['name'] !== '' && $img4_file !== null) ? wp_upload_bits($img4_file['name'], null, file_get_contents($img4_file['tmp_name']))['url'] : '';
			$img5_url = ($img5_file['name'] !== '' && $img5_file !== null) ? wp_upload_bits($img5_file['name'], null, file_get_contents($img5_file['tmp_name']))['url'] : '';
			$img2_url = ($img2_file['name'] !== '' && $img2_file !== null) ? wp_upload_bits($img2_file['name'], null, file_get_contents($img2_file['tmp_name']))['url'] : '';
			$img3_url = ($img3_file['name'] !== '' && $img3_file !== null) ? wp_upload_bits($img3_file['name'], null, file_get_contents($img3_file['tmp_name']))['url'] : '';
			
			
			//add project to db
			
			//create array of images
			$images = array($img1_url, $img2_url, $img3_url, $img4_url, $img5_url);
			
			//add images to db
			foreach ($images as $image){
				if ($image !== ''){
					$wpdb->insert( $table_name2, ['id_project' => $project_id, 'img' => $image]);
				}
			}
			echo "<script>location.replace('admin.php?page=admin_action_project_detaille');</script>";
			
		}
	}

    public function admin_action_new_project_detaille(){
	    
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

                <div style="display: flex;">
                    <div style="flex: 1">
                        <div class='form-group'>
                            <label for='title'>title</label>
                            <input type='text' class='form-control' id='title' name='title' placeholder='project title'>
                            <input type='hidden' name='form_type' value='ajout'>
                        </div>
                        <div class='form-group'>
                            <label for='description'>description</label>
                            <textarea class='form-control' id='description' name='description' rows='3'></textarea>
                        </div>
                        <div class='form-group'>
                            <label for='status'>status</label>
                            <input type='text' class='form-control' id='status' name='status' placeholder='status'>
                        </div>
                        <div class='form-group'>
                            <label for='date'>date</label>
                            <input type='text' class='form-control' id='date' name='date' placeholder='date de debut'>
                        </div>
                        <div class='form-group'>
                            <label for='localisation'>site</label>
                            <input type='text' class='form-control' id='site' name='site' placeholder='ville - pays'>
                        </div>
                        <div class='form-group'>
                            <label for='taille'>taille</label>
                            <input type='text' class='form-control' id='taille' name='taille' placeholder='85m²'>
                        </div>
                        <div class='form-group'>
                            <label for='client'>client</label>
                            <input type='text' class='form-control' id='client' name='client' placeholder='client'>
                        </div>
                        <div class='form-group'>
                            <label for='collaborateurs'>collaborateurs</label>
                            <input type='text' class='form-control' id='collaborateurs' name='collaborateurs'
                                   placeholder='entreprises collaboratrices'>
                        </div>
                    </div>
                    <div style="flex: 1">
                        <div class='form-group'>
                            <label for='img1'>image principale</label>
                            <input type='file' class='form-control' id='img1' name='img1' placeholder='img1' required>
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
                        <div class="form-group">
                            <label for="img6">image 6</label>
                            <input type="file" class="form-control" id="img6" name="img6" placeholder="img6">
		                    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img6' ); ?>
                        </div>
                        <div class="form-group">
                            <label for="img7">image 7</label>
                            <input type="file" class="form-control" id="img7" name="img7" placeholder="img7">
		                    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img7' ); ?>
                        </div>
                        <div class="form-group">
                            <label for="img8">image 8</label>
                            <input type="file" class="form-control" id="img8" name="img8" placeholder="img8">
		                    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img8' ); ?>
                        </div>
                        <div class="form-group">
                            <label for="img9">image 9</label>
                            <input type="file" class="form-control" id="img9" name="img9" placeholder="img9">
		                    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img9' ); ?>
                        </div>
                        <div class="form-group">
                            <label for="img10">image 10</label>
                            <input type="file" class="form-control" id="img10" name="img10" placeholder="img10">
		                    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'img10' ); ?>
                        </div>
 
                    </div>
                    
                </div>
                <button type="submit" class="button button-primary">Enregistrer</button>
            </div>
        </form>
	
	    <?php
	    if (isset($_POST['form_type']) && ($_POST['form_type'] === 'ajout')){
		    global $wpdb;
		    $table_name = $wpdb->prefix .'kemet_projects_detailles';
            $table_name2 = $wpdb->prefix .'kemet_projects_detailles_images';
		
		    $title = $_POST['title'];
		    $description = $_POST['description'];
		    $status = $_POST['status'];
            $date = $_POST['date'];
            $site = $_POST['site'];
            $taille = $_POST['taille'];
            $client = $_POST['client'];
            $collaborateurs = $_POST['collaborateurs'];
            
		    $img1_file = $_FILES['img1'];
		    $img2_file = $_FILES['img2'];
		    $img3_file = $_FILES['img3'];
		    $img4_file = $_FILES['img4'];
		    $img5_file = $_FILES['img5'];
            $img6_file = $_FILES['img6'];
            $img7_file = $_FILES['img7'];
            $img8_file = $_FILES['img8'];
            $img9_file = $_FILES['img9'];
            $img10_file = $_FILES['img10'];
		
		    //upload images
		    $img1_url = wp_upload_bits($img1_file['name'], null, file_get_contents($img1_file['tmp_name']))['url'];
		    $img2_url = wp_upload_bits($img2_file['name'], null, file_get_contents($img2_file['tmp_name']))['url'];
		    $img3_url = wp_upload_bits($img3_file['name'], null, file_get_contents($img3_file['tmp_name']))['url'];
		    $img4_url = ($img4_file['name'] !== '' && $img4_file !== null) ? wp_upload_bits($img4_file['name'], null, file_get_contents($img4_file['tmp_name']))['url'] : '';
		    $img5_url = ($img5_file['name'] !== '' && $img5_file !== null) ? wp_upload_bits($img5_file['name'], null, file_get_contents($img5_file['tmp_name']))['url'] : '';
            $img6_url = ($img6_file['name'] !== '' && $img6_file !== null) ? wp_upload_bits($img6_file['name'], null, file_get_contents($img6_file['tmp_name']))['url'] : '';
            $img7_url = ($img7_file['name'] !== '' && $img7_file !== null) ? wp_upload_bits($img7_file['name'], null, file_get_contents($img7_file['tmp_name']))['url'] : '';
            $img8_url = ($img8_file['name'] !== '' && $img8_file !== null) ? wp_upload_bits($img8_file['name'], null, file_get_contents($img8_file['tmp_name']))['url'] : '';
            $img9_url = ($img9_file['name'] !== '' && $img9_file !== null) ? wp_upload_bits($img9_file['name'], null, file_get_contents($img9_file['tmp_name']))['url'] : '';
            $img10_url = ($img10_file['name'] !== '' && $img10_file !== null) ? wp_upload_bits($img10_file['name'], null, file_get_contents($img10_file['tmp_name']))['url'] : '';
		
		
		
		    //add project to db
            
            //create array of images
            $images = array($img1_url, $img2_url, $img3_url, $img4_url, $img5_url, $img6_url, $img7_url, $img8_url, $img9_url, $img10_url);
		
		
		    $wpdb->query("INSERT INTO $table_name
                        (title, description, status, date, site, taille, client, collaborateurs, created)
                        VALUES
                            ('$title', '$description', '$status', '$date', '$site', '$taille', '$client', '$collaborateurs', NOW())");
            //get id of last inserted project
            $last_id = $wpdb->insert_id;
            //add images to db
            foreach ($images as $image){
                if ($image !== ''){
                    $wpdb->insert( $table_name2, ['id_project' => $last_id, 'img' => $image]);
                }
            }
		    echo "<script>location.replace('admin.php?page=admin_action_project_detaille');</script>";
		
	    }
	
	
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
                <select name="description" id="description" class="form-control">
                    <option value="designinterieur">Design interieur</option>
                    <option value="projet">Projets</option>
                    <option value="montage3d">Montage 3d</option>
                </select>
                
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
    

    
 
}