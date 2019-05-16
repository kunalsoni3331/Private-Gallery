<?php
/*
Plugin Name: Private Gallery
Plugin URI: https://madhatmedia.net/
Description: Allows to password protected gallery.
Version: 1.0.0
Author: MadHatMedia
Author URI: https://madhatmedia.net/
*/


// plugin definition
define( 'KPG_PLUGIN_NM', 'Private Gallery');
define( 'KPG_PLUGIN', '/privategallery/');

// directory define
define( 'KPG_PLUGIN_DIR', WP_PLUGIN_DIR.KPG_PLUGIN);
define( 'KPG_INCLUDES_DIR', KPG_PLUGIN_DIR.'includes/' );
// define( 'KPG_LIB_DIR', KPG_PLUGIN_DIR.'lib/' );

define( 'KPG_ASSETS_DIR', KPG_PLUGIN_DIR.'assets/' );
define( 'KPG_IMAGE_DIR', KPG_ASSETS_DIR.'image/' );
define( 'KPG_CSS_DIR', KPG_ASSETS_DIR.'css/' );
define( 'KPG_JS_DIR', KPG_ASSETS_DIR.'js/' );
define( 'KPG_TEMPLATE_DIR', KPG_PLUGIN_DIR.'templates/' );

// URL define
define( 'KPG_PLUGIN_URL', WP_PLUGIN_URL.KPG_PLUGIN);
define( 'KPG_ASSETS_URL', KPG_PLUGIN_URL.'assets/');
define( 'KPG_IMAGES_URL', KPG_ASSETS_URL.'images/');
define( 'KPG_CSS_URL', KPG_ASSETS_URL.'css/');
define( 'KPG_JS_URL', KPG_ASSETS_URL.'js/');
define( 'KPG_TEMPLATE_URL', KPG_PLUGIN_URL.'templates/' );

// define text domain
define( 'KPG_txt_domain', 'kpg_text_domain' );

global $kpg_version;
$kpg_version = '1.1';

class kprivategallery {

    var $kpg_gallery = '';
    var $kpg_links = '';

    function __construct() {

        global $wpdb;
        $this->kpg_gallery = 'kpg_gallery';
        $this->kpg_links = 'kpg_links';

		register_activation_hook( __FILE__,  array( &$this, 'kpg_install' ) );

        register_deactivation_hook( __FILE__, array( &$this, 'kpg_deactivation' ) );

		add_action( 'admin_menu', array( $this, 'kpg_add_menu' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'kpg_enqueue_scripts' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'kpg_front_enqueue_scripts' ) );
        
	}

	static function kpg_install() {

		global $wpdb, $kpg_version;

        $charset_collate = $wpdb->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        update_option( "kpg_plugin", true );
        update_option( "kpg_version", $kpg_version );
        
	}

    static function kpg_deactivation() {
        // deactivation process here
    }

	function kpg_get_sub_menu() {
		$kpg_admin_menu = array(
            array(
                'name' => __('Private Gallery', KPG_txt_domain),
                'cap'  => 'manage_options',
                'slug' => $this->kpg_gallery,
            ),
			array(
				'name' => __('Private Links', KPG_txt_domain),
				'cap'  => 'manage_options',
				'slug' => $this->kpg_links,
			),
		);
		return $kpg_admin_menu;
	}

	function kpg_add_menu() {

		$kpg_main_page_name = __('Private Gallery', KPG_txt_domain);
		$kpg_main_page_capa = 'manage_options';
		$kpg_main_page_slug = $this->kpg_gallery; //$this->kpg_commerce;

		$kpg_get_sub_menu   = $this->kpg_get_sub_menu();
		/* set capablity here.... Right now manage_options capability given to all page and sub pages. */
			 
		add_menu_page($kpg_main_page_name, $kpg_main_page_name, $kpg_main_page_capa, $kpg_main_page_slug, array( &$this, 'kpg_route' ),'', 11 );

		foreach ($kpg_get_sub_menu as $kpg_menu_key => $kpg_menu_value) {
			add_submenu_page(
				$kpg_main_page_slug, 
				$kpg_menu_value['name'], 
				$kpg_menu_value['name'], 
				$kpg_menu_value['cap'], 
				$kpg_menu_value['slug'], 
				array( $this, 'kpg_route') 
			);	
		}
	}

	function kpg_is_activate(){
		if(get_option("kpg_plugin")) {
			return true;
		} else {
			return false;
		}
	}

	function kpg_admin_slugs() {
		$kpg_pages_slug = array(
			$this->kpg_gallery,
			$this->kpg_links,
		);
		return $kpg_pages_slug;
	}

