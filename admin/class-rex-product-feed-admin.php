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
        $this->cron        = new Rex_Feed_Scheduler();
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
        if ( $screen->post_type === 'product-feed' || in_array($screen->id, apply_filters('wpfm_page_hooks', array($this->category_mapping_screen_hook_suffix, $this->dashboard_screen_hook_suffix, $this->google_screen_hook_suffix, $this->wpfm_pro_submenu)))) {
            wp_enqueue_style( 'font-awesome', WPFM_PLUGIN_ASSETS_FOLDER . 'css/font-awesome.min.css', array(), $this->version, 'all' );
            wp_enqueue_style( 'wpfm-vendor', WPFM_PLUGIN_ASSETS_FOLDER . 'css/vendor.min.css', array(), $this->version, 'all' );
            wp_enqueue_style($this->plugin_name.'-select2', WPFM_PLUGIN_ASSETS_FOLDER . 'css/select2.min.css', array(), $this->version, 'all');
            wp_enqueue_style( 'style-css', WPFM_PLUGIN_ASSETS_FOLDER . 'css/style.css', array(), $this->version, 'all' );
            wp_style_add_data( 'style-css', 'rtl', 'replace' );
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
	    if ( $db_version < 3 ) {
            $current_screen = get_current_screen();

            if ( gettype( $current_screen ) === 'object' && property_exists( $current_screen, 'base') && property_exists( $current_screen, 'post_type') ){
                if ( $current_screen->base === 'post' && $current_screen->post_type === 'product-feed' ) {
                    if ( $current_screen->action === 'add' ) {
	                    $current_screen = 'add';
                    }
                    elseif ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] === 'edit' ) {
	                    $current_screen = 'rex_feed_edit';
                    }
                    else {
	                    $current_screen = '';
                    }
                }
                elseif ( $current_screen->base === 'product-feed_page_wpfm_dashboard' ) {
	                $current_screen = $current_screen->base;
                }
            }
            else {
                $current_screen = '';
            }

		    wp_enqueue_script( 'rex-wpfm-global-js', WPFM_PLUGIN_ASSETS_FOLDER . 'js/rex-product-feed-global-admin.js', array( 'jquery' ), $this->version, false );
		    wp_localize_script(
			    'rex-wpfm-global-js', 'rex_wpfm_ajax',
			    array(
				    'ajax_url'       => admin_url( 'admin-ajax.php' ),
				    'ajax_nonce'     => wp_create_nonce( 'rex-wpfm-ajax' ),
				    'is_premium'     => apply_filters( 'wpfm_is_premium', false ),
				    'feed_id'        => get_the_ID(),
                    'category_mapping_url' => admin_url( 'admin.php?page=category_mapping' ),
				    'current_screen' => $current_screen
			    )
		    );
	    }

        $screen = get_current_screen();
        if( ($hook === 'edit.php' ) ){
            return;
        }

        if ( $screen->post_type === 'product-feed' || in_array($screen->id, apply_filters('wpfm_page_hooks', array($this->dashboard_screen_hook_suffix, $this->google_screen_hook_suffix, $this->wpfm_pro_submenu)))) {
            wp_enqueue_script( 'jquery-ui-autocomplete' );
            wp_enqueue_script(
                'jquery-stop-watch',
                WPFM_PLUGIN_ASSETS_FOLDER . 'js/jquery.stopwatch.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                'jquery-nice-select',
                WPFM_PLUGIN_ASSETS_FOLDER . 'js/jquery.nice-select.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name . '-select2',
                WPFM_PLUGIN_ASSETS_FOLDER . 'js/select2.min.js',
                array( 'jquery' ),
                $this->version
            );
            wp_enqueue_script(
                $this->plugin_name,
                WPFM_PLUGIN_ASSETS_FOLDER . 'js/rex-product-feed-admin.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_localize_script(
                $this->plugin_name, 'rex_wpfm_admin_translate_strings',
                array(
                    'google_cat_map_btn' => __( 'Configure Category Mapping', 'rex-product-feed' ),
                )
            );
            wp_enqueue_script(
                'jquery-cookie',
                'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/latest/js.cookie.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
        }

        if ($screen->id == $this->category_mapping_screen_hook_suffix) {
            wp_enqueue_script(
                    'category-map',
                    WPFM_PLUGIN_ASSETS_FOLDER . 'js/category-mapper.js',
                    array( 'jquery', 'jquery-ui-autocomplete' ),
                    $this->version,
                    true
            );
            wp_localize_script(
                'category-map', 'rex_wpfm_cat_map_translate_strings',
                array(
                    'update_btn' => __('Update', 'rex-product-feed'),
                    'update_and_close_btn' => __('Update & Close', 'rex-product-feed'),
                    'delete_btn' => __('Delete', 'rex-product-feed'),
                )
            );
        }
    }


    /**
     * Register CPT for the Plugin
     *
     * @since    1.0.0
     */
    public function register_cpt() {
        $this->cpt->register();
    }


    public function register_purge_button( $post )
    {
        if( $post->post_type === 'product-feed' ){
            $html = '';
            $html .= '<button id="btn_on_feed" ';
            $html .= 'class="wpfm-purge-cache btn_on_feed">Purge Cache';
            $html .= '<i class="fa fa-spinner fa-pulse fa-fw" style="display: none"></i></button>';
            
            print $html;
        }
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
     * Duplicate posts as draft
     *
     */
	function wpfm_duplicate_post_as_draft(){
        global $wpdb;
        if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'wpfm_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
          wp_die('No post to duplicate has been supplied!');
        }

        if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
          return;

        $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
        $post = get_post( $post_id );
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        if (isset( $post ) && $post != null) {
            $title = '';
            $name = '';
            if($post->post_title == ''){
                $title = 'Untitled-duplicate';
            }else{
              $title = $post->post_title.'-'.'duplicate';
            }
            
            if($post->post_name == ''){
                $name = 'Untitled-duplicate';
            }else{
              $name = $post->post_name.'-'.'duplicate';
            }
          $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
          );

          $cat = get_the_terms( $post->ID, 'product_cat');
          $tag = get_the_terms( $post->ID, 'product_tag');
         
          
          

          $new_post_id = wp_insert_post( $args );
          if($cat){
            foreach($cat as $cat){
                wp_set_post_terms( $new_post_id, $cat->term_id, 'product_cat' );
              }
          }
          if($tag){
            foreach($tag as $tag){
                wp_set_post_terms( $new_post_id, $tag->name, 'product_tag' );
              }
          }
          $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
          foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
          }
         
          $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
          if (count($post_meta_infos)!=0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
              $meta_key = $meta_info->meta_key;
              if( $meta_key == '_wp_old_slug' ) continue;
              $meta_value = addslashes($meta_info->meta_value);
              $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
          }
          wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
          exit;
        } else {
          wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    }


    /**
     * duplicate post link for feed-item
     *
     * @param $actions
     * @param $post
     * @return mixed
     */
	function wpfm_duplicate_post_link( $actions, $post ) {
        $user = wp_get_current_user();
        if ( !$post->post_type== 'product-feed') {
            return $actions;
        }
		if ( in_array( 'administrator', (array) $user->roles ) ) {
			if (current_user_can('edit_posts')) {
				$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=wpfm_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
			}
		}
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

        add_menu_page( __( 'Product Feed', 'rex-product-feed' ), __( 'Product Feed', 'rex-product-feed' ), 'manage_woocommerce', 'product-feed', null, WPFM_PLUGIN_ASSETS_FOLDER . 'icon/icon.png', 20 );
        add_submenu_page('product-feed', __('Add New Feed', 'rex-product-feed'), __('Add New Feed', 'rex-product-feed'), 'manage_woocommerce', 'post-new.php?post_type=product-feed');

        
        $this->category_mapping_screen_hook_suffix = add_submenu_page('product-feed', __('Category Mapping', 'rex-product-feed'), __('Category Mapping', 'rex-product-feed'), 'manage_woocommerce', 'category_mapping',  __CLASS__ .'::category_mapping');
        $this->google_screen_hook_suffix =  add_submenu_page('product-feed', __('Google Merchant Settings', 'rex-product-feed'), __('Google Merchant Settings', 'rex-product-feed'), 'manage_woocommerce', 'merchant_settings',  __CLASS__ .'::merchant_settings');
        $this->dashboard_screen_hook_suffix = add_submenu_page('product-feed', __('Settings', 'rex-product-feed'), __('Settings', 'rex-product-feed'), 'manage_woocommerce', 'wpfm_dashboard',  __CLASS__ .'::user_dashboard');
        $this->wpfm_support_menu = add_submenu_page('product-feed', '', __('Support', 'rex-product-feed'), 'manage_woocommerce', 'wpfm_support',  __CLASS__ .'::wpfm_support');
        $is_premium = apply_filters('wpfm_is_premium_activate', false);

        
        
        if(!$is_premium) {
            $this->wpfm_pro_submenu = add_submenu_page('product-feed', '', '<span class="dashicons dashicons-star-filled" style="font-size: 17px; color:#1fb3fb;"></span> ' . __( 'Go Pro', 'rex-product-feed' ), 'manage_woocommerce', 'go_wpfm_pro', __CLASS__ .'::wpfm_redirect_to_pro');
        } else {
            $this->wpfm_pro_submenu = add_submenu_page(
                'product-feed',
                __('License', 'rex-product-feed'),
                __('License', 'rex-product-feed'),
                'manage_options',
                'wpfm-license',
                __CLASS__ . '::wpfm_license_menu_render'
            );
        }

        /**
         * WPFM action links
         */
        add_filter('plugin_action_links_' . $this->plugin_basename, array( $this, 'wpfm_plugin_action_links' ));
    }


    public static function wpfm_license_menu_render()
    {
        require plugin_dir_path(__FILE__) . '/partials/rex-product-feed-pro-license.php';
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
        $support_link = apply_filters('wpfm_support_link', 'https://wordpress.org/support/plugin/best-woocommerce-feed/#new-topic-0');
        wp_redirect($support_link);
        exit();
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


    public function register_weekly_cron() {
        if( ! wp_next_scheduled( 'rex_feed_weekly_update' ) ) {
            wp_schedule_event( time(), 'weekly', 'rex_feed_weekly_update' );
        }
        if( ! wp_next_scheduled( 'rex_feed_daily_update' ) ) {
            wp_schedule_event( time(), 'daily', 'rex_feed_daily_update' );
        }
    }


    /**
     *  Feed Cron handler
     *  @since    1.3.2
     */
    public function activate_schedule_update() {
        $this->cron->rex_feed_cron_handler();
    }


    /**
     * Weekly cron handler
     */
    public function activate_weekly_update() {
        $this->cron->rex_feed_weekly_cron_handler();
    }
    
    /**
     * Daily cron handler
     */
    public function activate_daily_update() {
        $this->cron->rex_feed_daily_cron_handler();
    }


    /*
     * Admin Footer Styles
     */
    function rex_admin_footer_style() {
        echo '<style>

                .wpfm-bf-wrapper {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    max-width: 1510px;
                    margin: 0 auto;
                }
                .wpfm-bf-wrapper .wpfm-logo,
                .wpfm-bf-wrapper .wpfm-bf-button{
                    flex: 0 0 25%;
                    margin: 10px;
                }
                .wpfm-bf-wrapper .wpfm-bf-text{
                    flex: 0 0 40%;
                }
                .wpfm-bf-text p,
                .wpfm-bf-text h3{
                    color: #fff;
                    
                }
                .wpfm-bf-text p{
                    font-size: 18px;
                    margin: 0;
                }
                
                .wpfm-bf-text h3{
                    font-size: 32px;
                    font-weight: 700;
                    margin: 15px 0;
                    line-height: 1.1;
                }
                .wpfm-bf-button p {
                    font-size: 18px;
                    color: #fff;
                    margin-bottom: 25px;
                } 
                .wpfm-bf-button a {
                    background-color: #fff;
                    padding: 10px 20px;
                    color: #00b4ff;
                    font-size: 30px;
                    border-radius: 4px;
                    margin: 15px 0;
                    text-decoration: none;
                }
                p.wpfm-bf-coupon {
                    margin-top: 25px;
                }
                
                
                .wpfm-black-friday-notice {
                    position: relative;
                    padding: 0;
                    margin: 0!important;
                    border: none;
                    background: transparent;
                    box-shadow: none;
                }
                .wpfm-black-friday-notice img{
                    display: block;
                    max-width: 100%;
                }
                .wpfm-black-friday-notice .notice-dismiss {
                    top: 8px;
                    right: 10px;
                    padding: 0;
                }
                .wpfm-black-friday-notice .notice-dismiss:before {
                    color: #fff;
                    font-size: 22px;
                }
                @media  (max-width: 1199px) {
                    .wpfm-bf-wrapper {
                        flex-direction: column;
                        text-align: center;
                        padding-top: 20px;
                    }
                  .wpfm-bf-wrapper .wpfm-logo,
                    .wpfm-bf-wrapper .wpfm-bf-button{
                        flex: 0 0 100%;
                    }
                    .wpfm-bf-wrapper .wpfm-bf-text{
                        flex: 0 0 100%;
                    }
                }
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
                #woocommerce-product-data ul.wc-tabs li.wpfm_wc_custom_tabs a:before { 
                    font-family: WooCommerce; 
                    content: \'\e006\'; 
                 }
                 #wpfm_product_meta strong{
                    color: #1FB3FB;
                    padding: 10px;
                 }
                .bwfm-review-notice {
                  display: flex;
                  flex-flow: row wrap;
                  align-items: center;
                  padding: 20px; }
                  .bwfm-review-notice .wpfm-logo {
                    width: 80px; }
                    .bwfm-review-notice .wpfm-logo img {
                      display: block; }
                  .bwfm-review-notice .wpfm-notice-content {
                    width: calc(100% - 110px);
                    padding-left: 30px; }
                    .bwfm-review-notice .wpfm-notice-content .wpfm-notice-title {
                      font-size: 24px;
                      color: #222; }

            .rextheme-black-friday-offer {
                padding: 0!important;
                border: 0;
            }
            .rextheme-black-friday-offer img {
                display: block;
                width: 100%;
            }
            .rextheme-black-friday-offer .notice-dismiss {
                top: 4px;
                right: 6px;
                padding: 4px;
                background: #fff;
                border-radius: 100%;
            }
            .rextheme-black-friday-offer .notice-dismiss:before {
                content: "\f335";
                font-size: 20px;
            }
        </style>';
    }


    /**
     * Add Pixel to WC pages
     * @throws Exception
     */
    public function wpfm_enable_facebook_pixel() {
        global $product;
        $currency = function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : 'USD';
        $wpfm_fb_pixel_enabled = get_option('wpfm_fb_pixel_enabled', 'no');
        $viewContent = "";
        if($wpfm_fb_pixel_enabled == 'yes') {
            $wpfm_fb_pixel_data = get_option('wpfm_fb_pixel_value');
            if(isset($wpfm_fb_pixel_data)) {
                if(is_product()){
                    $product_id = $product->get_id();
                    $price = $product->get_price();
                    $product_title = $product->get_name();
                    $cats = '';
                    $terms = wp_get_post_terms( $product_id, 'product_cat' , array( 'orderby' => 'term_id' ));

                    if ( empty( $terms ) || is_wp_error( $terms ) ){
                        $cats = '';
                    }else {
                        foreach ( $terms as $term ) {
                            $cats .= $term->name . ',';
                        }
                        $cats = rtrim($cats, ",");
                        $cats = str_replace("&amp;","&", $cats);
                    }

                    if($product->is_type('variable')) {
                        $variation_id = $this->wpfm_find_matching_product_variation( $product, $_GET );
                        $total_get = count($_GET);
                        if($total_get>0 && $variation_id > 0) {
                            $product_id = $variation_id;
                            $variable_product = wc_get_product($variation_id);
                            $content_type = 'product';
                            if(is_object($variable_product)) {
                                $formatted_price = wc_format_decimal( $variable_product->get_price(), wc_get_price_decimals());
                            }else {
                                $prices  = $product->get_variation_prices();
                                $lowest  = reset( $prices['price'] );
                                $formatted_price = wc_format_decimal( $lowest, wc_get_price_decimals());
                            }
                        }
                        else {
                            $variation_ids = $product->get_visible_children();
                            $prices  = $product->get_variation_prices();
                            $lowest  = reset( $prices['price'] );
                            $formatted_price = wc_format_decimal( $lowest, wc_get_price_decimals());
                            $product_ids = '';
                            foreach ($variation_ids as $variation) {
                                $product_ids .= "'" .$variation. "'" . ',';
                            }
                            $product_id = rtrim($product_ids, ",");
                            $content_type = 'product_group';
                        }
                    }
                    else {
                        $formatted_price = wc_format_decimal( $price, wc_get_price_decimals() );
                        $content_type = 'product';
                    }
                    $viewContent = "fbq(\"track\",\"ViewContent\",{content_category:\"$cats\", content_name:\"$product_title\", content_type:\"$content_type\", content_ids:[\"$product_id\"],value:\"$formatted_price\",currency:\"$currency\"});";
                    ?>

                <?php }
                elseif (is_product_category()) {
                    global $wp_query;
                    $product_ids = wp_list_pluck( $wp_query->posts, "ID" );
                    $term = get_queried_object();

                    $product_id = '';

                    foreach ($product_ids as $id) {
                        $product = wc_get_product($id);
                        if ( ! is_object( $product ) ) {
                            continue;
                        }

                        if ( ! $product->is_visible() ) {
                            continue;
                        }

                        if($product->is_type('simple')){
                            $product_id .= $id.',';;
                        }elseif ($product->is_type('variable')) {
                            $variations = $product->get_visible_children();
                            foreach ($variations as $variation) {
                                $product_id .= $variation. ',';
                            }
                        }
                    }
                    $product_id = rtrim($product_id, ",");
                    $category_name = $term->name;
                    $category_path = $this->get_the_term_path($term->term_id, 'product_cat', ' > ');
                    $viewContent = "fbq(\"trackCustom\",\"ViewCategory\",{content_category:\"$category_path\", content_name:\"$category_name\", content_type:\"product\", content_ids:\"[$product_id]\"});";
                }
                elseif (is_search()) {
                    $term = get_queried_object();
                    $search_term = sanitize_text_field($_GET['s']);
                    global $wp_query;
                    $product_ids = wp_list_pluck( $wp_query->posts, "ID" );

                    $product_id = '';

                    foreach ($product_ids as $id) {
                        $product = wc_get_product($id);
                        if ( ! is_object( $product ) ) {
                            continue;
                        }

                        if ( ! $product->is_visible() ) {
                            continue;
                        }

                        if($product->is_type('simple')){
                            $product_id .= $id.',';;
                        }elseif ($product->is_type('variable')) {
                            $variations = $product->get_visible_children();
                            foreach ($variations as $variation) {
                                $product_id .= $variation. ',';
                            }
                        }
                    }
                    $product_id = rtrim($product_id, ",");
                    $viewContent = "fbq(\"trackCustom\",\"Search\",{search_string:\"$search_term\", content_type:\"product\", content_ids:\"[$product_id]\"});";
                }
                elseif (is_cart() || is_checkout()) {
                    if ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) {
                        $order_key = sanitize_text_field($_GET['key']);
                        if(!empty($order_key)) {
                            $order_id = wc_get_order_id_by_order_key($order_key);
                            $order = wc_get_order($order_id);
                            $order_items = $order->get_items();
                            $order_real = 0;
                            $contents = "";
                            if (!is_wp_error($order_items)) {
                                foreach ($order_items as $item_id => $order_item) {
                                    $prod_id = $order_item->get_product_id();
                                    $prod_quantity = $order_item->get_quantity();
                                    $order_subtotal = $order_item->get_subtotal();
                                    $order_subtotal_tax = $order_item->get_subtotal_tax();
                                    $order_real += number_format(($order_subtotal + $order_subtotal_tax), 2);
                                    $contents .= "{'id': '$prod_id', 'quantity': $prod_quantity},";
                                }
                            }
                            $contents = rtrim($contents, ",");
                            $viewContent = "fbq(\"trackCustom\",\"Purchase\",{content_type:\"product\", value:\"$order_real\", currency:\"$currency\", contents:\"[$contents]\"});";
                        }
                    }else {
                        $cart_real = 0;
                        $contents = "";
                        foreach( WC()->cart->get_cart() as $cart_item ){
                            $product_id = $cart_item['product_id'];
                            if ($cart_item['variation_id'] > 0) {
                                $product_id = $cart_item['variation_id'];
                            }$contents .= "'" .$product_id. "'" . ',';
                            $line_total = $cart_item['line_total'];
                            $line_tax = $cart_item['line_tax'];
                            $cart_real += number_format(($line_total + $line_tax), 2);
                        }
                        $contents = rtrim($contents, ",");
                        if(is_cart()) {
                            $viewContent = "fbq(\"trackCustom\",\"AddToCart\",{ content_type:\"product\", value:\"$cart_real\", currency:\"$currency\", content_ids:\"[$contents]\"});";
                        }elseif (is_checkout()) {
                            $viewContent = "fbq(\"trackCustom\",\"InitiateCheckout\",{content_type:\"product\", value:\"$cart_real\", currency:\"$currency\", content_ids:\"[$contents]\"});";
                        }
                    }
                }
            }

            ?>
            <!-- Facebook pixel code - added by RexTheme.com -->
            <script type="text/javascript">
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                    n.queue=[];t=b.createElement(e);t.async=!0;
                    t.src=v;s=b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t,s)}(window, document,'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '<?php print"$wpfm_fb_pixel_data";?>');
                fbq('track', 'PageView');
                <?php
                if(strlen($viewContent) > 2){
                    print"$viewContent";
                }
                ?>
            </script>
            <noscript>
                <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo  "$wpfm_fb_pixel_data";?>&ev=PageView&noscript=1"/>
            </noscript>
            <!-- End Facebook Pixel Code -->
        <?php }
    }


    /**
     * @param $id
     * @param $taxonomy
     * @param string $sep
     * @param bool $is_visited
     * @return array|string|WP_Error|WP_Term|null
     */
    protected function get_the_term_path( $id, $taxonomy, $sep = '', $is_visited =  false) {
        $term = get_term( $id, $taxonomy );
        if ( is_wp_error( $term ) )
            return $term;
        $name = $term->name;
        if($is_visited) {
            $path = '';
        }else {
            $path = 'Home';
        }
        if($term->parent && ( $term->parent != $term->term_id )) {
            $path .= $this->get_the_term_path($term->parent, $taxonomy, $sep, true);
        }
        $path .= $sep.$name;
        return $path;
    }


    /**
     * Find matching product variation
     *
     * @param WC_Product $product
     * @param array $attributes
     * @return int Matching variation ID or 0.
     * @throws Exception
     */
    protected function wpfm_find_matching_product_variation( $product, $attributes ) {
        foreach( $attributes as $key => $value ) {
            if( strpos( $key, 'attribute_' ) === 0 ) {
                continue;
            }
            unset( $attributes[ $key ] );
            $attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
        }
        if( class_exists('WC_Data_Store') ) {
            $data_store = WC_Data_Store::load( 'product' );
            return $data_store->find_matching_product_variation( $product, $attributes );
        } else {
            return $product->get_matching_variation( $attributes );
        }
    }



    /**
     * black friday notice markup
     * @since 6.1.0
     */
    public function rt_black_friday_offer_notice() {
        $current_time = time();
        $date_now = date("Y-m-d", $current_time);
        $notice_info = get_option('rt_bf_notice', array(
            'show_notice' => 'yes',
            'updated_at' => $current_time,
        ));
        if($this->is_wpfm_page()) {
            if( $notice_info['show_notice'] === 'yes' && $date_now <= '2020-12-01' ) { ?>
                <div class="rextheme-black-friday-offer notice notice-warning is-dismissible">
                    <a href="https://rextheme.com/black-friday/?wpfm=1" target="_blank">
                        <div class="bf-banner-container">
                            <img src="<?php echo WPFM_PLUGIN_ASSETS_FOLDER . 'icon/black-friday.png'?>" style="max-width: 100%;" alt="black-friday-offer">
                        </div>
                    </a>
                </div>
            <?php }
        }

    }


    /**
     * check to see if it is WPFM page
     * @return bool
     * @since 6.1.0
     */
    public function is_wpfm_page() {
        global $pagenow;
        global $typenow;
        $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        if($typenow == 'product-feed' &&  $pagenow === 'edit.php') {
            return true;
        }
        elseif($typenow == 'product-feed' &&  $pagenow === 'post.php') {
            return true;
        }
        elseif (in_array($page, array('category_mapping', 'merchant_settings', 'wpfm_dashboard', 'wpfm-license'))) {
            return true;
        }
        return false;
    }


	/**
	 * Trigger review request on new feed publish
	 */
    public function rex_feed_show_review_request( $post_id, WP_Post $post ){

	    $show_review_request = get_option( 'rex_feed_review_request' );

	    if ( empty( $show_review_request ) ) {
	        $data = array (
                'show' => true,
                'time' => '',
                'frequency' => 'immediate'
            );
		    update_option( 'rex_feed_review_request', $data );
	    }
    }


	/**
	 * Save feed meta data on post saving as draft
	 */
	public function save_draft_feed_meta( $post_id, WP_Post $post )
	{
		if ( !current_user_can( "edit_post", $post_id ) ) {
			return $post_id;
		}

		if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$slug = "product-feed";
		if ( $slug != $post->post_type ) {
			return $post_id;
		}
		if ( isset( $_POST[ 'rex_feed_schedule' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_schedule', $_POST[ 'rex_feed_schedule' ] );
		}
		if ( isset( $_POST[ 'rex_feed_products' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_products', $_POST[ 'rex_feed_products' ] );
		}
		if ( isset( $_POST[ 'rex_feed_variable_product' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_variable_product', $_POST[ 'rex_feed_variable_product' ] );
		}
		if ( isset( $_POST[ 'rex_feed_variations' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_variations', $_POST[ 'rex_feed_variations' ] );
		}
		if ( isset( $_POST[ 'rex_feed_parent_product' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_parent_product', $_POST[ 'rex_feed_parent_product' ] );
		}
		if ( isset( $_POST[ 'rex_feed_variation_product_name' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_variation_product_name', $_POST[ 'rex_feed_variation_product_name' ] );
		}
		if ( isset( $_POST[ 'rex_feed_hidden_products' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_hidden_products', $_POST[ 'rex_feed_hidden_products' ] );
		}
		if ( isset( $_POST[ 'rex_feed_skip_row' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_skip_row', $_POST[ 'rex_feed_skip_row' ] );
		}
		if ( isset( $_POST[ 'rex_feed_aelia_currency' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_aelia_currency', $_POST[ 'rex_feed_aelia_currency' ] );
		}
		if ( isset( $_POST[ 'rex_feed_wcml_currency' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_wcml_currency', $_POST[ 'rex_feed_wcml_currency' ] );
		}
		if ( isset( $_POST[ 'rex_feed_google_destination' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_google_destination', $_POST[ 'rex_feed_google_destination' ] );
		}
		if ( isset( $_POST[ 'rex_feed_google_target_country' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_google_target_country', $_POST[ 'rex_feed_google_target_country' ] );
		}
		if ( isset( $_POST[ 'rex_feed_google_target_language' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_google_target_language', $_POST[ 'rex_feed_google_target_language' ] );
		}
		if ( isset( $_POST[ 'rex_feed_google_schedule_month' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_google_schedule_month', $_POST[ 'rex_feed_google_schedule_month' ] );
		}
		if ( isset( $_POST[ 'rex_feed_google_schedule_week_day' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_google_schedule_week_day', $_POST[ 'rex_feed_google_schedule_week_day' ] );
		}
		if ( isset( $_POST[ 'rex_feed_google_schedule_time' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_google_schedule_time', $_POST[ 'rex_feed_google_schedule_time' ] );
		}
		if ( isset( $_POST[ 'rex_feed_ebay_seller_site_id' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_ebay_seller_site_id', $_POST[ 'rex_feed_ebay_seller_site_id' ] );
		}
		if ( isset( $_POST[ 'rex_feed_ebay_seller_country' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_ebay_seller_country', $_POST[ 'rex_feed_ebay_seller_country' ] );
		}
		if ( isset( $_POST[ 'rex_feed_ebay_seller_currency' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_ebay_seller_currency', $_POST[ 'rex_feed_ebay_seller_currency' ] );
		}
		if ( isset( $_POST[ 'rex_feed_analytics_params_options' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_analytics_params_options', $_POST[ 'rex_feed_analytics_params_options' ] );
		}
		if ( isset( $_POST[ 'rex_feed_analytics_params' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_analytics_params', $_POST[ 'rex_feed_analytics_params' ] );
		}
		if ( isset( $_POST[ 'rex_feed_product_filter_ids' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_product_filter_ids', $_POST[ 'rex_feed_product_filter_ids' ] );
		}
		if ( isset( $_POST[ 'product_filter_condition' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_product_condition', $_POST[ 'product_filter_condition' ] );
		}
		if ( isset( $_POST[ 'rex_feed_merchant' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_merchant', $_POST[ 'rex_feed_merchant' ] );
		}
		if ( isset( $_POST[ 'rex_feed_feed_format' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_feed_format', $_POST[ 'rex_feed_feed_format' ] );
		}
		if ( isset( $_POST[ 'rex_feed_separator' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_separator', $_POST[ 'rex_feed_separator' ] );
		}
		if ( isset( $_POST[ 'fc' ] ) ) {
		    array_shift( $_POST[ 'fc' ] );
			update_post_meta( $post_id, 'rex_feed_feed_config', $_POST[ 'fc' ] );
		}
		if ( isset( $_POST[ 'ff' ] ) ) {
		    array_shift( $_POST[ 'ff' ] );
			update_post_meta( $post_id, 'rex_feed_feed_config_filter', $_POST[ 'ff' ] );
		}
		if ( isset( $_POST[ 'rex_feed_cats' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_cats', $_POST[ 'rex_feed_cats' ] );
		}
		if ( isset( $_POST[ 'rex_feed_tags' ] ) ) {
			update_post_meta( $post_id, 'rex_feed_tags', $_POST[ 'rex_feed_tags' ] );
		}
	}
}
