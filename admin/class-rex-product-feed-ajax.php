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
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'get_product_number' ) )
            ->with_validation( $validations );


        wp_ajax_helper()->handle( 'generate-feed' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'generate_feed' ) )
            ->with_validation( $validations );


        wp_ajax_helper()->handle( 'save-feed' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'save_feed' ) )
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


        /*
         * Google merchant settings
         */
        wp_ajax_helper()->handle( 'google-merchant-settings' )
            ->with_callback( array( 'Rex_Google_Merchant_Settings_Api', 'save_settings' ) )
            ->with_validation( $validations );

        /*
         * Send to Google
         * Merchant Center
         */
        wp_ajax_helper()->handle( 'send-to-google' )
            ->with_callback( array( 'Rex_Product_Feed_Ajax', 'send_to_google' ) )
            ->with_validation( $validations );
    }


    /**
     * Get total number of products
     *
     * @since    2.0.0
     */
    public static function get_product_number($payload) {

        if (rex_product_feed()->is_free_plan()) {
            $totalProducts = 50;
        }else {
            $products=wp_count_posts('product');
            $variations=wp_count_posts('product_variation');
            $totalProducts=$products->publish + $variations->publish;
        }
        return array(
            'products'  => $totalProducts,
        );
    }


    /**
     * Get total number of products
     *
     * @since    2.0.0
     */

    public static function save_feed($payload) {

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


    /*
     * Send to Google
     */
    public static function send_to_google($payload) {

        $feed_id = $payload['feed_id'];
        $rex_google_merchant = new Rex_Google_Merchant_Settings_Api();
        if ($rex_google_merchant->is_authenticate()) {
            $feed_url = get_post_meta( $feed_id, 'rex_feed_xml_file', true );
            $feed_title = get_the_title($feed_id);
            $client = $rex_google_merchant::get_client();
            $client_id = $rex_google_merchant::$client_id;
            $client_secret = $rex_google_merchant::$client_secret;
            $merchant_id = $rex_google_merchant::$merchant_id;


            $access_token = $rex_google_merchant->get_access_token();
            $client->setClientId($client_id);
            $client->setClientSecret($client_secret);
            $client->setScopes( 'https://www.googleapis.com/auth/content' );
            $client->setAccessToken($access_token);

            /*
             * Initialize service and datafeed
             */
            $service = new Google_Service_ShoppingContent($client);
            $datafeed = new Google_Service_ShoppingContent_Datafeed();

            $name = $feed_title;
            $filename = $name.uniqid();
            $datafeed->setName($name);
            $datafeed->setContentType('products');
            $datafeed->setAttributeLanguage($payload['language']);
            $datafeed->setContentLanguage($payload['language']);
            $datafeed->setIntendedDestinations(array('Shopping'));
            if (!$rex_google_merchant->feed_exists($feed_id)){
                $datafeed->setFileName($filename);
            }else {
                $datafeed->setFileName(get_post_meta($feed_id, 'rex_feed_google_data_feed_file_name', true));
            }

            $datafeed->setTargetCountry($payload['country']);

            /*
             * Initialize Schedule
             */
            $fetch_schedule = new Google_Service_ShoppingContent_DatafeedFetchSchedule();
            if($payload['schedule'] === 'monthly') {
                $fetch_schedule->setDayOfMonth($payload['']);
            }
            if($payload['schedule'] === 'weekly') {
                $fetch_schedule->setWeekday($payload['day']);
            }
            $fetch_schedule->setHour($payload['hour']);
            $fetch_schedule->setFetchUrl($feed_url);

            /*
             * initialize feed format
             */
            $format = new Google_Service_ShoppingContent_DatafeedFormat();
            $format->setFileEncoding('utf-8');
            $datafeed->setFormat($format);
            $datafeed->setFetchSchedule($fetch_schedule);

            try {
                if ($rex_google_merchant->feed_exists($feed_id)){
                    $datafeedID = get_post_meta($feed_id, 'rex_feed_google_data_feed_id', true);
                    $datafeed->setId($datafeedID);
                    $service->datafeeds->update($merchant_id, $datafeedID, $datafeed);
                    error_log($datafeedID);
                }else {
                    $datafeed = $service->datafeeds->insert($merchant_id, $datafeed);
                    $datafeedID = $datafeed->getId();
                    $datafeedFileName = $datafeed->getFileName();
                    update_post_meta($feed_id, 'rex_feed_google_data_feed_id',$datafeedID );
                    update_post_meta($feed_id, 'rex_feed_google_data_feed_file_name',$datafeedFileName );
                    error_log(print_r($datafeed, 1));
                    error_log($datafeedID);
                }
                $service->datafeeds->fetchnow($merchant_id, $datafeedID);
            }
            catch(Exception $e) {
                error_log(print_r($e->getMessage(), 1));
                echo 'Message: ' .$e->getMessage();
            }
        }


        update_post_meta($feed_id, 'rex_feed_google_schedule',$payload['schedule'] );
        update_post_meta($feed_id, 'rex_feed_google_schedule_time',$payload['hour'] );
        update_post_meta($feed_id, 'rex_feed_google_schedule_month',$payload['month'] );
        update_post_meta($feed_id, 'rex_feed_google_schedule_week_day',$payload['day'] );
        update_post_meta($feed_id, 'rex_feed_google_target_country',$payload['country'] );
        update_post_meta($feed_id, 'rex_feed_google_target_language',$payload['language'] );

        return array('success' => true);
    }

}
