<?php

/**
 * The Rex_Product_Feed_Cron_Handler class file that
 * handle the schedule feed update
 *
 * @link       https://rextheme.com
 * @since      2.0.0
 *
 * @package    Rex_Product_Feed_Cron_Handler
 * @subpackage Rex_Product_Feed/admin
 */

class Rex_Product_Feed_Cron_Handler {


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


    /**
     * Initialize the class and set its properties.
     *
     * @since    2.0.0
     */
    public function __construct() {
        $this->background_process = new Rex_Product_Feed_Background_Process();
    }



    /**
     * Initialize Cron
     *
     * @since    2.0.0
     */
    public function rex_feed_cron_handler() {

        if ( !rex_is_woocommerce_active() ) {
            write_log('WooCommerce is not installed');
            exit;
        }

        $this->feed_ids = $this->get_feeds();
        $this->process_handler($this->feed_ids);

    }



    /**
     * Get all feeds
     *
     * @since    2.0.0
     */
    public function get_feeds() {
        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'product-feed',
            'post_status'    => 'publish',
            'fields'         => 'ids',
        );
        return get_posts($args);
    }



    /**
     * Start cron processor
     *
     * @since    2.0.0
     */
    public function process_handler($feed_ids) {
        $hour = date('H');
        if ($feed_ids) {
            foreach ($feed_ids as $feed_id) {
                $schedule = get_post_meta($feed_id, 'rex_feed_schedule', true);
                switch ($schedule) {
                    case 'hourly':
                        if(!(Rex_Product_Feed_Controller::check_feed_id_in_queue($feed_id))){
                            Rex_Product_Feed_Controller::add_id_to_feed_queue($feed_id);
                            Rex_Product_Feed_Controller::update_feed_status($feed_id, 'processing');
                            $this->background_process->push_to_queue( $feed_id );
                        }
                        break;
                    case 'daily':
                        if($hour == 07){
                            if(!(Rex_Product_Feed_Controller::check_feed_id_in_queue($feed_id))){
                                Rex_Product_Feed_Controller::add_id_to_feed_queue($feed_id);
                                Rex_Product_Feed_Controller::update_feed_status($feed_id, 'processing');
                                $this->background_process->push_to_queue( $feed_id );
                            }
                        }
                        break;
                    default:
                        break;
                }

            }
            $this->background_process->save()->dispatch();
        }


    }
}


