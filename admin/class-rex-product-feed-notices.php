<?php

/**
 * Class Rex_Product_Feed_Notices
 */

class Rex_Product_Feed_Notices {


    /**
     * Array of notices - name => callback.
     *
     * @var array
     */
    private static $core_notices = array(
        'database_update'           => 'rex_wpfm_db_update_notice',
        'database_update_running'   => 'rex_wpfm_db_update_running_notice',
    );


    /**
     * Get notices
     *
     * @return array
     */
    public static function get_notices() {
        return self::$core_notices;
    }


    /**
     * WPFM get screen ids
     * @return array
     */
    public static function wpfm_get_screen_ids() {

        $wpfm_screen_id = sanitize_title( __( 'Product Feed', 'rex-product-feed' ) );
        $screen_ids = array(
            $wpfm_screen_id,
            'edit-' . $wpfm_screen_id,
            $wpfm_screen_id . '_page_category_mapping',
            $wpfm_screen_id . '_page_merchant_settings',
            $wpfm_screen_id . '_page_wpfm_dashboard',
        );;

        $show_on_screens = array_merge($screen_ids, array(
            'dashboard',
            'plugins',
        ));
        return $show_on_screens;
    }

    /*
    * Database update notice
    */
    public static function rex_wpfm_db_update_notice() {
        $show_on_screens = self::wpfm_get_screen_ids();
        $screen          = get_current_screen();
        $screen_id       = $screen ? $screen->id : '';


        // Notices should only show on WPFM screens, the main dashboard, and on the plugins screen.
        if ( ! in_array( $screen_id, $show_on_screens, true ) ) {
            return;
        }

        $db_version = get_option('rex_wpfm_db_version');
        if( get_transient( 'rex-wpfm-database-update' ) ){
            require_once plugin_dir_path( __FILE__ ) . 'partials/database-update-notice.php';

        }
        if(!$db_version){
            require_once plugin_dir_path( __FILE__ ) . 'partials/database-update-notice.php';
        }
    }

    /**
     * Database running update notice
     */
    public static function rex_wpfm_db_update_running_notice() {

        $show_on_screens = self::wpfm_get_screen_ids();
        $screen          = get_current_screen();
        $screen_id       = $screen ? $screen->id : '';
        if ( ! in_array( $screen_id, $show_on_screens, true ) ) {
            return;
        }
        if( get_transient( 'rex-wpfm-database-update-running' ) ){
            require_once plugin_dir_path( __FILE__ ) . 'partials/database-update-running-notice.php';
        }
    }

}
