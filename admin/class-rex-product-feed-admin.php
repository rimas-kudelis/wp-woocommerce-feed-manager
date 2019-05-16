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
class Rex_Product_Feed_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Metabox instance of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $metabox    The current metabox of this plugin.
     */
    private  $cpt ;
    /**
     * Metabox instance of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $metabox    The current metabox of this plugin.
     */
    private  $metabox ;
    /**
     * Cron Handler
     *
     * @since    1.3.2
     * @access   private
     * @var      object    $cron    The current cron of this plugin.
     */
    private  $cron ;
    /**
     * Google merchant page
     *
     * @since    1.3.2
     * @access   private
     * @var      string
     */
    private  $google_screen_hook_suffix = null ;
    /**
     * Settings Page
     *
     * @since    1.3.2
     * @access   private
     * @var      string
     */
    private  $wpfm_settings_page = null ;
    /**
     * Category Mapping page
     *
     * @since    1.3.2
     * @access   private
     * @var      string
     */
    private  $category_mapping_screen_hook_suffix = null ;
    /**
     * Dashboard
     *
     * @since    1.3.2
     * @access   private
     * @var      string
     */
    private  $dashboard_screen_hook_suffix = null ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->cpt = new Rex_Product_CPT();
        $this->metabox = new Rex_Product_Metabox();
        $this->cron = new Rex_Product_Feed_Cron_Handler();
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles( $hook )
    {
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
        if ( $hook === 'edit.php' ) {
            return;
        }
        
        if ( $screen->post_type === 'product-feed' || in_array( $screen->id, array(
            $this->category_mapping_screen_hook_suffix,
            $this->dashboard_screen_hook_suffix,
            $this->google_screen_hook_suffix,
            $this->wpfm_settings_page
        ) ) ) {
            wp_enqueue_style(
                'materialize-icons',
                plugin_dir_url( __FILE__ ) . 'css/material-icon.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'materialize-css',
                plugin_dir_url( __FILE__ ) . 'css/materialize.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'easy-auto',
                plugin_dir_url( __FILE__ ) . 'css/easy-autocomplete.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'font-awesome',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/rex-product-feed-admin.css',
                array(),
                $this->version,
                'all'
            );
        }
    
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts( $hook )
    {
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
        if ( $hook === 'edit.php' ) {
            return;
        }
        
        if ( $screen->post_type === 'product-feed' || in_array( $screen->id, array(
            $this->category_mapping_screen_hook_suffix,
            $this->dashboard_screen_hook_suffix,
            $this->google_screen_hook_suffix,
            $this->wpfm_settings_page
        ) ) ) {
            wp_enqueue_script(
                'materialize-js',
                plugin_dir_url( __FILE__ ) . 'js/materialize.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/rex-product-feed-admin.js',
                array( 'jquery' ),
                $this->version,
                false
            );
        }
        
        wp_enqueue_script(
            'easy',
            plugin_dir_url( __FILE__ ) . 'js/wp-jquery.easy-autocomplete.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        wp_enqueue_script(
            'category-map',
            plugin_dir_url( __FILE__ ) . 'js/category-mapper.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /**
     * Remove a previously enqueued script by libraries
     * for the admin area.
     *
     * @since    1.0.0
     */
    public function dequeue_scripts( $hook )
    {
        $screen = get_current_screen();
        
        if ( $screen->post_type != 'product-feed' ) {
            wp_dequeue_script( 'cmb2-scripts' );
            wp_dequeue_script( 'cmb2-conditionals' );
            // wp_dequeue_script( 'wp-ajax-helper' );
        }
    
    }
    
    /**
     * Admin Notices
     *
     * @since    1.2.7
     */
    public function bwfm_admin_notices()
    {
        $total_feed = get_option( 'rex_total_feed' );
        $show_notice = get_option( 'rex_bwfm_notification_status' );
        
        if ( $total_feed == 'yes' and $show_notice != 'no' ) {
            ?>
            <div class="notice notice-info bwfm-review-notice" style="position: relative">
                <p><strong style="font-weight: bold"><?php 
            echo  __( 'Hey, I noticed you just created a new feed with 50 products using WC product feed manager – that’s awesome! Could you please do me a
                        BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', 'rex-product-feed' ) ;
            ?><br>~ Lincoln </strong></p>

                <ul>
                    <li>
                        <a href="https://wordpress.org/support/plugin/best-woocommerce-feed/reviews/#new-post" target="_blank" class="" style="font-weight: bold"><?php 
            echo  __( 'Ok, you deserve it', 'rex-product-feed' ) ;
            ?></a>
                    </li>
                    <li>
                        <a href="#" class="stop-bwfm-notice" style="font-weight: bold"><?php 
            echo  __( 'Nope, maybe later.', 'rex-product-feed' ) ;
            ?></a>
                    </li>
                    <li>
                        <a href="#" class="stop-bwfm-notice" style="font-weight: bold"><?php 
            echo  __( 'I already did.', 'rex-product-feed' ) ;
            ?></a>
                    </li>
                </ul>
                <button type="button" class="notice-dismiss bwfm-dismiss-notice"><span class="screen-reader-text"><?php 
            echo  __( 'Dismiss this notice.', 'rex-product-feed' ) ;
            ?></span></button>
            </div>
        <?php 
        }
        
        $activation_time = get_option( 'rex_bwfm_first_installation' );
        //            $user_review  =
        $current_time = time();
        $notice_start = 1209600;
        $interval = ( $current_time - $activation_time > $notice_start ? true : false );
        
        if ( $interval and $show_notice != 'no' ) {
            ?>
            <div class="notice notice-info bwfm-review-notice" style="position: relative; border-left-color: #00b4ff;">
                <p><strong style="font-weight: bold"><?php 
            echo  __( 'Hey, I noticed you are using WC product feed manager for over two weeks – that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', 'rex-product-feed' ) ;
            ?><br>~ Lincoln </strong></p>
                <ul>
                    <li style="display: inline;">
                        <span class="dashicons dashicons-external" style="font-size: 1.4em; padding-left: 10px"></span>
                        <a href="https://wordpress.org/support/plugin/best-woocommerce-feed/reviews/#new-post" target="_blank" class="" style="font-weight: bold; padding-left: 10px;"><?php 
            echo  __( 'Ok, you deserve it', 'rex-product-feed' ) ;
            ?></a>
                    </li>
                    <li style="display: inline;">
                        <span class="dashicons dashicons-calendar" style="font-size: 1.4em; padding-left: 10px"></span>
                        <a href="#" class="stop-bwfm-notice" style="font-weight: bold; padding-left: 10px;"><?php 
            echo  __( 'Nope, maybe later.', 'rex-product-feed' ) ;
            ?></a>
                    </li>
                    <li style="display: inline;">
                        <span class="dashicons dashicons-smiley" style="font-size: 1.4em; padding-left: 10px"></span>
                        <a href="#" class="stop-bwfm-notice" style="font-weight: bold; padding-left: 10px;"><?php 
            echo  __( 'I already did.', 'rex-product-feed' ) ;
            ?></a>
                    </li>
                </ul>
                <button type="button" class="notice-dismiss bwfm-dismiss-notice"><span class="screen-reader-text"><?php 
            echo  __( 'Dismiss this notice.', 'rex-product-feed' ) ;
            ?></span></button>
            </div>

        <?php 
        }
    
    }
    
    /**
     * Register CPT for the Plugin
     *
     * @since    1.0.0
     */
    public function register_cpt()
    {
        $this->cpt->register();
    }
    
    /**
     * Remove Bulk Edit for Feed
     *
     * @since    1.0.0
     */
    public function remove_bulk_edit( $actions )
    {
        unset( $actions['edit'] );
        return $actions;
    }
    
    /**
     * Remove Quick Edit for Feed
     *
     * @since    1.0.0
     */
    public function remove_quick_edit( $actions )
    {
        // Abort if the post type is not "books"
        if ( !is_post_type_archive( 'product-feed' ) ) {
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
    public function register_metaboxes()
    {
        $this->metabox->register();
    }
    
    /**
     * Register Plugin Admin Pages
     *
     * @since    1.0.0
     */
    public function load_admin_pages()
    {
        add_menu_page(
            __( 'Product Feed', 'rex-product-feed' ),
            __( 'Product Feed', 'rex-product-feed' ),
            'manage_options',
            'product-feed',
            null,
            WPFM_PLUGIN_DIR_URL . 'admin/icon/icon.png',
            5
        );
        add_submenu_page(
            'product-feed',
            __( 'Add New Feed', 'rex-product-feed' ),
            __( 'Add New Feed', 'rex-product-feed' ),
            'manage_options',
            'post-new.php?post_type=product-feed'
        );
        $this->category_mapping_screen_hook_suffix = add_submenu_page(
            'product-feed',
            __( 'Category Mapping', 'rex-product-feed' ),
            __( 'Category Mapping', 'rex-product-feed' ),
            'manage_options',
            'category_mapping',
            __CLASS__ . '::category_mapping'
        );
        $this->google_screen_hook_suffix = add_submenu_page(
            'product-feed',
            __( 'Google Merchant Settings', 'rex-product-feed' ),
            __( 'Google Merchant Settings', 'rex-product-feed' ),
            'manage_options',
            'merchant_settings',
            __CLASS__ . '::merchant_settings'
        );
        $this->dashboard_screen_hook_suffix = add_submenu_page(
            'product-feed',
            __( 'Dashboard', 'rex-product-feed' ),
            __( 'Dashboard', 'rex-product-feed' ),
            'manage_options',
            'bwfm-dashboard',
            __CLASS__ . '::user_dashboard'
        );
    }
    
    public static function category_mapping()
    {
        require plugin_dir_path( __FILE__ ) . '/partials/category_mapping.php';
    }
    
    public static function user_dashboard()
    {
        require plugin_dir_path( __FILE__ ) . '/partials/on_boarding.php';
    }
    
    public static function merchant_settings()
    {
        require plugin_dir_path( __FILE__ ) . '/partials/merchant_settings.php';
    }
    
    /**
     *  Feed Cron handler
     *  @since    1.3.2
     */
    public function activate_schedule_update()
    {
        $this->cron->rex_feed_cron_handler();
    }
    
    /*
     * Admin Footer Styles
     */
    function rex_admin_footer_style()
    {
        echo  '<style>
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
        </style>' ;
    }
    
    /**
     * is_edit_page
     * function to check if the current page is a post edit page
     *
     * @param  string  $new_edit what page to check for accepts new - new post page ,edit - edit post page, null for either
     * @return boolean
     */
    function is_edit_page( $new_edit = null )
    {
        global  $pagenow ;
        if ( !is_admin() ) {
            return false;
        }
        
        if ( $new_edit == "edit" ) {
            return in_array( $pagenow, array( 'post.php' ) );
        } elseif ( $new_edit == "new" ) {
            //check for new post page
            return in_array( $pagenow, array( 'post-new.php' ) );
        } else {
            return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
        }
    
    }
    
    /**
     * @param $pages
     * @return mixed
     */
    public function wpfm_themify_top_pages_modify( $pages )
    {
        global  $typenow ;
        
        if ( $this->is_edit_page( 'edit' ) && "product-feed" == $typenow ) {
            unset( $pages[0] );
            unset( $pages[1] );
        } elseif ( $this->is_edit_page( 'new' ) && "product-feed" == $typenow ) {
            unset( $pages[0] );
            unset( $pages[1] );
        }
        
        return $pages;
    }

}