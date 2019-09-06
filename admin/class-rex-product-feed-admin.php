<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin
 * @author     RexTheme <info@rextheme.com>
 */

/**
 * Class Rex_Product_Feed_Admin
 */
class Rex_Product_Feed_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;


    /**
     * The ID of this plugin.
     *
     * @since    3.0
     * @access   private
     * @var      string    $plugin_basename    The ID of this plugin.
     */
    private $plugin_basename;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;


    /**
     * Metabox instance of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $metabox    The current metabox of this plugin.
     */
    private $cpt;


    /**
     * Admin notices
     *
     * @since    2.5
     * @access   private
     * @var      object    $notices
     */
    private $notices;

    /**
     * Metabox instance of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $metabox    The current metabox of this plugin.
     */
    private $metabox;


    /**
     * Cron Handler
     *
     * @since    1.3.2
     * @access   private
     * @var      object    $cron    The current cron of this plugin.
     */
    private $cron;


    /**
     * Google merchant page
     *
     * @since    1.3.2
     * @access   private
     * @var      string
     */
    private $google_screen_hook_suffix = null;


    /**
     * Category Mapping page
     *
     * @since    1.3.2
     * @access   private
     * @var      string
     */
    private $category_mapping_screen_hook_suffix = null;


    /**
     * Dashboard
     *
     * @since    1.3.2
     * @access   private
     * @var      string
     */
    private $dashboard_screen_hook_suffix = null;


    /**
     * WPFM pro
     *
     * @since    3.0
     * @access   private
     * @var      string
     */
    private $wpfm_pro_submenu = null;


    /**
     * WPFM Support menu
     *
     * @since    3.0
     * @access   private
     * @var      string
     */
    private $wpfm_support_menu = null;



    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->plugin_basename      = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_name . '.php' );
        $this->version     = $version;
        $this->cpt         = new Rex_Product_CPT;
        $this->metabox     = new Rex_Product_Metabox;
        $this->cron        = new Rex_Product_Feed_Cron_Handler();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook) {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Rex_Product_Feed_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Rex_Product_Feed_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $screen = get_current_screen();
        if( ($hook === 'edit.php' ) ){
            return;
        }

        if ( $screen->post_type === 'product-feed' || in_array($screen->id, apply_filters('wpfm_page_hooks', array($this->category_mapping_screen_hook_suffix, $this->dashboard_screen_hook_suffix, $this->google_screen_hook_suffix)))) {
            wp_enqueue_style( 'materialize-icons', plugin_dir_url( __FILE__ ) . 'css/material-icon.css', array(), $this->version, 'all' );
            wp_enqueue_style( 'materialize-css', plugin_dir_url( __FILE__ ) . 'css/materialize.min.css', array(), $this->version, 'all' );
            wp_enqueue_style( 'easy-auto', plugin_dir_url( __FILE__ ) . 'css/easy-autocomplete.min.css', array(), $this->version, 'all' );
            wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
            wp_enqueue_style( 'jquery-ui-styles', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rex-product-feed-admin.css', array(), $this->version, 'all' );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook) {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Rex_Product_Feed_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Rex_Product_Feed_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $db_version = get_option('rex_wpfm_db_version');
        if($db_version < 3) {
            wp_enqueue_script( 'rex-wpfm-global-js', plugin_dir_url( __FILE__ ) . 'js/rex-product-feed-global-admin.js', array( 'jquery'), $this->version, false );
            wp_localize_script( 'rex-wpfm-global-js', 'rex_wpfm_ajax',
                array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'ajax_nonce' => wp_create_nonce('rex-wpfm-ajax'),
                )
            );
        }

        $screen = get_current_screen();
        if( ($hook === 'edit.php' ) ){
            return;
        }

        if ( $screen->post_type === 'product-feed' || in_array($screen->id, apply_filters('wpfm_page_hooks', array($this->category_mapping_screen_hook_suffix, $this->dashboard_screen_hook_suffix, $this->google_screen_hook_suffix)))) {
            wp_enqueue_script( 'jquery-ui-autocomplete' );
            wp_enqueue_script(
                'materialize-js',
                plugin_dir_url( __FILE__ ) . 'js/materialize.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                'jquery-stop-watch',
                plugin_dir_url( __FILE__ ) . 'js/jquery.stopwatch.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/rex-product-feed-admin.js',
                array( 'jquery' ),
                $this->version,
                true
            );

            wp_enqueue_script( 'easy', plugin_dir_url( __FILE__ ) . 'js/wp-jquery.easy-autocomplete.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( 'category-map', plugin_dir_url( __FILE__ ) . 'js/category-mapper.js', array( 'jquery', 'jquery-ui-autocomplete' ), $this->version, true );
        }


    }

    /**
     * Remove a previously enqueued script by libraries
     * for the admin area.
     *
     * @since    1.0.0
     */
    public function dequeue_scripts($hook) {
        $screen = get_current_screen();
        if ( $screen->post_type != 'product-feed' ) {
            wp_dequeue_script( 'cmb2-scripts' );
            wp_dequeue_script( 'cmb2-conditionals' );
        }
    }
    

    /**
     * Admin Notices
     *
     * @since    1.2.7
     */
    public function rex_wpfm_admin_notices() {

        $show_notice = get_option('rex_bwfm_notification_status');

        $activation_time = get_option('rex_bwfm_first_installation');

        $current_time = time();
        $notice_start = 1209600;
        $interval   = ($current_time - $activation_time)>$notice_start ? true : false;

        if ($interval AND $show_notice !='no') {?>
            <div class="notice notice-info bwfm-review-notice" style="position: relative; border-left-color: #00b4ff;">
                <p><strong style="font-weight: bold"><?php echo __( 'Hey, I noticed you are using WC product feed manager for over two weeks – that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', 'rex-product-feed' ) ?><br>~ Lincoln </strong></p>
                <ul>
                    <li style="display: inline;">
                        <span class="dashicons dashicons-external" style="font-size: 1.4em; padding-left: 10px"></span>
                        <a href="https://wordpress.org/support/plugin/best-woocommerce-feed/reviews/#new-post" target="_blank" class="" style="font-weight: bold; padding-left: 10px;"><?php echo __('Ok, you deserve it','rex-product-feed')?></a>
                    </li>
                    <li style="display: inline;">
                        <span class="dashicons dashicons-calendar" style="font-size: 1.4em; padding-left: 10px"></span>
                        <a href="#" class="stop-bwfm-notice" style="font-weight: bold; padding-left: 10px;"><?php echo __( 'Nope, maybe later.', 'rex-product-feed' ) ?></a>
                    </li>
                    <li style="display: inline;">
                        <span class="dashicons dashicons-smiley" style="font-size: 1.4em; padding-left: 10px"></span>
                        <a href="#" class="stop-bwfm-notice" style="font-weight: bold; padding-left: 10px;"><?php echo __( 'I already did.', 'rex-product-feed' ) ?></a>
                    </li>
                </ul>
                <button type="button" class="notice-dismiss bwfm-dismiss-notice"><span class="screen-reader-text"><?php echo __( 'Dismiss this notice.', 'rex-product-feed' ) ?></span></button>
            </div>

        <?php }
    }



    /**
     * Register CPT for the Plugin
     *
     * @since    1.0.0
     */
    public function register_cpt() {
        $this->cpt->register();
    }

    /**
     * Remove Bulk Edit for Feed
     *
     * @since    1.0.0
     */
    public function remove_bulk_edit( $actions ){
        unset( $actions['edit'] );
        return $actions;
    }

    /**
     * Remove Quick Edit for Feed
     *
     * @since    1.0.0
     */
    public function remove_quick_edit( $actions ){
        // Abort if the post type is not "books"
        if ( ! is_post_type_archive( 'product-feed' ) ) {
            return $actions;
        }

        // Remove the Quick Edit link
        if ( isset( $actions['inline hide-if-no-js'] ) ) {
            unset( $actions['inline hide-if-no-js'] );
        }

        // Return the set of links without Quick Edit
        return $actions;
    }

    /**
     * Register All the Metaboxes for the admin area.
     *
     * @since    1.0.0
     */
    public function register_metaboxes() {
        $this->metabox->register();
    }


    /**
     * Register Plugin Admin Pages
     *
     * @since    1.0.0
     */
    public function load_admin_pages() {
        add_menu_page( __( 'Product Feed', 'rex-product-feed' ), __( 'Product Feed', 'rex-product-feed' ), 'manage_options', 'product-feed', null, WPFM_PLUGIN_DIR_URL . 'admin/icon/icon.png', 5 );
        add_submenu_page('product-feed',  __( 'Add New Feed', 'rex-product-feed' ), __( 'Add New Feed', 'rex-product-feed' ), 'manage_options', 'post-new.php?post_type=product-feed');
        $this->category_mapping_screen_hook_suffix = add_submenu_page('product-feed', __('Category Mapping', 'rex-product-feed'), __('Category Mapping', 'rex-product-feed'), 'manage_options', 'category_mapping',  __CLASS__ .'::category_mapping');
        $this->google_screen_hook_suffix =  add_submenu_page('product-feed', __('Google Merchant Settings', 'rex-product-feed'), __('Google Merchant Settings', 'rex-product-feed'), 'manage_options', 'merchant_settings',  __CLASS__ .'::merchant_settings');
        $this->dashboard_screen_hook_suffix = add_submenu_page('product-feed', __('Settings', 'rex-product-feed'), __('Settings', 'rex-product-feed'), 'manage_options', 'wpfm_dashboard',  __CLASS__ .'::user_dashboard');
        $this->wpfm_support_menu = add_submenu_page('product-feed', '', __('Support', 'rex-product-feed'), 'manage_options', 'wpfm_support',  __CLASS__ .'::wpfm_support');


        $is_premium = apply_filters('wpfm_is_premium_activate', false);
        if(!$is_premium) $this->wpfm_pro_submenu = add_submenu_page('product-feed', '', '<span class="dashicons dashicons-star-filled" style="font-size: 17px; color: #2BBBAC;"></span> ' . __( 'Go Pro', 'rex-product-feed' ), 'manage_options', 'go_wpfm_pro', __CLASS__ .'::wpfm_redirect_to_pro');

        do_action('wpfm_pro_license_page');

        /**
         * WPFM action links
         */
        add_filter('plugin_action_links_' . $this->plugin_basename, array( $this, 'wpfm_plugin_action_links' ));
    }

    /**
     *
     */
    public static function category_mapping(){
        require plugin_dir_path(__FILE__) . '/partials/category_mapping.php';
    }



    public static function user_dashboard(){
        require plugin_dir_path(__FILE__) . '/partials/on_boarding.php';
    }


    /**
     *
     */
    public static function merchant_settings(){
        require plugin_dir_path(__FILE__) . '/partials/merchant_settings.php';
    }


    public static function wpfm_redirect_to_pro() {
        wp_redirect('https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro');
    }


    /**
     * WPFM redirect to support link
     */
    public static function wpfm_support() {
        $support_link = apply_filters('wpfm_support_link', 'https://wordpress.org/support/plugin/best-woocommerce-feed');
        wp_redirect($support_link);
    }


    /**
     * WPFM action links
     * @param $links
     * @return array
     */
    public  function wpfm_plugin_action_links($links) {
        $is_premium = apply_filters('wpfm_is_premium_activate', false);
        $dashboard_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url('admin.php?page=wpfm_dashboard' ), __( 'Dashboard', 'rex-product-feed' ) );
        array_unshift( $links, $dashboard_link );
        if(!$is_premium) $links['wpfm_go_pro'] = sprintf( '<a href="%1$s" target="_blank" class="wpfm-plugins-gopro" style="color: #2BBBAC; font-weight: bold; ">%2$s</a>', 'https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro' , __( 'Go Pro', 'rex-product-feed' ) );
        return $links;
    }


    /**
     * Plugin row meta.
     * Adds row meta links to the plugin list table
     * @param $plugin_meta
     * @param $plugin_file
     * @return array
     */
    public function wpfm_plugin_row_meta($plugin_meta, $plugin_file) {
        if ( WPFM_PLUGIN_BASE === $plugin_file ) {
            $row_meta = [
                'docs' => '<a href="https://rextheme.com/docs/woocommerce-product-feed/" aria-label="' . esc_attr( __( 'View WPFM Documentation', 'rex-product-feed' ) ) . '" target="_blank">' . __( 'Docs & FAQs', 'rex-product-feed' ) . '</a>',
//                'video' => '<a href="https://www.youtube.com/watch?v=WYRgnMFQGH8&list=PLelDqLncNWcVoPA7T4eyyfzTF0i_Scbnq" aria-label="' . esc_attr( __( 'View WPFM Video Tutorials', 'rex-product-feed' ) ) . '" target="_blank">' . __( 'Video Tutorials', 'rex-product-feed' ) . '</a>',
            ];
            $plugin_meta = array_merge( $plugin_meta, $row_meta );
        }

        return $plugin_meta;
    }



    /**
     *  Feed Cron handler
     *  @since    1.3.2
     */
    public function activate_schedule_update() {
        $this->cron->rex_feed_cron_handler();
    }


    /**
     * Available merchants filter
     * @param $array
     * @return array
     */
    public function wpfm_available_merchants_status($array){
        $free_merchants = array(
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
        $array = array_merge($free_merchants, $array);
        $pro_merchants = array(
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
        foreach ($pro_merchants as $key=>$merchant) {
            if(array_key_exists($key, $array)) {
                unset($key, $merchant);
            }else {
                $array[$key] = $merchant;
            }
        }
        return $array;
    }



    /*
     * Admin Footer Styles
     */
    function rex_admin_footer_style() {
        echo '<style>
                .wpfm-db-update-loader {
                  display: none;
                  width: 20px;
                  height: 20px;
                }
                .blink span {
                  font-size: 35px;
                  animation-name: blink;
                  animation-duration: 1.4s;
                  animation-iteration-count: infinite;
                  animation-fill-mode: both;
                }
                
                .blink span:first-child {
                  margin-left: 5px;
                }
                
                .blink span:nth-child(2) {
                  animation-delay: .2s;
                }
                
                .blink span:nth-child(3) {
                  animation-delay: .4s;
                }
                
                @keyframes blink {
                  0% {
                    opacity: .2;
                  }
                  20% {
                    opacity: 1;
                  }
                  100% {
                    opacity: .2;
                  }
                }
        </style>';
    }

}
