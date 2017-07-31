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
 * Plugin Name:       Best Products Feed for WooCoommerce
 * Plugin URI:        https://rextheme.com
 * Description:       Best WooCommerce Product Feed helps you to sell more by uploading products to Google merchant shop and acquiring real buyer.
 * Version:           1.1.4
 * Author:            RexTheme
 * Author URI:        https://rextheme.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rex-product-feed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Create a helper function for easy SDK access.
function rex_product_feed() {
    global $rex_product_feed;

    if ( ! isset( $rex_product_feed ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $rex_product_feed = fs_dynamic_init( array(
            'id'                  => '1260',
            'slug'                => 'best-woocommerce-feed',
            'type'                => 'plugin',
            'public_key'          => 'pk_10b344e36e5e1aaf459a0a15655bd',
            'is_premium'          => true,
            // If your plugin is a serviceware, set this option to false.
            'has_premium_version' => true,
            'has_addons'          => false,
            'has_paid_plans'      => true,
            'menu'                => array(
                'slug'           => 'edit.php?post_type=product-feed',
                'contact'        => true,
                'support'        => true,
            ),
            // Set the SDK to work in a sandbox mode (for development & testing).
            // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
            'secret_key'          => 'sk_h4^$lDf4(l=z<jxkqZbBo-{#zSvXV',
        ) );
    }

    return $rex_product_feed;
}

// Init Freemius.
rex_product_feed();
// Signal that SDK was initiated.
do_action( 'rex_product_feed_loaded' );


/**
 * Check if WooCommerce is active
 **/
function rex_is_woocommerce_active(){
    if(is_plugin_active( 'woocommerce/woocommerce.php' )){
        return true;
    }else
        return false;
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
    echo '<div class="error"><p><strong>Best Products Feed for WooCoommerce</strong> has been <strong>deactivated</strong>. Please install and activate <b>WooCoommerce</b> before activating this plugin.</p></div>';

    if ( isset( $_GET['activate'] ) ){
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

    rex_check_dependency();
    $plugin = new Rex_Product_Feed();
    $plugin->run();

}
run_rex_product_feed();