	function kpg_is_page() {
		if( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $this->kpg_admin_slugs() ) ) {
			return true;
		} else {
			return false;
		}
	} 

    function kpg_admin_msg( $key ) {
        $admin_msg = array(
            "no_tax" => __("No matching tax rates found.", KPG_txt_domain)
        );

        if( $key == 'script' ){
            $script = '<script type="text/javascript">';
            $script.= 'var __kpg_msg = '.json_encode($admin_msg);
            $script.= '</script>';
            return $script;
        } else {
            return isset($admin_msg[$key]) ? $admin_msg[$key] : false;
        }
    }

	function kpg_enqueue_scripts() {
		global $kpg_version;
		/* must register style and than enqueue */
		if( $this->kpg_is_page() ) {
			/*********** register and enqueue styles ***************/
            wp_register_style( 'kpg_bootstrap_min',  KPG_CSS_URL.'kpg_bootstrap.min.css', false, $kpg_version );
            wp_register_style( 'kpg_datatable_bootstrap', KPG_CSS_URL.'kpg_dataTables.bootstrap4.min.css', false, $kpg_version );
            wp_register_style( 'kpg_admin_style_css',  KPG_CSS_URL.'kpg_admin_style.css', false, $kpg_version );
            wp_enqueue_style( 'kpg_bootstrap_min' );
            wp_enqueue_style( 'kpg_datatable_bootstrap' );
            wp_enqueue_style( 'kpg_admin_style_css' );


			/*********** register and enqueue scripts ***************/
            echo $this->kpg_admin_msg( 'script' );
            wp_register_script( 'kpg_jquery_js', KPG_JS_URL.'kpg_jquery-3.3.1.js', 'jQuery', $kpg_version, true );
			wp_register_script( 'kpg_bootstrap_js', KPG_JS_URL.'kpg_bootstrap.min.js', 'jQuery', $kpg_version, true );
            wp_register_script( 'kpg_bootstrap_bundle_js', KPG_JS_URL.'kpg_bootstrap.bundle.min.js', 'jQuery', $kpg_version, true );
            wp_register_script( 'kpg_datatable_js', KPG_JS_URL.'kpg_jquery.dataTables.min.js', 'jQuery', $kpg_version, true );
            wp_register_script( 'kpg_admin_js', KPG_JS_URL.'kpg_admin_js.js', 'jQuery', $kpg_version, true );
			wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'kpg_jquery_js' );
            wp_enqueue_script( 'kpg_bootstrap_js' );
			wp_enqueue_script( 'kpg_bootstrap_bundle_js' );
            wp_enqueue_script( 'kpg_datatable_js' );
            wp_enqueue_script( 'kpg_admin_js' );
		}
    }

    function kpg_front_enqueue_scripts() {
        global $kpg_version;
        // need to check here if its front section than enqueue script
        if ( is_singular() && strpos( $_SERVER['REQUEST_URI'], 'private_gallery' ) !== false ) {
        	
            /*********** register and enqueue styles ***************/
            wp_register_style( 'kpg_bootstrap_min',  KPG_CSS_URL.'kpg_bootstrap.min.css', false, $kpg_version );
            wp_register_style( 'kpg_datatable_bootstrap', KPG_CSS_URL.'kpg_dataTables.bootstrap4.min.css', false, $kpg_version );
            wp_register_style( 'kpg_admin_style_css',  KPG_CSS_URL.'kpg_admin_style.css', false, $kpg_version );
            wp_enqueue_style( 'kpg_bootstrap_min' );
            wp_enqueue_style( 'kpg_datatable_bootstrap' );
            wp_enqueue_style( 'kpg_admin_style_css' );


			/*********** register and enqueue scripts ***************/
            echo $this->kpg_admin_msg( 'script' );
            wp_register_script( 'kpg_jquery_js', KPG_JS_URL.'kpg_jquery-3.3.1.js', 'jQuery', $kpg_version, true );
			wp_register_script( 'kpg_bootstrap_js', KPG_JS_URL.'kpg_bootstrap.min.js', 'jQuery', $kpg_version, true );
            wp_register_script( 'kpg_bootstrap_bundle_js', KPG_JS_URL.'kpg_bootstrap.bundle.min.js', 'jQuery', $kpg_version, true );
            wp_register_script( 'kpg_datatable_js', KPG_JS_URL.'kpg_jquery.dataTables.min.js', 'jQuery', $kpg_version, true );
            wp_register_script( 'kpg_admin_js', KPG_JS_URL.'kpg_admin_js.js', 'jQuery', $kpg_version, true );
			wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'kpg_bootstrap_js' );
			wp_enqueue_script( 'kpg_bootstrap_bundle_js' );
            wp_enqueue_script( 'kpg_datatable_js' );
            wp_enqueue_script( 'kpg_admin_js' );

        }        
	}

	function kpg_route() {
		global $kpg_gallery, $kpg_links;
		if( isset($_REQUEST['page']) && $_REQUEST['page'] != '' ){
			switch ( $_REQUEST['page'] ) {
				case $this->kpg_gallery:
                    $kpg_gallery->kpg_page_content();
					break;
				case $this->kpg_links:
					$kpg_links->kpg_page_content();
					break;
				default:
					_e( "Product Listing will be here", KPG_txt_domain );
					break;
			}
		}
	}

    function kpg_site_url() {
        return get_site_url( get_current_blog_id() ).'/';
    }

    function kpg_write_log( $content = '', $file_name = 'kpg_log.txt' ) {
        $file = __DIR__ . '/log/' . $file_name;    
        $file_content = "=============== Write At => " . date( "y-m-d H:i:s" ) . " =============== \r\n";
        $file_content .= $content . "\r\n\r\n";
        file_put_contents( $file, $file_content, FILE_APPEND | LOCK_EX );
    }
}

// begin!
global $kpg;
$kpg = new kprivategallery();

if( $kpg->kpg_is_activate() && file_exists( KPG_INCLUDES_DIR."kpg_links.php" ) ) {
    include_once( KPG_INCLUDES_DIR."kpg_links.php" );
}

if( $kpg->kpg_is_activate() && file_exists( KPG_INCLUDES_DIR."kpg_gallery.php" ) ) {
    include_once( KPG_INCLUDES_DIR."kpg_gallery.php" );
}

if( $kpg->kpg_is_activate() && file_exists( KPG_INCLUDES_DIR."kpg_front_end.php" ) ) {
    include_once( KPG_INCLUDES_DIR."kpg_front_end.php" );
}

