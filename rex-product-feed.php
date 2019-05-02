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
 * Plugin Name:       WooCommerce Product Feed Manager
 * Plugin URI:        https://rextheme.com
 * Description:       WooCommerce Product Feed Manager helps you to sell more by uploading products to Google merchant, Amazon, Ebay, Nextag, Pricegrabber and acquiring real buyer.
 * Version:           2.2.5
 * Author:            RexTheme
 * Author URI:        https://rextheme.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rex-product-feed
 * WC requires at least: 3.0.0
 * WC tested up to: 3.6.2
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
define( "WPFM_PLUGIN_DIR_URL", plugin_dir_url( __FILE__ ) );
// Create a helper function for easy SDK access.
function rex_product_feed()
{
    global  $rex_product_feed ;
    
    if ( !isset( $bwf_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $rex_product_feed = fs_dynamic_init( array(
            'id'             => '1327',
            'slug'           => 'best-woocommerce-feed',
            'type'           => 'plugin',
            'public_key'     => 'pk_872b130317a310d70105122544cde',
            'is_premium'     => false,
            'premium_suffix' => 'premium',
            'has_addons'     => false,
            'has_paid_plans' => true,
            'menu'           => array(
            'slug'           => 'bwfm-dashboard',
            'override_exact' => true,
            'first-path'     => 'admin.php?page=bwfm-dashboard',
            'parent'         => array(
            'slug' => 'product-feed',
        ),
        ),
            'is_live'        => true,
        ) );
    }
    
    return $rex_product_feed;
}

// Init Freemius.
rex_product_feed();
// Signal that SDK was initiated.
do_action( 'rex_product_feed_loaded' );
function bwf_fs_settings_url()
{
    return admin_url( 'admin.php?page=bwfm-dashboard' );
}

rex_product_feed()->add_filter( 'connect_url', 'bwf_fs_settings_url' );
rex_product_feed()->add_filter( 'after_skip_url', 'bwf_fs_settings_url' );
rex_product_feed()->add_filter( 'after_connect_url', 'bwf_fs_settings_url' );
rex_product_feed()->add_filter( 'after_pending_connect_url', 'bwf_fs_settings_url' );
/**
 * Check if WooCommerce is active
 **/
function rex_is_woocommerce_active()
{
    
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        return true;
    } else {
        return false;
    }

}

/**
 * Run dependency check and abort if required.
 **/
function rex_check_dependency()
{
    
    if ( !rex_is_woocommerce_active() ) {
        add_action( 'admin_init', 'rex_product_feed_deactivate' );
        add_action( 'admin_notices', 'rex_product_feed_admin_notice' );
    }

}

/**
 * Display admin notice if WooCoomerce not activated
 **/
function rex_product_feed_admin_notice()
{
    echo  '<div class="error"><p><strong>WooCcommerce Product Feed Manager</strong> has been <strong>deactivated</strong>. Please install and activate <b>WooCoommerce</b> before activating this plugin.</p></div>' ;
    if ( isset( $_GET['activate'] ) ) {
        unset( $_GET['activate'] );
    }
}

/**
 * Force deactivate the plugin.
 **/
function rex_product_feed_deactivate()
{
    deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rex-product-feed-activator.php
 */
function activate_rex_product_feed()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-rex-product-feed-activator.php';
    
    if ( !rex_is_woocommerce_active() ) {
        // Stop activation redirect and show error
        wp_die( 'Sorry, but this plugin requires the WooCommerce Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>' );
    } else {
        Rex_Product_Feed_Activator::activate();
    }

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rex-product-feed-deactivator.php
 */
function deactivate_rex_product_feed()
{
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
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rex_product_feed()
{
    rex_check_dependency();
    $plugin = new Rex_Product_Feed();
    $plugin->run();
}

run_rex_product_feed();
rex_product_feed()->add_action( 'after_uninstall', 'rex_uninstall_cleanup' );
function rex_uninstall_cleanup()
{
    if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        exit;
    }
}

if ( !function_exists( 'write_log' ) ) {
    function write_log( $log )
    {
        if ( true === WP_DEBUG ) {
            
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        
        }
    }

}