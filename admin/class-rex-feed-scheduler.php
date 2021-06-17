<?php

class Rex_Feed_Scheduler {

    /**
     * Feed ids
     *
     * @since    2.0.0
     * @access   protected
     * @var      string    $feed_ids
     */
    protected $feed_ids;


    /**
     * Feed Schedule
     *
     * @since    2.0.0
     * @access   protected
     * @var      string    $schedule
     */
    protected $schedule;


    /**
     * Background Processor
     *
     * @since    2.0.0
     * @access   protected
     * @var      string    $background_process
     */
    protected $background_process;

    protected $batch_array;

    /**
     * Initialize the class and set its properties.
     *
     * @since    2.0.0
     */
    public function __construct() {
        $this->batch_array = array();
        $this->background_process = new Rex_Product_Feed_Background_Process();
    }

    /**
     * register scheduler for
     * action scheduler
     *
     */
    public function register_scheduler() {
        $schedules = apply_filters('wpfm_action_schedules', array(
            'hourly'    => '0 * * * *',
            'daily'     => '0 0 * * *',
            'weekly'    => '0 0 * * 0',
        ));
        foreach ($schedules as $key => $value) {
            if ( false === as_next_scheduled_action( "wpfm_{$key}_schedule_update_hook" ) ) {
                wp_clear_scheduled_hook( 'rex_feed_schedule_update' );
                as_schedule_cron_action( time(), $value, "wpfm_{$key}_schedule_update_hook", array('schedule' => $key));
            }
        }
    }


    /**
     * Get all feeds
     *
     * @param string $schedule
     * @return int[]|WP_Post[]
     */
    public function get_feeds( $schedule ) {

        
        $meta_query = array();
        $meta_query[] = array(
            'key'      => 'rex_feed_schedule',
            'value'    => 'hourly',
        );
        if($schedule === 'daily') {
            $meta_query[] = array(
                'key'      => 'rex_feed_schedule',
                'value'    => 'daily',
            );
            $meta_query['relation'] = 'OR';
        }
        if($schedule === 'weekly') {
            $meta_query[] = array(
                'key'      => 'rex_feed_schedule',
                'value'    => 'weekly',
            );
            $meta_query['relation'] = 'OR';
        }

        $args = array(
            'post_type'      => 'product-feed',
            'post_status'    => array('publish'),
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query'     => $meta_query,
        );

        
        $query = new WP_Query( $args );
        return $query->get_posts();
    }


    /**
     * Get all feeds for scheduler
     *
     * @param string $schedule
     * @return int[]|WP_Post[]
     */
    public function get_feeds_for_scheduler( $schedule = 'hourly' ) {
        
        $args = array(
            'post_type'      => 'product-feed',
            'post_status'    => array('publish'),
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query'     => array(
                array(
                    'key'      => 'rex_feed_schedule',
                    'value'    => $schedule,
                )
            ),
        );
        $query = new WP_Query( $args );
        
