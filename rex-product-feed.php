<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rextheme.com
 * @since             1.0.0
 * @package           Rex_Product_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       Product Feed Manager for WooCommerce
 * Plugin URI:        https://rextheme.com
 * Description:       Product Feed Manager for WooCommerce helps you to sell more by uploading product feed to Google shopping, Walmart, eBay, Nextag, Pricegrabber and acquiring real buyer.
 * Version:           6.7.2
 * Author:            RexTheme
 * Author URI:        https://rextheme.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rex-product-feed
 * Domain Path:       /languages
 *
 * WP Requirement & Test
 * Requires at least: 4.7
 * Tested up to: 5.8
 * Requires PHP: 7.0
 *
 * WC Requirement & Test
 * WC requires at least: 3.2
 * WC tested up to: 5.6.0
 */



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
define('WPFM__FILE__', __FILE__ );
define('WPFM_PLUGIN_BASE', plugin_basename( WPFM__FILE__ ) );
define("WPFM_PLUGIN_DIR_URL", plugin_dir_url(__FILE__));
define("WPFM_PLUGIN_DIR_PATH", plugin_dir_path( __FILE__ ));
define("WPFM_PLUGIN_ASSETS_FOLDER", WPFM_PLUGIN_DIR_URL.'admin/assets/');


/**
 * Check if WooCommerce is active
 **/
function rex_is_woocommerce_active(){
    if(is_plugin_active( 'woocommerce/woocommerce.php' )){
        return true;

    }else{
        return false;
    }

}

/**
 * Run dependency check and abort if required.
 **/
function rex_check_dependency(){
    if ( ! rex_is_woocommerce_active() ) {
        add_action( 'admin_init', 'rex_product_feed_deactivate' );
        add_action( 'admin_notices', 'rex_product_feed_admin_notice' );
    }
}


/**
 * Display admin notice if WooCoomerce not activated
 **/
function rex_product_feed_admin_notice() {
    echo '<div class="error"><p><strong>WooCcommerce Product Feed Manager</strong> has been <strong>deactivated</strong>. Please install and activate <b>WooCoommerce</b> before activating this plugin.</p></div>';
    $activate = filter_input( INPUT_GET, 'activate', FILTER_SANITIZE_STRING );

    if ( $activate ){
        unset( $_GET['activate'] );
    }
}


/**
 * Force deactivate the plugin.
 **/
function rex_product_feed_deactivate() {
    deactivate_plugins( plugin_basename( __FILE__ ) );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rex-product-feed-activator.php
 */
function activate_rex_product_feed() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-rex-product-feed-activator.php';
    if ( !rex_is_woocommerce_active() ) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires the WooCommerce Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }else{
        Rex_Product_Feed_Activator::activate();
    }

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rex-product-feed-deactivator.php
 */
function deactivate_rex_product_feed() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-rex-product-feed-deactivator.php';
    Rex_Product_Feed_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_rex_product_feed' );
register_deactivation_hook( __FILE__, 'deactivate_rex_product_feed' );



/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rex-product-feed.php';
require plugin_dir_path( __FILE__ ) . 'includes/helper.php';

function wpfm_plugin_redirect() {
    if (get_option('rex_wpfm_plugin_do_activation_redirect', false)) {
        delete_option('rex_wpfm_plugin_do_activation_redirect');
        wp_redirect("admin.php?page=bwfm-dashboard");
    }
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rex_product_feed() {

    $plugin = new Rex_Product_Feed();
    $plugin->run();

    /**
     * Notices
     */
//    $notices         = array(
//        'database_update'           => 'rex_wpfm_db_update_notice',
//        'database_update_running'   => 'rex_wpfm_db_update_running_notice',
//    );
//
//    foreach ( $notices as $notice => $callback ) {
//        add_action( 'admin_notices', array('Rex_Product_Feed_Notices', $callback) );
//    }
}
run_rex_product_feed();


/**
 * Initialize the tracker
 *
 * @return void
 */
function appsero_init_tracker_bwfm() {
    $client = new Appsero\Client( '5fab4a18-aaf4-4565-816a-47858011d96f', 'Product Feed Manager for WooCommerce', __FILE__ );

    // Active insights
    $client->insights()->init();
}

appsero_init_tracker_bwfm();


/**
 * is_edit_page
 * function to check if the current page is a post edit page
 *
 * @param  string  $new_edit what page to check for accepts new - new post page ,edit - edit post page, null for either
 * @return boolean
 */
function is_edit_page($new_edit = null){
    global $pagenow;
    if (!is_admin()) return false;
    if($new_edit == "edit")
        return in_array( $pagenow, array( 'post.php',  ) );
    elseif($new_edit == "new") //check for new post page
        return in_array( $pagenow, array( 'post-new.php' ) );
    else
        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
}

/**
 * @param $pages
 * @return mixed
 */
function wpfm_top_pages_modify($pages) {
    global $typenow;
    if (is_edit_page('edit') && "product-feed" == $typenow){
        unset($pages[0]);
        unset($pages[1]);
    }elseif (is_edit_page('new') && "product-feed" == $typenow) {
        unset($pages[0]);
        unset($pages[1]);
    }
    return $pages;
}
add_filter('themify_top_pages', 'wpfm_top_pages_modify' );