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

    public function init_hooks(){
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_action_project_new', array($this, 'admin_action_project_new'));

    }

    public function admin_menu(){
       // add_options_page('KmtProject', 'Kemet Projects', 'manage_options', 'kmtproject', array($this, 'config_page'));
        add_menu_page('KmtProjectList', 'Kemet Projects', 'manage_options', 'kmtproject', array($this, 'kmtproject_action_list'), 'dashicons-admin-generic');
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
        $commissions_table_name = $wpdb->prefix . 'kemet_projects';

        $result = $wpdb->get_results("SELECT * FROM $commissions_table_name");

        ?>

        <div class="wrap">
            <h1>Kemet Projects</h1>
            <a class="button button-primary" href="admin.php?page=admin_action_project_new">Ajouter un projet</a>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="manage-column column-title column-primary">Title</th>
                        <th class="manage-column column-title">Description</th>
                        <th class="manage-column column-title">Localisation</th>
                        <th class="manage-column column-title">Image 1</th>
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
                            <td><?= $row->img1 ?></td>
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

    <form action="option.php">
        <?php settings_fields('kemet_projects_options'); ?>
        <?php do_settings_sections('kemet_projects'); ?>
        <?php submit_button(); ?>
    </form>

    <?php

        
    }
}