<?php
/*
Plugin Name: WP Remote Sync
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0.0-d1
Author: EngineThemes
Author URI: http://URI_Of_The_Plugin_Author
Front-ends :
Designers : Nguyen Xuan Hoang
Developers : Nguyen Van Duoc nvduoc@senviet.org
License: http://enginethemes.com/license
*/

/**
 * Define some CONSTANT
 */
define( "WRS_OPTION", 'wrs_option' );
define( "WRS_PLUGIN_FILE", __FILE__ );
define( "WRS_PLUGIN_DIR", trailingslashit(dirname ( __FILE__ ) ));
define( "WRS_PLUGIN_PATH", trailingslashit ( plugin_dir_path ( __FILE__ ) ) );
define( "WRS_INCLUDE_PATH", trailingslashit ( plugin_dir_path ( __FILE__ )."/include" ) );
define( "WRS_PLUGIN_URL", untrailingslashit ( plugins_url ( '/', __FILE__ ) ) );
define( "WRS_AJAX_URL", admin_url ( 'admin-ajax.php', 'relative' ) );
define( "WRS_SCRIPT_DIR", trailingslashit(dirname ( __FILE__ ) . "/javascripts" ));
define( "WRS_STYLE_DIR", trailingslashit(dirname ( __FILE__ ) . "/styles" ));
define( "WRS_DOMAIN", "wrs_domain" );
define( "WRS_VERSION", "1.0.0-d1" );

define("WRS_REMOTE_METAKEY","remote_id");
/**
 * Global option variable
 */
global $wrs_options;
/**
 * Load framework autoload
 */
require_once WRS_PLUGIN_DIR . '/framework/load.php';
/**
 * Loader function
 */
function wrs_loader ()
{
    
    require_once WRS_PLUGIN_DIR . "/include/code-functions.php";

    global $wrs_options;
    wrs_text_domain ();
    /**
     *
     */
    $wrs_options = new scbOptions(
        WRS_OPTION, WRS_PLUGIN_FILE,
        array (
            'endpoint' => 'http://localhost/ettest',
            'username' => "admin",
            'password' => "admin",
            'category_id' => 1,
        )
    );

    /**
     * in admin area
     */

    require_once WRS_INCLUDE_PATH."/class-wrs.php";
    $WRS = WRS::instance();
    if ( is_admin () ) {
        require_once WRS_INCLUDE_PATH."/class-wrs-admin.php";
        new WRS_Admin( WRS_PLUGIN_FILE, $wrs_options );
    }

}
scb_init ( 'wrs_loader' );