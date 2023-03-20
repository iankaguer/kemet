<?php

namespace App\Controller;

use App\KmtProjectPlugin;

class AdminController
{

    const REDIRECT_LIST_PAGE = 'admin.php?page=kemet_projects_list';
    const REDIRECT_LiST_DETAILLED = 'admin.php?page=admin_action_project_detaille';
    const REDIRECT_EDIT_PAGE = 'admin.php?page=kemet_projects_edit';

    public function __construct()
    {
        $this->init_hooks();
    }

    public function init_hooks(): void
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        //   add_action('admin_action_project_new', array($this, 'admin_action_project_new'));

        //add_shortcode('kemet_realisation', 'kemet_rea'); admin_action_add_image_project_detaille

    }


    public function admin_menu(): void
    {
        // add_options_page('KmtProject', 'Kemet Projects', 'manage_options', 'kmtproject', array($this, 'config_page'));
        add_menu_page(
            'KmtProjectList',
            'Kemet Projects',
            'manage_options',
            'kmtproject',
            array($this, 'kmtproject_action_list'),
            'dashicons-admin-generic'
        );
        //add a new link to page

        add_submenu_page(
            'kmtproject',
            'Add new project',
            'New Project',
            'manage_options',
            'kmtproject_new',
            array($this, 'admin_action_project_new')
        );
        add_submenu_page(
            'kmtproject',
            'Project groups',
            'Project groups',
            'manage_options',
            'admin_action_project_groups',
            array($this, 'admin_action_project_groups')
        );
        add_submenu_page(
            'kmtproject',
            'Detailled projects',
            'New project\'s group',
            'manage_options',
            'admin_action_new_project_group',
            array($this, 'admin_action_new_project_group')
        );
        add_submenu_page(
            'kmtproject',
            'Project menu',
            'Project menu',
            'manage_options',
            'admin_action_project_menu',
            array($this, 'admin_action_project_menu')
        );
        add_submenu_page(
            'kmtproject',
            'project menu',
            'New project\'s menu',
            'manage_options',
            'admin_action_project_menu_new',
            array($this, 'admin_action_project_menu_new')
        );
    }

    public function config_page(): void
    {
        KmtProjectPlugin::render('config');
    }

    public function admin_init(): void
    {
        register_setting('kmtproject_options', 'kmtproject_options');
        add_settings_section('kmtproject_section', null, null, 'kmtproject_action');

        add_settings_field('redirect_to', 'Kemet Projects', array($this, 'redirect_render'), 'kmtproject', 'kmtproject_section');
        add_settings_field('admin_action_project_new', 'Add a Project', array($this, 'admin_action_project_new'), 'admin_action_project_new', '');
        add_settings_field('admin_action_project_detaille', 'Detailed Projects', array($this, 'admin_action_project_detaille'), 'admin_action_project_detaille', '');
        add_settings_field('admin_action_new_project_group', 'New Detailed Projects', array($this, 'admin_action_new_project_group'), 'admin_action_new_project_group', '');
        add_settings_field('add_image_project_detaille', 'add_image_project_detaille', 'add_image_project_detaille', 'add_image_project_detaille', 'add_image_project_detaille');
    }


    public function redirect_render(): void
    {
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

    public function kmtproject_action_list(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kemet_projects';
        $table_name1 = $wpdb->prefix . 'kemet_projects_groups';

        if (isset($_GET['delete'])) {
            $wpdb->query("DELETE FROM $table_name WHERE id = " . $_GET['delete']);
            wp_redirect(self::REDIRECT_LIST_PAGE);
        }

        $result = $wpdb->get_results("SELECT t1.*, t2.title 
                FROM $table_name t1
                INNER JOIN $table_name1 t2 ON t1.group_id = t2.id
                ");

        ?>

        <div class="wrap">
            <h1>Kemet Projects</h1>
            <a class="button button-primary" href="admin.php?page=kmtproject_new">Ajouter un projet</a>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th class="manage-column column-title column-primary">Title</th>
                    <th class="manage-column column-title">Description</th>
                    <th class="manage-column column-title">Main Image</th>
                    <th class="manage-column column-title">Other images</th>
                    <th class="manage-column column-title">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td>
                            <div>Name : <strong><?= $row->name ?></strong></div>
                            <div>Location : <strong><?= $row->localisation ?></strong></div>
                            <div>Start Date : <strong><?= $row->date ?></strong></div>
                            <div>Client : <strong><?= $row->client ?></strong></div>
                            <div>Size : <strong><?= $row->taille ?></strong></div>
                            <div>Group : <strong><?= $row->title ?></strong></div>
                            <div>Created : <strong><?= $row->created ?></strong></div>

                        </td>
                        <td><?= $row->description ?></td>
                        <td><img src="<?= $row->img1 ?>" style="width: 100px; height: auto;" alt=""/></td>
                        <td>
                            <img src="<?= $row->img2 ?>" style='width: 50px; height: auto;' alt=""/>
                            <img src="<?= $row->img3 ?>" style='width: 50px; height: auto;' alt=""/>
                            <img src="<?= $row->img4 ?>" style='width: 50px; height: auto;' alt=""/>
                            <img src="<?= $row->img5 ?>" style='width: 50px; height: auto;' alt=""/>
                            <img src="<?= $row->img6 ?>" style='width: 50px; height: auto;' alt=""/>
                            <img src="<?= $row->img7 ?>" style='width: 50px; height: auto;' alt=""/>
                        </td>

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

    public function admin_action_project_menu(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kemet_projects_categories';

        if (isset($_GET['delete'])) {
            $wpdb->query("DELETE FROM $table_name WHERE id = " . $_GET['delete']);
            wp_redirect(self::REDIRECT_LiST_DETAILLED);
        }

        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        ?>

        <div class="wrap">
            <div style="display: flex; justify-content: space-between">
                <h1>Kemet Projects</h1>
                <a class='button button-primary' href='admin.php?page=admin_action_project_menu_new'>Add a menu</a>
            </div>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th class="manage-column column-title column-primary">Title</th>
                    <th class="manage-column column-title">short code</th>
                    <th class="manage-column column-title">Création</th>
                    <th class="manage-column column-title"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $resultat): ?>
                    <tr>
                        <td><?= $resultat['title'] ?></td>
                        <td>[kemet_projects title='<?= $resultat['short_code'] ?>']</td>
                        <td><?= $resultat['created'] ?></td>
                        <td>
                            <a class='button' href="admin.php?page=admin_action_project_detaille&delete=<?= $resultat['id'] ?>">Delete</a>
                        </td>
                    </tr>

                <?php endforeach; ?>
                </tbody>
            </table>

        </div>


        <?php
    }

    public function admin_action_project_groups(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kemet_projects_groups';
        $table_name2 = $wpdb->prefix . 'kemet_projects_categories';

        if (isset($_GET['delete'])) {
            $wpdb->query("DELETE FROM $table_name WHERE id = " . $_GET['delete']);
            wp_redirect(self::REDIRECT_LiST_DETAILLED);
        }

        $results = $wpdb->get_results("SELECT t1.*, t2.title as menu 
                FROM $table_name t1
                INNER JOIN $table_name2 t2 ON t1.menu_id = t2.id
                ", ARRAY_A);
        ?>

        <div class="wrap">
            <h1>Kemet Projects</h1>
            <a class="button button-primary" href="admin.php?page=admin_action_new_project_group">Ajouter un groupe</a>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th class="manage-column column-title column-primary">Title</th>
                    <th class="manage-column column-title">Description</th>
                    <th class='manage-column column-title'>Menu</th>
                    <th class='manage-column column-title'>Couverture</th>

                    <th class="manage-column column-title">Création</th>
                    <th class="manage-column column-title"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $resultat): ?>
                    <tr>
                        <td><?= $resultat['title'] ?></td>
                        <td><?= $resultat['description'] ?></td>
                        <td><?= $resultat['menu'] ?></td>
                        <td><img src="<?= $resultat['cover'] ?>" style='width: 100px; height: auto;' alt=""/></td>
                        <td><?= $resultat['created'] ?></td>
                        <td>
                            <a class='button' href="admin.php?page=admin_action_project_detaille&delete=<?= $resultat['id'] ?>">Delete</a>
                        </td>
                    </tr>

                <?php endforeach; ?>
                </tbody>
            </table>

        </div>


        <?php
    }


    public function admin_action_project_menu_new()
    {

        ?>
        <style>
            .form-group input[type="text"], .form-group textarea {
                border: 1px solid #ccc;
                padding: 2px;
                border-radius: 5px;
            }

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

        <form action="" method="post">
            <div class="wrap">
                <h1>Kemet Projects</h1>

                <div style="display: flex;">
                    <div style="flex: 1">
                        <div class='form-group'>
                            <label for='title'>Menu title </label>
                            <input type='text' class='form-control' id='title' name='title' placeholder='menu title'>
                            <input type='hidden' name='form_type' value='ajout'>
                        </div>
                    </div>

                </div>
                <button type="submit" class="button button-primary">Enregistrer</button>
            </div>
        </form>

        <?php
        if (isset($_POST['form_type']) && ($_POST['form_type'] === 'ajout')) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'kemet_projects_categories';

            $title = $_POST['title'];

            //remove special characters, spaces, replace accent by simple letter and make lowercase
            $shortCode = strtolower(str_replace(' ', '_', preg_replace('/[^A-Za-z0-9\-]/', '', remove_accents($title))));


            $wpdb->query("INSERT INTO $table_name
                        (title, short_code, created)
                        VALUES
                            ('$title', '$shortCode', NOW())");


            echo "<script>location.replace('admin.php?page=admin_action_project_menu');</script>";

        }


    }

    public function admin_action_project_new()
    {
        global $wpdb;
        $table_name1 = $wpdb->prefix . 'kemet_projects_groups';

        $results = $wpdb->get_results("SELECT * FROM $table_name1", ARRAY_A);
        ?>
        <style>
            .form-group input[type="text"], .form-group textarea {
                border: 1px solid #ccc;
                padding: 2px;
                border-radius: 5px;
            }

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

            .gr-6 {
                width: 50%;
            }

            .form-control-img {
                justify-content: space-between;
                padding: 5px;
            }
        </style>

        <form action="" enctype="multipart/form-data" method="post">
            <div class="wrap">
                <h1>Kemet Projects</h1>

                <div class="wrap" style="display: flex; flex-direction: row;">
                    <div class='gr-6'>
                        <div class='form-group'>
                            <label for='name'>name</label>
                            <input type='text' class='form-control' id='name' name='name' placeholder="project's name">
                            <input type='hidden' name='form_type' value='ajout'>
                        </div>
                        <div class='form-group'>
                            <label for='description'>Description</label>
                            <textarea class='form-control' style='width: 100%; min-height: 200px;' id='description' name='description' placeholder="project's description"></textarea>
                        </div>
                        <div class='form-group'>
                            <label for='group'>Group</label>
                            <select name='group' id='group' class='form-control'>
                                <?php foreach ($results as $result) { ?>
                                    <option value="<?php echo $result['id']; ?>"><?php echo $result['title']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="localisation">Location</label>
                            <input type="text" class="form-control" id="localisation" name="localisation" placeholder="ville - pays">
                        </div>
                        <div class='form-group'>
                            <label for='date'>Date</label>
                            <input type='text' class='form-control' id='date' name='date' placeholder="start date">
                        </div>
                        <div class='form-group'>
                            <label for='client'>Client</label>
                            <input type='text' class='form-control' id='client' name='client' placeholder="client">
                        </div>
                        <div class='form-group'>
                            <label for='taille'>size</label>
                            <input type='text' class='form-control' id='taille' name='taille' placeholder="ground's surface">
                        </div>
                    </div>
                    <div class='gr-6'>
                        <div class='form-group form-control-img'>
                            <label for='img1'>image principale</label>
                            <input type='file' class='form-control' id='img1' name='img1' placeholder='img1' required>
                            <?php wp_nonce_field(plugin_basename(__FILE__), 'img1'); ?>
                        </div>
                        <div class="form-group form-control-img">
                            <label for="img2">image 2</label>
                            <input type="file" class="form-control" id="img2" name="img2" placeholder="img2" required>
                            <?php wp_nonce_field(plugin_basename(__FILE__), 'img2'); ?>
                        </div>
                        <div class="form-group form-control-img">
                            <label for="img3">image 3</label>
                            <input type="file" class="form-control" id="img3" name="img3" placeholder="img3" required>
                            <?php wp_nonce_field(plugin_basename(__FILE__), 'img3'); ?>
                        </div>
                        <div class="form-group form-control-img">
                            <label for="img4">image 4</label>
                            <input type="file" class="form-control" id="img4" name="img4" placeholder="img4">
                            <?php wp_nonce_field(plugin_basename(__FILE__), 'img4'); ?>
                        </div>
                        <div class="form-group form-control-img">
                            <label for="img5">image 5</label>
                            <input type="file" class="form-control" id="img5" name="img5" placeholder="img5">
                            <?php wp_nonce_field(plugin_basename(__FILE__), 'img5'); ?>
                        </div>
                        <div class='form-group form-control-img'>
                            <label for='img6'>image 5</label>
                            <input type='file' class='form-control' id='img6' name='img6' placeholder='img6'>
                            <?php wp_nonce_field(plugin_basename(__FILE__), 'img6'); ?>
                        </div>
                        <div class='form-group form-control-img'>
                            <label for='img7'>image 5</label>
                            <input type='file' class='form-control' id='img7' name='img7' placeholder='img7'>
                            <?php wp_nonce_field(plugin_basename(__FILE__), 'img7'); ?>
                        </div>
                    </div>
                </div>


                <button type="submit" class="button button-primary">Enregistrer</button>
            </div>
        </form>

        <?php
        if (isset($_POST['form_type']) && ($_POST['form_type'] === 'ajout')) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'kemet_projects';

            $name = $_POST['name'];
            $description = $_POST['description'];
            $localisation = $_POST['localisation'];
            $date = $_POST['date'];
            $client = $_POST['client'];
            $taille = $_POST['taille'];
            $group = $_POST['group'];

            $img1_file = $_FILES['img1'];
            $img2_file = $_FILES['img2'];
            $img3_file = $_FILES['img3'];
            $img4_file = $_FILES['img4'];
            $img5_file = $_FILES['img5'];
            $img6_file = $_FILES['img6'];
            $img7_file = $_FILES['img7'];


            //upload images
            $img1_url = wp_upload_bits($img1_file['name'], null, file_get_contents($img1_file['tmp_name']))['url'];
            $img2_url = wp_upload_bits($img2_file['name'], null, file_get_contents($img2_file['tmp_name']))['url'];
            $img3_url = wp_upload_bits($img3_file['name'], null, file_get_contents($img3_file['tmp_name']))['url'];
            $img4_url = ($img4_file['name'] !== '' && $img4_file !== null) ? wp_upload_bits($img4_file['name'], null, file_get_contents($img4_file['tmp_name']))['url'] : '';
            $img5_url = ($img5_file['name'] !== '' && $img5_file !== null) ? wp_upload_bits($img5_file['name'], null, file_get_contents($img5_file['tmp_name']))['url'] : '';
            $img6_url = ($img6_file['name'] !== '' && $img6_file !== null) ? wp_upload_bits($img6_file['name'], null, file_get_contents($img6_file['tmp_name']))['url'] : '';
            $img7_url = ($img7_file['name'] !== '' && $img7_file !== null) ? wp_upload_bits($img7_file['name'], null, file_get_contents($img7_file['tmp_name']))['url'] : '';

            $wpdb->show_errors(true);

            //add project to db


            $wpdb->query("INSERT INTO $table_name
                        (name, description, localisation, date, client, taille, group_id, img1, img2, img3, img4, img5, img6, img7, created)
                        VALUES
                            ('$name', '$description', '$localisation', '$date', '$client', '$taille', '$group',
                             '$img1_url', '$img2_url', '$img3_url', '$img4_url', '$img5_url', '$img6_url', '$img7_url',
                             NOW() )");


            echo "<script>location.replace('admin.php?page=kmtproject');</script>";

            var_dump($wpdb->last_error);
            die();

        }


    }


    public function admin_action_new_project_group()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kemet_projects_categories';
        $table_name1 = $wpdb->prefix . 'kemet_projects_groups';

        if (isset($_GET['delete'])) {
            $wpdb->query("DELETE FROM $table_name1 WHERE id = " . $_GET['delete']);
            wp_redirect(self::REDIRECT_LiST_DETAILLED);
        }

        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        ?>
        <style>
            .form-group input[type="text"], .form-group textarea {
                border: 1px solid #ccc;
                padding: 2px;
                border-radius: 5px;
            }

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

        <form action="" enctype="multipart/form-data" method="post">
            <div class="wrap">
                <h1>Kemet Project's Group</h1>

                <div class="form-group">
                    <label for="title">title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="project title">
                    <input type="hidden" name="form_type" value="ajout">
                </div>
                <div class="form-group">
                    <label for="description">description</label>
                    <textarea name="description" id="description" class="form-control" style="width: 100%; min-height: 200px;"></textarea>

                </div>
                <div class='form-group'>
                    <label for='menu'>description</label>
                    <select name='menu' id='menu' class='form-control'>
                        <?php foreach ($results as $result) { ?>
                            <option value='<?php echo $result['id']; ?>'><?php echo $result['title']; ?></option>
                        <?php } ?>

                    </select>

                </div>

                <div class="form-group">
                    <label for="img1">image principale</label>
                    <input type="file" class="form-control" id="img1" name="img1" placeholder="img1" required>
                    <?php wp_nonce_field(plugin_basename(__FILE__), 'img1'); ?>
                </div>

                <button type="submit" class="button button-primary">Enregistrer</button>
            </div>
        </form>

        <?php
        if (isset($_POST['form_type']) && ($_POST['form_type'] === 'ajout')) {
            global $wpdb;

            $table_name = $wpdb->prefix . 'kemet_projects_groups';

            $title = $_POST['title'];
            $description = $_POST['description'];
            $menu = $_POST['menu'];
            $img1_file = $_FILES['img1'];

            //upload images
            $img1_url = wp_upload_bits($img1_file['name'], null, file_get_contents($img1_file['tmp_name']))['url'];
            $wpdb->show_errors();

            //add project to db


            $wpdb->query("INSERT INTO $table_name
                        (title, description, menu_id, cover, created)
                        VALUES
                            ('$title', '$description', '$menu', '$img1_url', NOW())");

            echo "<script>location.replace('admin.php?page=admin_action_project_groups');</script>";

        }


    }


}