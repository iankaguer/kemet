<?php
namespace App;

use App\Controller\AdminController;

class KmtProjectPlugin
{
   
    public function __construct($file){
        register_activation_hook($file, array($this, 'crud_operations'));
        //register_activation_hook($file, array($this, ''));
        add_action('admin_notice', array($this, 'notice_activation'));

        if(is_admin()){
            $adminController = new AdminController();
            

        }
    }

    public function plugin_activation(){
        set_transient('kmtplugin_notice_activation', true);
       
    }

    public function notice_activation(){
        if (get_transient('kmtplugin_notice_activation')) {
            self::render('notices', [
                'message' => 'Bienvenue dans KmtProject !'
            ]);
            delete_transient('kmtplugin_notice_activation');
            
        }
    }

    public static function render(string $view, array $data = array()){
        $file = plugin_dir_path(__FILE__) . 'views/' . $view . '.php';
        if(file_exists($file)){
            extract($data);
            ob_start();
            include_once($file);
            echo ob_get_clean();
        }
    }

    public function crud_operations(): void
    {
        $this->plugin_activation();
        global $wpdb;
        $table_name = $wpdb->prefix .'kemet_projects';
	    $table_name2 = $wpdb->prefix .'kemet_projects_detailles';
	    $table_name3 = $wpdb->prefix .'kemet_projects_detailles_images';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
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
          
        ) $charset_collate;";
		
		$sql2 = "CREATE TABLE IF NOT EXISTS $table_name2 (
			`id` int(20) NOT NULL AUTO_INCREMENT,
            `title` varchar(254) NOT NULL,
			`status` varchar(254) NOT NULL,
			`description` text,
    		`date` varchar(254) NULL,
    		`site` varchar(254) NULL,
    		`taille` varchar(254) NULL,
    		`client` varchar(254) NULL,
    		`collaborateurs` varchar(254) NULL,
			`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
		 
		) $charset_collate;";
		
		$sql3 = "CREATE TABLE IF NOT EXISTS $table_name3 (
    			`id` int(20) NOT NULL AUTO_INCREMENT,
    			`id_project` int(20) NOT NULL,
    			`img` varchar(254) NOT NULL,
    			PRIMARY KEY (`id`),
    			FOREIGN KEY (`id_project`) REFERENCES $table_name2(`id`)
    		) $charset_collate;";
		
		
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
	    if($wpdb->get_var("SHOW TABLES LIKE '$table_name2'") != $table_name2){
		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		    dbDelta($sql2);
	    }
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name3'") != $table_name3){
		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		    dbDelta($sql3);
	    }
    }
    
}
