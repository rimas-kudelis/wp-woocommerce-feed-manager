<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Metabox
 * @subpackage Rex_Product_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines all the Metaboxes for Products
 *
 * @package    Rex_Product_Metabox
 * @subpackage Rex_Product_Feed/admin
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Product_Feed_Ajax {

    /**
     * Hook in ajax handlers.
     *
     * @since    1.0.0
     */
    public static function init() {

        $validations = array(
            'logged_in' => true,
            'user_can'  => 'manage_options',
        );

        wp_ajax_helper()->handle( 'my-handle' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'generate_feed' ) )
            ->with_validation( $validations );

        wp_ajax_helper()->handle( 'merchant-change' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'show_feed_template' ) )
            ->with_validation( $validations );


        /**
         * Stop Admin Notices
         */
        wp_ajax_helper()->handle( 'stop-notices' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'stop_notices' ) )
            ->with_validation( $validations );

        /**
         * Google Category Mapping
         */
        wp_ajax_helper()->handle( 'category-mapping' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'category_mapping' ) )
            ->with_validation( $validations );

        wp_ajax_helper()->handle( 'category-mapping-update' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'category_mapping_update' ) )
            ->with_validation( $validations );

        wp_ajax_helper()->handle( 'category-mapping-delete' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'category_mapping_delete' ) )
            ->with_validation( $validations );
    }

    public static function generate_feed( $config ){

        try {
            $merchant = Rex_Product_Feed_Factory::build( $config );
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return $merchant->make_feed();

    }

    public static function show_feed_template( $merchant ){


        $feed_rules    = get_post_meta( $merchant['post_id'], 'rex_feed_feed_config', true );

        if ( $merchant['merchant'] != get_post_meta( $merchant['post_id'], 'rex_feed_merchant', true ) ) {
            $feed_rules = false;
        }

        $feed_template = Rex_Feed_Template_Factory::build( $merchant['merchant'], $feed_rules );
        ob_start();
        include plugin_dir_path( __FILE__ ) . 'partials/feed-config-metabox-display.php';
        return ob_get_clean();

    }

    /**
     * Save Category Map
     */
    public function category_mapping($payload){
        $map_category = array();
        $map_name = $payload['map_name'];
        $cat_map_array       = array();
        parse_str( $payload['cat_map'], $cat_map_array );
        $cat_map_array       = $cat_map_array['category-map'];
        $cat_mapper['map-name'] = $map_name;
        $cat_mapper['map-config'] = $cat_map_array;

        if ( get_option( 'rex_cat_map_'.str_replace(' ', '', strtolower($map_name)) ) !== false ) {
            update_option( 'rex_cat_map_'.str_replace(' ', '', strtolower($map_name)), $cat_mapper );
        }else {
            add_option('rex_cat_map_'.str_replace(' ', '', strtolower($map_name)), $cat_mapper);
        }
        return $map_category;
    }

    public function category_mapping_update($payload){
        $map_category = array();
        $map_name = $payload['map_name'];
        $cat_map_array       = array();
        parse_str( $payload['cat_map'], $cat_map_array );
        $cat_map_array       = $cat_map_array['category-map'];
        $cat_mapper['map-name'] = $map_name;
        $cat_mapper['map-config'] = $cat_map_array;
        if ( get_option( 'rex_cat_map_'.str_replace(' ', '', strtolower($map_name)) ) !== false ) {
            update_option( 'rex_cat_map_'.str_replace(' ', '', strtolower($map_name)), $cat_mapper );
        }else {
            add_option('rex_cat_map_'.str_replace(' ', '', strtolower($map_name)), $cat_mapper);
        }
        return $map_category;
    }

    public function category_mapping_delete($payload){

        $map_name = $payload['map_name'];

        if ( get_option( 'rex_cat_map_'.str_replace(' ', '', strtolower($map_name)) ) !== false ) {
            delete_option( 'rex_cat_map_'.str_replace(' ', '', strtolower($map_name)) );
        }

        return 'Success';
    }


    function stop_notices($payload) {
        update_option('rex_bwfm_notification_status', 'no');
        return 'success';
    }

}
