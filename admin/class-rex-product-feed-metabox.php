<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines all the Metaboxes for Products
 *
 * @package    Rex_Product_Metabox
 * @subpackage Rex_Product_Feed/admin
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Product_Metabox {

    private $prefix = 'rex_feed_';

    /**
     * Register all metaboxes.
     *
     * @since    1.0.0
     */
    public function register() {
        $is_premium = apply_filters('wpfm_is_premium_activate', false);
        $this->products();
        if(!$is_premium) $this->upgrade_notice();
        $this->feed_config();
        $this->feed_file();
        $this->google_merchant();



        add_filter( 'cmb2_select_attributes', array($this, 'wpfm_merchant_dropdown'), 10, 4 );
    }


    /**
     * Products Selection Metabox
     *
     * @since    1.0.0
     */
    private function products(){

        $box = new_cmb2_box( array(
            'id'            => $this->prefix . 'products',
            'title'         => esc_html__( 'Products', 'rex-product-feed' ),
            'object_types'  => array( 'product-feed' ), // Post type
        ) );


        $box->add_field( array(
            'name'             => __('Products', 'rex-product-feed' ),
            'desc'             => __('Select products to create feed for.', 'rex-product-feed' ),
            'id'               => $this->prefix . 'products',
            'type'             => 'select',
            'show_option_none' => false,
            'default'          => 'all',
            'options'          => array(
                'all'           => __( 'All Published Products', 'rex-product-feed' ),
                'filter'        => __( 'Custom Filter', 'rex-product-feed' ),
                'product_cat'   => __( 'Category Filter', 'rex-product-feed' ),
                'product_tag'   => __( 'Tag Filter', 'rex-product-feed' ),
            ),
            'before_row' => array($this, 'progress_config_cb'),
        ) );


        // filter product
        $box->add_field( array(
            'id'        => $this->prefix . 'config_filter_title',
            'name'      => 'Configure Feed Filters and Rules',
            'type'      => 'title',
            'after_row' => array($this, 'atts_filter_cb'),
        ) );

        $box->add_field( array(
            'name'           => 'Product Category',
            'desc'           => 'Select Category',
            'id'             => $this->prefix . 'cats',
            'taxonomy'       => 'product_cat', //Enter Taxonomy Slug
            'type'           => 'taxonomy_multicheck_inline',
            'text'           => array(
                'no_terms_text' => 'Sorry, no product categories could be found.'
            ),
            'attributes' => array(
                'data-conditional-id'    => $this->prefix . 'products',
                'data-conditional-value' => 'product_cat',
            ),
        ) );

        $box->add_field( array(
            'name'           => 'Product Tag',
            'desc'           => 'Select Tag',
            'id'             => $this->prefix . 'tags',
            'taxonomy'       => 'product_tag', //Enter Taxonomy Slug
            'type'           => 'taxonomy_multicheck_inline',
            'text'           => array(
                'no_terms_text' => 'Sorry, no product tags could be found.'
            ),
            'attributes' => array(
                'data-conditional-id'    => $this->prefix . 'products',
                'data-conditional-value' => 'product_tag',
            ),
        ) );


        /*
         * Schedule Time
         */
        $box->add_field( array(
            'name'           => __( 'Refresh Interval', 'rex-product-feed' ),
            'desc'           => __( 'Feed Schedule Update', 'rex-product-feed' ),
            'id'             => $this->prefix . 'schedule',
            'type'           => 'radio_inline',
            'options' => array(
                'no'        => __( 'No Interval', 'rex-product-feed' ),
                'daily'     => __( 'Daily', 'rex-product-feed' ),
                'hourly'    => __( 'Hourly', 'rex-product-feed' ),
            ),
            'default' => 'no',
        ) );


        /*
         * Include/Exclude Variations
         */
        $box->add_field( array(
            'name'           => __( 'Include Product Variations', 'rex-product-feed' ),
            'desc'           => __( 'Include/Exclude Products Variations', 'rex-product-feed' ),
            'id'             => $this->prefix . 'variations',
            'type'           => 'radio_inline',
            'options' => array(
                'yes'       => __( 'Yes', 'rex-product-feed' ),
                'no'        => __( 'No', 'rex-product-feed' ),
            ),
            'default' => 'yes',
        ) );


        /*
         * Exclude parent product (group product)
         */
        $box->add_field( array(
            'name'           => __( 'Include Parent Product (Grouped Product)', 'rex-product-feed' ),
            'desc'           => __( 'Include/Exclude Parent Product', 'rex-product-feed' ),
            'id'             => $this->prefix . 'parent_product',
            'type'           => 'radio_inline',
            'options' => array(
                'yes'       => __( 'Yes', 'rex-product-feed' ),
                'no'        => __( 'No', 'rex-product-feed' ),
            ),
            'default' => 'yes',
        ) );


        /*
         * WPML Support
         */
        if ( function_exists('icl_object_id') ) {
            global $sitepress;
            $active_languages = $sitepress->get_active_languages();
            $no_of_languages = count($active_languages);
            $option_array = array();

            if($no_of_languages>0) {
                foreach ($active_languages as $key => $value){
                    $option_array[$key] = $value['display_name'];
                }
                $box->add_field( array(
                    'name'          => __( 'WPML Language', 'rex-product-feed' ),
                    'desc'          => __( 'WPML Language', 'rex-product-feed' ),
                    'id'            => $this->prefix . 'wpml_language',
                    'type'          => 'radio_inline',
                    'options'       => $option_array,
                    'default'       => array_keys($option_array)[0],
                ) );
            }

        }

    }

    /**
     * Defines Metaboxes for Feed Configuration
     *
     * @return void
     * @author RexTheme
     **/
    private function feed_config(){

        $_merchants = array(
            'custom'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Custom'
            ),
            'google'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Google'
            ),
            'google_Ad'    => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Google AD'
            ),
            'facebook'     => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Facebook'
            ),
            'amazon'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Amazon'
            ),
            'ebay'         => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'eBay'
            ),
            'adroll'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'AdRoll'
            ),
            'nextag'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Nextag'
            ),
            'pricegrabber' => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Pricegrabber'
            ),
            'bing'         => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Bing'
            ),
            'kelkoo'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Kelkoo'
            ),
            'become'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Become'
            ),
            'shopzilla'    => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'ShopZilla'
            ),
            'shopping'     => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Shopping'
            )
        );
        $merchants = get_option('rex_wpfm_merchant_status');
        if($merchants) {
            $_merchants = array_merge($_merchants, $merchants);
        }
        $merchant_lists = [];
        foreach ($_merchants as $key => $merchant) {
            if($merchant['status'])
                $merchant_lists[$key] = $merchant['name'];
        }
        reset($merchant_lists);
        $default_merchant = key($merchant_lists);


        $box = new_cmb2_box( array(
            'id'            => $this->prefix . 'conf',
            'title'         => esc_html__( 'Feed Configuration', 'rex-product-feed' ),
            'object_types'  => array( 'product-feed' ), // Post type
        ) );

        $box->add_field( array(
            'name'             => __('Merchant Type', 'rex-product-feed' ),
            'desc'             => __('Select Merchant Type of the Feed.', 'rex-product-feed' ),
            'id'               => $this->prefix . 'merchant',
            'type'             => 'select',
            'show_option_none' => false,
            'default'          => $default_merchant,
            'options'          => $merchant_lists,
        ) );

        do_action('wpfm_merchant_settings_field', $box, $this->prefix);

        $box->add_field( array(
            'name'             => __('File Format', 'rex-product-feed' ),
            'desc'             => __('Select Format of the Feed.', 'rex-product-feed' ),
            'id'               => $this->prefix . 'feed_format',
            'type'             => 'select',
            'options'          => array(
                'xml'          => __( 'XML', 'rex-product-feed' ),
                'text'         => __( 'TEXT', 'rex-product-feed' ),
                'csv'          => __( 'CSV', 'rex-product-feed' ),
            ),
            'attributes' => array(
                'data-conditional-id'    => $this->prefix . 'merchant',
                'data-conditional-value' => wp_json_encode(apply_filters('wpfm_merchant_fixed_format', array( 'custom', 'facebook', 'nextag', 'pricegrabber', 'bing', 'kelkoo', 'amazon', 'ebay', 'become' , 'shopzilla', 'shopping', 'google_Ad', 'adroll'))),
            ),
        ) );

        $box->add_field( array(
            'id'        => $this->prefix . 'config_heading',
            'name'      => 'Configure Feed Attributes and their values.',
            'type'      => 'title',
            'after_row' => array($this, 'atts_config_cb'),
        ) );

    }

    /**
     * Display Feed Config Metabox.
     *
     * @return void
     * @author RexTheme
     **/
    public function atts_config_cb($field_args, $field){
        $feed_rules    = get_post_meta( $field->object_id, $this->prefix . 'feed_config', true );
        $feed_template = new Rex_Feed_Template_Google($feed_rules);
        echo '<div id="rex-feed-config" class="rex-feed-config">';
        require plugin_dir_path( __FILE__ ) . 'partials/loading-spinner.php';
        require plugin_dir_path( __FILE__ ) . 'partials/feed-config-metabox-display.php';
        echo '<br><a id="rex-new-attr" class="waves-effect waves-light btn-large "><i class="material-icons left">add</i>'.__('Add New Attribute','rex-product-feed').'</a>';
        echo '</div>';
    }


    /**
     * Display Feed Config Metabox.
     *
     * @return void
     * @author RexTheme
     **/
    public function progress_config_cb($field_args, $field){

        echo '<div id="rex-feed-progress" class="rex-feed-progress">';
        require plugin_dir_path( __FILE__ ) . 'partials/progress-bar.php';
        echo '</div>';
    }



    /**
     * Display Feed Filter Metabox.
     *
     * @return void
     * @author RexTheme
     **/
    public function atts_filter_cb($field_args, $field){
        $feed_filter_rules      = get_post_meta( $field->object_id, $this->prefix . 'feed_config_filter', true );
        $feed_filter            = new Rex_Product_Filter($feed_filter_rules);


        echo '<div id="rex-feed-config-filter" class="rex-feed-config-filter">';
        require plugin_dir_path( __FILE__ ) . 'partials/loading-spinner.php';
        require plugin_dir_path( __FILE__ ) . 'partials/feed-config-metabox-display-filter.php';
        echo '<br><a id="rex-new-attr" class="waves-effect waves-light btn-large "><i class="material-icons left">add</i>'.__('Add New Filter', 'rex-product-feed').'</a>';
        echo '</div>';
    }



    /**
     * Defines Metaboxes for Feed
     *
     * @return void
     * @author RexTheme
     **/
    private function feed_file(){

        $box = new_cmb2_box( array(
            'id'            => $this->prefix . 'file_link',
            'title'         => esc_html__( 'Feed URL', 'rex-product-feed' ),
            'object_types'  => array( 'product-feed' ), // Post type
            'context'       => 'side',
            'priority'      => 'low'
        ) );

        $box->add_field( array(
            'name'             => __('Your Feed URL', 'rex-product-feed' ),
            'desc'             => __('', 'rex-product-feed' ),
            'id'               => $this->prefix . 'xml_file',
            'type'             => 'text',
            'sanitization_cb'  => array($this, 'sanitize_xml_file'),
            'after_field'      => array($this, 'after_field_xml_file_cb'),
            'default'          => '',
            'attributes'  => array(
                'readonly' => 'readonly',
                'disabled' => 'disabled',
            ),
        ) );

    }



    private function upgrade_notice(){

        $box = new_cmb2_box( array(
            'id'            => $this->prefix . 'upgrade_notice',
            'title'         => esc_html__( 'Why upgrade to Premium Version?', 'rex-product-feed' ),
            'object_types'  => array( 'product-feed' ), // Post type
            'context'       => 'side',
            'priority'      => 'low'
        ) );

        $box->add_field( array(
            'name' => '',
            'type' => 'title',
            'id'   => $this->prefix . 'features_text',
            'after_field'      => array($this, 'after_field_upgrade_notice_cb'),
        ) );
    }


    /**
     * @param $field_args
     * @param $field
     */
    public function after_field_upgrade_notice_cb ($field_args, $field) {

        echo '<ol class="parent">';
        echo '<li class="item">Supports more than 50 products.</li>';
        echo '<li class="item">Access to a elite support team.</li>';
        echo '<li class="item">Supports YITH brand attributes.</li>';
        echo '<li class="item">Custom Filtering.</li>';
        echo '<li class="item">Dynamic Attribute.</li>';
        echo '<li class="item">Custom field support - Brand,GTIN,MPN,UPC And EAN.</li>';
        echo '<li class="item">Fix WooCommerce\'s (JSON-LD) structure data bug</li>';
        echo '</ol>';

        echo '<a class="waves-effect waves-light btn" target="_blank" href="https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro">Upgrade to pro</a>';
    }


    /**
     * Output a message if the current page has the id of "2" (the about page)
     * @param  object $field_args Current field args
     * @param  object $field      Current field object
     */
    public function after_field_xml_file_cb($field_args, $field){
        $feed_url = get_post_meta( $field->object_id, $this->prefix . 'xml_file', true );
        // Only show feed url not empty.
        if ( strlen($feed_url) > 0 ){
            $url = esc_url( get_post_meta( $field->object_id, 'rex_feed_xml_file', true ) );
            echo '<a target="_blank" class="btn waves-effect waves-light" href="' . $url . '">
              <i class="material-icons">open_in_new</i>'.__('View Feed', 'rex-product-feed').'</a> ';
            echo '<a target="_blank" class="btn waves-effect waves-light" href="' . $url . '" download>
            <i class="material-icons">system_update_alt</i>'.__('Download Feed', 'rex-product-feed').'</a>';
        }
    }


    /**
     * Defines Metaboxes for Google Merchant
     *
     * @since 4.0.0
     * @return void
     * @author RexTheme
     **/
    private function google_merchant(){

        $box = new_cmb2_box( array(
            'id'            => $this->prefix . 'google_merchant',
            'title'         => esc_html__( 'Send to Google Merchant', 'rex-product-feed' ),
            'object_types'  => array( 'product-feed' ), // Post type
            'context'       => 'side',
            'priority'      => 'low',
            'show_on_cb'    =>  array($this, 'cmb_only_show_google'),
        ) );

        $box->add_field( array(
            'name'    => __('Target Country', 'rex-product-feed' ),
            'id'      => $this->prefix . 'google_target_country',
            'type'    => 'text',
            'default' => 'US',
            'attributes'  => array(
                'required'    => 'required',
            ),
            'before_row'    => array($this, 'google_merchant_desc'),
        ) );

        $box->add_field( array(
            'name'    => __('Target Language', 'rex-product-feed' ),
            'id'      => $this->prefix . 'google_target_language',
            'type'    => 'text',
            'default' => 'en',
            'attributes'  => array(
                'required'    => 'required',
            ),
        ) );

        $box->add_field( array(
            'name'             =>  __('Schedule', 'rex-product-feed' ),
            'id'               => $this->prefix . 'google_schedule',
            'type'             => 'select',
            'default'          => 'hourly',
            'options'          => array(
                'monthly'   => __( 'Monthly', 'rex-product-feed' ),
                'weekly'    => __( 'Weekly', 'rex-product-feed' ),
                'hourly'    => __( 'Hourly', 'rex-product-feed' ),
            ),
        ) );

        $month_array = range(1,31);
        array_unshift($month_array,"");
        unset($month_array[0]);
        $box->add_field( array(
            'name'             =>  __('Select day of month', 'rex-product-feed' ),
            'id'               => $this->prefix . 'google_schedule_month',
            'type'             => 'select',
            'default'          => 1,
            'options'          => $month_array,
            'attributes' => array(
                'data-conditional-id'    => $this->prefix . 'google_schedule',
                'data-conditional-value' => 'monthly',
            ),
        ));

        $box->add_field( array(
            'name'             =>  __('Select day of week', 'rex-product-feed' ),
            'id'               => $this->prefix . 'google_schedule_week_day',
            'type'             => 'select',
            'default'          => 'monday',
            'options'          => array(
                'monday' => 'Monday',
                'tuesday' => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
                'saturday' => 'Saturday',
                'sunday' => 'Sunday',
            ),
            'attributes' => array(
                'data-conditional-id'    => $this->prefix . 'google_schedule',
                'data-conditional-value' => 'weekly',
            ),
        ));

        $box->add_field( array(
            'name'             =>  __('Select Hour', 'rex-product-feed' ),
            'id'               => $this->prefix . 'google_schedule_time',
            'type'             => 'select',
            'default'          => 1,
            'options'          => range(0,23),
            'after_field'      => array($this, 'after_field_google_merchant_cb'),
        ) );
    }


    /**
     * Output a message if the current page has the id of "2" (the about page)
     * @param  object $field_args Current field args
     * @param  object $field      Current field object
     */
    function google_merchant_desc( $field_args, $field ) {
       echo __('<p class="google-desc">'.__('Please note that Google has fixed abbreviations for Location and Language. For example, the abbreviation for target location, 
                United States is US and the abbreviation for language, English is en.', 'rex-product-feed').' <a href="http://www.unicode.org/repos/cldr/tags/latest/common/main/en.xml" target="_blank">'.__('Click here', 'rex-product-feed').'</a> '.__('to see the list of all abbreviations set by Google.', 'rex-product-feed').'</p>', 'rex-product-feed');
    }



    /**
     * Only display a metabox if the merchant is google
     * @param  object $cmb CMB2 object
     * @return bool        True/false whether to show the metabox
     */
    public function cmb_only_show_google($feed) {
        $status = get_post_meta( $feed->object_id, 'rex_feed_merchant', 1 );
        return 'google' === $status;
    }


    /**
     * Output a message if the feed merchant is google
     * @param  object $field_args Current field args
     * @param  object $field      Current field object
     */
    public function after_field_google_merchant_cb($field_args, $field){
        $feed_merchant = get_post_meta( $field->object_id, 'rex_feed_merchant', true );
        // Only show feed url not empty.
        if ( $feed_merchant === 'google' ){
            $rex_google_merchant = new Rex_Google_Merchant_Settings_Api();
            $message = __('Oops!! Access token has expired 😕. Please authenticate token for Google Merchant Shop to be able to send feed.', 'rex-product-feed');
            if (!($rex_google_merchant->is_authenticate())){
                echo sprintf('<p class="google-status">%s <a href="%s">'. __('Authenticate', 'rex-product-feed') .'</a> </p>',
                    $message,
                    admin_url( 'admin.php?page=merchant_settings'));
            }else {
                echo '<a class="btn waves-effect waves-light" id="send-to-google" href="#">
                        '. __('Send to google merchant', 'rex-product-feed') .'
                      </a> ';
            }
            echo '<div class="rex-google-status"></div>';
        }
    }


    /**
     * Update the XML File URL on Sanitization Hook.
     *
     * @return string
     * @author RexTheme
     **/
    public function sanitize_xml_file($value, $field_args, $field){
        $format = $field->data_to_save['rex_feed_feed_format'];
        $path  = wp_upload_dir();
        if($format == 'xml'){
            $path  = $path['baseurl'] . '/rex-feed' . "/feed-{$field->object_id}.xml";
        }elseif ($format == 'text'){
            $path  = $path['baseurl'] . '/rex-feed' . "/feed-{$field->object_id}.txt";
        }elseif ($format == 'csv'){
            $path  = $path['baseurl'] . '/rex-feed' . "/feed-{$field->object_id}.csv";
        }
        return esc_url( $path );
    }


    /**
     * @param $args
     * @param $defaults
     * @param $field_object
     * @param $field_types_object
     * @return mixed
     */
    public function wpfm_merchant_dropdown( $args, $defaults, $field_object, $field_types_object ) {

        $is_premium = apply_filters('wpfm_is_premium', false);
        if ($is_premium)
            return $args;

        // Only do this for the field we want (vs all select fields)
        if ( 'rex_feed_merchant' != $field_types_object->_id() ) {
            return $args;
        }

        // free vs pro merchants
        $merchants = apply_filters('wpfm_available_merchants_status', get_option('rex_wpfm_merchant_status'));
        $_merchants = array(
            'custom'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Custom'
            ),
            'google'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Google'
            ),
            'google_Ad'    => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Google AD'
            ),
            'facebook'     => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Facebook'
            ),
            'amazon'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Amazon'
            ),
            'ebay'         => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'eBay'
            ),
            'adroll'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'AdRoll'
            ),
            'nextag'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Nextag'
            ),
            'pricegrabber' => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Pricegrabber'
            ),
            'bing'         => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Bing'
            ),
            'kelkoo'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Kelkoo'
            ),
            'become'       => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Become'
            ),
            'shopzilla'    => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'ShopZilla'
            ),
            'shopping'     => array(
                'free'  => true,
                'status'    => 1,
                'name'  => 'Shopping'
            )
        );
        if($merchants) {
            $_merchants = array_merge($_merchants, $merchants);
        }
        $_pro_merchants = array(
            'ebay_mip'     => array(
                'free'  => false,
                'status'    => 0,
                'name'  => 'eBay (MIP)'
            ),
            'ebay_seller'     => array(
                'free'  => false,
                'status'    => 0,
                'name'  => 'eBay Seller Center'
            ),
            'bol'       => array(
                'free'  => false,
                'status'    => 0,
                'name'  => 'Bol.com'
            ),
            'wish'       => array(
                'free'  => false,
                'status'    => 0,
                'name'  => 'Wish.com'
            ),
            'fruugo'       => array(
                'free'  => false,
                'status'    => 0,
                'name'  => 'Fruugo'
            ),
            'leguide'       => array(
                'free'  => false,
                'status'    => 0,
                'name'  => 'Leguide'
            ),
            'connexity'       => array(
                'free'  => false,
                'status'    => 0,
                'name'  => 'Connexity'
            ),
            'drm'     => array(
                'free'  => false,
                'status'    => 0,
                'name'  => 'Google Remarketing (DRM)'
            )

        );
        $_merchants = array_merge($_merchants, $_pro_merchants);

        if(!$is_premium) {
            $_merchants = array_merge($_merchants, $_pro_merchants);
        }

        $saved_value = $field_object->escaped_value();
        $value       = $saved_value ? $saved_value : $field_object->args( 'default' );
        $options_string = '';
        $options_string .= $field_types_object->select_option( array(
            'label'		=> __( 'Select an option' ),
            'value'		=> '',
            'checked'	=> ! $value,
            'disabled'  => false
        ));

        if(array_key_exists($value, $_pro_merchants)) {
            $value = array_keys($_merchants)[1];
        }

        foreach ($_merchants as $key => $merchant) {
            if($merchant['free']) {
                if($merchant['status']) {
                    $options_string .= sprintf( "\t" . '<option value="%s" %s>%s</option>', $key, selected($value, $key, false ), $merchant['name'] ) . "\n";
                }
            }
            else
                $options_string .= sprintf( "\t" . '<option class="pro-merchants" value="%s"  disabled>%s</option>', $key, $merchant['name'] ) . "\n";
        }

        reset($_merchants);
        $default_merchant = key($_merchants);

        $defaults['options'] = $options_string;
        $defaults['default'] = $default_merchant;

        return $defaults;
    }
}