        return $query->get_posts();
    }

    /**
     * @param $schedule
     */
    public function wpfm_hourly_schedule_update_hook($schedule) {
     
        $this->feed_ids = $this->get_feeds_for_scheduler($schedule);
        
        $this->configure_merchant_object(false, $schedule );
    }

    public function wpfm_daily_schedule_update_hook($schedule) {
      
        $this->feed_ids = $this->get_feeds_for_scheduler($schedule);

        $this->configure_merchant_object(false, $schedule);
    }

    public function wpfm_weekly_schedule_update_hook($schedule) {
  
        $this->feed_ids = $this->get_feeds_for_scheduler($schedule);
        $this->configure_merchant_object(false, $schedule);
    }


    /**
     * Initialize Cron
     *
     * @since    2.0.0
     */
    public function rex_feed_cron_handler() {
        $hour = date('H');
        $schedule = 'hourly';
        
        $this->feed_ids = $this->get_feeds($schedule);

        $this->configure_merchant_object(true);
        $this->start_batch_processing();
    }


    /**
     * weekly cron
     */
    public function rex_feed_weekly_cron_handler() {
        $this->feed_ids = $this->get_feeds('weekly');
        
        $this->configure_merchant_object(true);
        $this->start_batch_processing();
    } 
    
    /**
     * weekly cron
     */
    public function rex_feed_daily_cron_handler() {
        $hour = date('H');
        
        if($hour == 07){
            $this->feed_ids = $this->get_feeds('daily');
            $this->configure_merchant_object(true);
            $this->start_batch_processing();
        }   
    }


    /**
     *
     * generate the feed generation payload
     *
     * @param $feed_id
     * @param $current_batch
     * @param $total_batches
     * @param $per_batch
     * @param $offset
     * @return array
     */
    private function get_feed_settings_payload($feed_id, $current_batch, $total_batches, $per_batch, $offset) {
	    $merchant                = get_post_meta( $feed_id, 'rex_feed_merchant', true );
	    $product_condition       = get_post_meta( $feed_id, 'rex_feed_product_condition', true );
	    $feed_config             = get_post_meta( $feed_id, 'rex_feed_feed_config', true );
	    $analytics               = get_post_meta( $feed_id, 'rex_feed_analytics_params_options', true );
	    $analytics_params        = $analytics === 'on' ? get_post_meta( $feed_id, 'rex_feed_analytics_params', true ) : [];
	    $feed_filter             = get_post_meta( $feed_id, 'rex_feed_feed_config_filter', true );
	    $product_scope           = get_post_meta( $feed_id, 'rex_feed_products', true );
	    $include_variations      = get_post_meta( $feed_id, 'rex_feed_variations', true ) === 'yes';
	    $variable_product        = get_post_meta( $feed_id, 'rex_feed_variable_product', true ) === 'yes';
	    $parent_product          = get_post_meta( $feed_id, 'rex_feed_parent_product', true ) === 'yes';
	    $exclude_hidden_products = get_post_meta( $feed_id, 'rex_feed_hidden_products', true ) === 'yes';
	    $append_variations       = get_post_meta( $feed_id, 'rex_feed_variation_product_name', true ) === 'yes';
	    $wpml                    = get_post_meta( $feed_id, 'rex_feed_wpml_language', true ) ? get_post_meta( $feed_id, 'rex_feed_wpml_language', true ) : '';
	    $wcml_currency           = get_post_meta( $feed_id, 'rex_feed_wcml_currency', true ) ? get_post_meta( $feed_id, 'rex_feed_wcml_currency', true ) : '';
	    $wcml                    = $wcml_currency ? true : false;
	    $feed_format             = get_post_meta( $feed_id, 'rex_feed_feed_format', true ) ?
		    get_post_meta( $feed_id, 'rex_feed_feed_format', true ) : 'xml';
	    $terms_array             = array();

	    if ( $product_scope !== 'all' && $product_scope !== 'filter' ) {
		    $terms = wp_get_post_terms( $feed_id, $product_scope );
		    if ( $terms ) {
			    foreach ( $terms as $term ) {

				    $terms_array[] = $term->slug;
			    }
		    }
	    }

	    $payload = array(
		    'merchant'                => $merchant,
		    'feed_format'             => $feed_format,
		    'feed_config'             => $feed_config,
		    'append_variations'       => $append_variations,
		    'info'                    => array(
			    'post_id'        => $feed_id,
			    'title'          => get_the_title( $feed_id ),
			    'desc'           => get_the_title( $feed_id ),
			    'total_batch'    => $total_batches,
			    'batch'          => $current_batch,
			    'per_page'       => $per_batch,
			    'offset'         => $offset,
			    'products_scope' => $product_scope,
			    'cats'           => $terms_array,
			    'tags'           => $terms_array,
		    ),
		    'feed_filter'             => $feed_filter,
		    'product_condition'       => $product_condition,
		    'include_variations'      => $include_variations,
		    'variable_product'        => $variable_product,
		    'parent_product'          => $parent_product,
		    'exclude_hidden_products' => $exclude_hidden_products,
		    'wpml_language'           => $wpml,
		    'wcml_currency'           => $wcml_currency,
		    'wcml'                    => $wcml,
		    'analytics'               => $analytics,
		    'analytics_params'        => $analytics_params,
	    );
        

        return $payload;
    }


    /**
     * configure merchant object
     * for feed generation
     *
     * @param bool $cron
     * @param string $schedule
     */
    private function configure_merchant_object( $cron = false, $schedule = 'hourly' ) {

	    if ( $this->feed_ids ) {
		    foreach ( $this->feed_ids as $key => $feed_id ) {

			    $products_info = Rex_Product_Feed_Ajax::get_product_number( array() );
			    $per_batch     = $products_info[ 'per_batch' ];
			    $total_batches = $products_info[ 'total_batch' ];
			    $offset        = 0;
			    $count         = 0;
			    $batch_size    = 20;
			    try {
				    for ( $i = 1; $i <= $total_batches; $i++ ) {
					    try {
						    if ( $cron ) {
							    /**
							     * if action triggered by WP-CRON
							     */
							    $payload = $this->get_feed_settings_payload( $feed_id, $i, $total_batches, $per_batch, $offset );
							    $merchant = Rex_Product_Feed_Factory::build( $payload, true );
							    $this->batch_array[ $feed_id ][] = $merchant;
						    }
						    else {
							    /**
							     * if action triggered by Action Scheduler
							     */
							    if ( $i == 1 ) {
								    update_post_meta( $feed_id, 'rex_feed_status', 'processing' );
								    update_post_meta( $feed_id, 'total_batch', $total_batches );
								    update_post_meta( $feed_id, 'batch_completed', $i );
								    $payload  = $this->get_feed_settings_payload( $feed_id, $i, $total_batches, $per_batch, $offset );
								    $merchant = Rex_Product_Feed_Factory::build( $payload, true );
								    $merchant->make_feed();
							    }
							    else {
								    as_schedule_single_action(
									    time(), "wpfm_regenerate_scheduled_feed", array(
									    'feed_id'       => $feed_id,
									    'current_batch' => $i,
									    'total_batches' => $total_batches,
									    'per_batch'     => $per_batch,
									    'offset'        => $offset,
								    ) );
							    }
						    }
						    $offset += (int) $per_batch;
						    $count++;
					    }
					    catch ( Exception $e ) {
						    $log = wc_get_logger();
						    $log->critical( $e->getMessage(), array( 'source' => 'wpfm-error' ) );
					    }
				    }
			    }
			    catch ( Exception $e ) {
				    $log = wc_get_logger();
				    $log->critical( $e->getMessage(), array( 'source' => 'wpfm-error' ) );
			    }
		    }
	    }
    }


    /**
     * schedule each batch for processing
     * as soon as possible
     *
     * @param $feed_id
     * @param $current_batch
     * @param $total_batches
     * @param $per_batch
     * @param $offset
     */
    public function wpfm_schedule_feed_processing( $feed_id, $current_batch, $total_batches, $per_batch, $offset ) {
        try {
            global $wpdb;
            $_batch_completed = get_post_meta( $feed_id, 'batch_completed', true);
            $batch_completed = (int) $_batch_completed + 1;
            update_post_meta($feed_id, 'batch_completed', $batch_completed);
            if( $batch_completed >= $total_batches ) {
                update_post_meta($feed_id, 'rex_feed_status', 'completed');
                $wpdb->delete(
                    $wpdb->prefix. 'actionscheduler_actions',
                    array(
                        'hook' => 'wpfm_regenerate_scheduled_feed',
                        'status' => 'completed',
                    )
                );
            }
            $payload = $this->get_feed_settings_payload($feed_id, $current_batch, $total_batches, $per_batch, $offset);
            $merchant = Rex_Product_Feed_Factory::build( $payload, true );
            $merchant->make_feed();
        } catch (Exception $e) {
            $log = wc_get_logger();
            $log->critical( $e->getMessage(), array('source' => 'wpfm-error') );
        }
    }



    /*
     * start the background process
     */
    private function start_batch_processing() {
	    if ( $this->batch_array ) {
		    foreach ( $this->batch_array as $feed_id => $batches ) {
			    if ( !( Rex_Product_Feed_Controller::check_feed_id_in_queue( $feed_id ) ) ) {
				    Rex_Product_Feed_Controller::add_id_to_feed_queue( $feed_id );
				    Rex_Product_Feed_Controller::update_feed_status( $feed_id, 'processing' );
			    }

			    foreach ( $batches as $merchant ) {
				    $this->background_process->push_to_queue( $merchant );
			    }

			    /*For Daily refresh interval queue for feeds that are updating at the same time*/

			    /*try {
				    if ( !( Rex_Product_Feed_Controller::check_feed_id_in_queue( $feed_id ) ) ) {
					    Rex_Product_Feed_Controller::add_id_to_feed_queue( $feed_id );
					    Rex_Product_Feed_Controller::update_feed_status( $feed_id, 'processing' );
				    }

				    foreach ( $batches as $merchant ) {
					    try {
						    $this->background_process->push_to_queue( $merchant );
					    } catch (Throwable $e) {
						    $log = wc_get_logger();
						    $log->critical( $e->getMessage(), array('source' => 'wpfm-error') );

						    Rex_Product_Feed_Controller::remove_id_from_feed_queue($feed_id);
						    Rex_Product_Feed_Controller::update_feed_status($feed_id, 'completed');
					    }
				    }
			    } catch (Throwable $e) {
				    $log = wc_get_logger();
				    $log->critical( $e->getMessage(), array('source' => 'wpfm-error') );

				    Rex_Product_Feed_Controller::remove_id_from_feed_queue($feed_id);
				    Rex_Product_Feed_Controller::update_feed_status($feed_id, 'completed');
			    }*/
		    }
	    }
	    $this->background_process->save()->dispatch();
    }

}