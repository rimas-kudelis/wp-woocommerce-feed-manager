<?php

/**
 * Fired during plugin activation
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/includes
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Product_Feed_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        /*
         * Schedule Feed Update
         * @since 1.3.3
         */
        if (! wp_next_scheduled ( 'rex_feed_schedule_update' )) {
            wp_schedule_event(time(), 'hourly', 'rex_feed_schedule_update');
        }


	    update_option('rex_bwfm_first_installation', time());
	    update_option('rex_bwfm_notification_status', 'yes');
	}

}
