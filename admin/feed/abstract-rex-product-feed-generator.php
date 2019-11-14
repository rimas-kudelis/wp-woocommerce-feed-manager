<?php

/**
 * Abstract Rex Product Feed Generator
 *
 * A abstract class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 * The XML Feed Generator.
 *
 * This is used to generate xml feed based on given settings.
 *
 * @since      1.0.0
 * @package    Rex_Product_Feed_Abstract_Generator
 * @author     RexTheme <info@rextheme.com>
 */
abstract class Rex_Product_Feed_Abstract_Generator {

    /**
     * The Product/Feed Config.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    config    Feed config.
     */
    protected $config;

    /**
     * The Product/Feed ID.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    id    Feed id.
     */
    protected $id;

    /**
     * Feed Title.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    title    Feed title
     */
    protected $title;

    /**
     * Feed Description.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    desc    Feed description.
     */
    protected $desc;

    /**
     * Feed Link.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    link    Feed link.
     */
    protected $link;

    /**
     * The feed Merchant.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    $merchant    Contains merchant name of the feed.
     */
    protected $merchant;

    /**
     * The feed format.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    $feed_format    Contains format of the feed.
     */
    protected $feed_format;

    /**
     * The feed rules containing all attributes and their value mappings for the feed.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    $feed_rules    Contains attributes and value mappings for the feed.
     */
    protected $feed_rules;


    /**
     * The feed filter rules containing all condition and values for the feed.
     *
     * @since    1.1.10
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    $feed_rules_filter    Contains condition and value for the feed.
     */
    protected $feed_rules_filter;

    /**
     * The Product Query args to retrieve specific products for making the Feed.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    $products_args    Contains products query args for feed.
     */
    protected $products_args;

    /**
     * Array contains all products.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    $products    Contains all products to make feed.
     */
    protected $products;

    /**
     * Array contains all variable products for creating feed with variations.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    $products    Contains all products to make feed.
     */
    protected $variable_products;


    /**
     * Array contains all variable products for creating feed with variations.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rex_Product_Feed_Abstract_Generator    $products    Contains all products to make feed.
     */
    protected $grouped_products;



    /**
     * The Feed.
     * @since    1.0.0
     * @access   protected
     * @var Rex_Product_Feed_Abstract_Generator    $feed    Feed as text.
     */
    protected $feed;


    /**
     * Allowed Product
     *
     * @since    1.1.10
     * @access   private
     * @var      bool    $allowed
     */
    protected $allowed;



    /**
     * Post per page
     *
     * @since    1.0.0
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $posts_per_page
     */
    protected $posts_per_page;


    /**
     * Product Scope
     *
     * @since    1.1.10
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $product_scope
     */
    protected $product_scope;



    /**
     * Product Offset
     *
     * @since    1.3.0
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $offset
     */
    protected $offset;


    /**
     * Product Current Batch
     *
     * @since    1.3.0
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $batch
     */
    protected $batch;


    /**
     * Product Total Batch
     *
     * @since    1.3.0
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $tbatch
     */
    protected $tbatch;


    /**
     * Bypass functionality from child
     *
     * @since    2.0.0
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $bypass
     */
    protected $bypass;


    /**
     * Product variations include/exclude
     *
     * @since    2.0.1
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $variations
     */
    protected $variations;


    /**
     * parent product include/exclude
     *
     * @since    2.0.3
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $parent_product
     */
    protected $parent_product;


    /**
     * Append variation
     * product name
     *
     * @since    3.2
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $append_variation
     */
    protected $append_variation;


    /**
     * wpml enable
     *
     * @since    2.2.2
     * @access   private
     * @var      Rex_Product_Feed_Abstract_Generator    $wpml_language
     */
    protected $wpml_language;




    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     * @param $config
     * @param $bypass
     * @since    1.0.0
     */
    public function __construct( $config, $bypass = false )
    {
        $this->products = [];
        $this->variable_products= [];
        $this->grouped_products = [];

        $this->config = $config;
        $is_premium = apply_filters('wpfm_is_premium', false);
        $per_page = get_option('rex-wpfm-product-per-batch', 50);
        $this->posts_per_page = $is_premium ? (int)$per_page : ((int)$per_page >= 50 ? 50 : (int)$per_page);
        $this->bypass = $bypass;

        $this->setup_feed_data($config['info']);
        $this->prepare_products_args($config['products']);

        if (!$this->bypass){
            $this->setup_feed_rules($config['feed_config']);
            $this->setup_feed_filter_rules($config['feed_config']);
            $this->variations = $this->include_product_variations($config['feed_config']);
            $this->parent_product = $this->include_parent_product($config['feed_config']);
            $this->append_variation = $this->append_variation_product_name($config['feed_config']) ? 'yes' : 'no';
        }else {
            $this->feed_rules = $config['feed_config'];
            $this->feed_rules_filter = $config['feed_filter'];
            $this->variations   = $config['include_variations'];
            $this->parent_product   = $config['include_variations'];
            $this->append_variation   = $config['append_variations'];
        }


        $this->setup_products();
        $this->merchant = $config['merchant'];
        $this->feed_format = $config['feed_format'];

        /**
         * log for feed
         */
        $log = wc_get_logger();

        if($this->bypass) {
            if($this->batch == 1) {
                $log->info(__( 'Start feed processing job by cron', 'rex-product-feed' ), array('source' => 'WPFM',));
                $log->info('Feed ID: '.$config['info']['post_id'], array('source' => 'WPFM',));
                $log->info('Feed Name: '.$config['info']['title'], array('source' => 'WPFM',));
                $log->info('Merchant Type: '.$this->merchant, array('source' => 'WPFM',));
            }
            $log->info('Total Batches: '.$this->batch, array('source' => 'WPFM',));
            $log->info('Current Batch: '.$this->tbatch, array('source' => 'WPFM',));
        }else {
            if($this->batch == 1) {
                $log->info(__( 'Start feed processing job.', 'rex-product-feed' ), array('source' => 'WPFM',));
                $log->info('Feed ID: '.$config['info']['post_id'], array('source' => 'WPFM',));
                $log->info('Feed Name: '.$config['info']['title'], array('source' => 'WPFM',));
                $log->info('Merchant Type: '.$this->merchant, array('source' => 'WPFM',));
            }
            $log->info('Total Batches: '.$this->batch, array('source' => 'WPFM',));
            $log->info('Current Batch: '.$this->tbatch, array('source' => 'WPFM',));
        }

    }



    /**
     * Prepare the Products Query args for retrieving  products.
     * @param $args
     */
    protected function prepare_products_args( $args ) {

        $this->product_scope = $args['products_scope'];
        $this->products_args = array(
            'post_type'              => 'product',
            'fields'                 => 'ids',
            'post_status'            => 'publish',
            'posts_per_page'         => $this->posts_per_page,
            'offset'                 => $this->offset,
            'update_post_term_cache' => true,
            'update_post_meta_cache' => true,
            'cache_results'          => false,
            'suppress_filters'       => false,
        );

        if ( $args['products_scope'] === 'product_cat' || $args['products_scope'] === 'product_tag') {

            $terms = $args['products_scope'] === 'product_tag' ? 'tags' : 'cats';

            $this->products_args['tax_query'][] = array(
                'taxonomy' => $args['products_scope'],
                'field'    => 'slug',
                'terms'    => $args[$terms]
            );
        }
    }

    /**
     * Setup the Feed Related info
     * @param $info
     */
    protected function setup_feed_data( $info ){
        $totalProducts  =   apply_filters('wpfm_get_total_number_of_products_for_batch', 50);
        $per_batch      =   get_option('rex-wpfm-product-per-batch', 50);
        $this->tbatch   =   ceil($totalProducts/(int)$per_batch);
        $this->id       =   $info['post_id'];
        $this->title    =   $info['title'];
        $this->desc     =   $info['desc'];
        $this->offset   =   $info['offset'];
        $this->batch    =   (int) $info['batch'];
        $this->link     =   esc_url( home_url('/') );
    }

    /**
     * Setup the rules
     * @param $info
     */
    protected function setup_feed_rules( $info ){
        $feed_rules       = array();
        parse_str( $info, $feed_rules );

        if ( function_exists('icl_object_id') ) {
            update_post_meta( $this->id, 'rex_feed_wpml_language', ICL_LANGUAGE_CODE );
            $this->wpml_language = ICL_LANGUAGE_CODE;
        }
        else {
            $this->wpml_language = false;
        }

        $feed_rules       = $feed_rules['fc'];
        $this->feed_rules = $feed_rules;
        // save the feed_rules into feed post_meta.
        update_post_meta( $this->id, 'rex_feed_feed_config', $this->feed_rules );
    }



    /**
     * Include Product Variations
     * @param $info
     * @return bool
     */
    protected function include_product_variations( $info ){
        $feed_rules       = array();
        parse_str( $info, $feed_rules );
        $include_variations       = $feed_rules['rex_feed_variations'];
        if ($include_variations === 'yes') {
            return true;
        }
        return false;
    }


    /**
     * Append product variation
     * name
     * @param $info
     * @return bool
     */
    protected function append_variation_product_name( $info ){
        $feed_rules       = array();
        parse_str( $info, $feed_rules );
        $include_variations       = $feed_rules['rex_feed_variation_product_name'];
        if ($include_variations === 'yes') {
            return true;
        }
        return false;
    }


    /**
     * Include Product Variations
     * @param $info
     * @return bool
     */
    protected function include_parent_product( $info ){
        $feed_rules       = array();
        parse_str( $info, $feed_rules );
        $include_parent       = $feed_rules['rex_feed_parent_product'];
        if ($include_parent === 'yes') {
            return true;
        }
        return false;
    }


    /**
     * Setup the rules for filter
     * @param $info
     */
    protected function setup_feed_filter_rules( $info ){
        $feed_rules_filter       = array();
        parse_str( $info, $feed_rules_filter );
        $feed_rules_filter          = $feed_rules_filter['ff'];
        $this->feed_rules_filter    = $feed_rules_filter;

        // save the feed_rules_filter into feed post_meta.
        update_post_meta( $this->id, 'rex_feed_feed_config_filter', $this->feed_rules_filter );
    }


    /**
     * Get the products to generate feed
     */
    protected function setup_products() {

        if ( function_exists('icl_object_id') ) {
            global $sitepress;
            $current_language = get_post_meta($this->id, 'rex_feed_wpml_language', true) ? get_post_meta($this->id, 'rex_feed_wpml_language', true)  : $sitepress->get_default_language();
            $sitepress->switch_lang($current_language);
        }

        if($this->product_scope === 'filter') {

            $filter_args = Rex_Product_Filter::createFilterQueryParams($this->feed_rules_filter);
            add_filter( 'posts_where', array($this, 'wpfm_post_title_filter'), 10, 2 );
            foreach ($filter_args['args'] as $key => $value) {
                $this->products_args[$key] = $value;
            }

            if(array_key_exists('meta_query', $this->products_args)) {
                $this->products_args['meta_query']['relation'] = 'OR';
            }

            if(array_key_exists('tax_query', $this->products_args)) {
                $this->products_args['tax_query']['relation'] = 'AND';
            }

        }

        $result = new WP_Query($this->products_args);
        remove_filter( 'posts_where', array($this, 'wpfm_post_title_filter'), 10 );

//        var_dump($result->request);
//        wp_die();

        $products = $result->posts;
        if($products) {
            foreach ($products as $product) {
                if($this->is_variable_product($product)) {
                    $this->variable_products[] = $product;
                }elseif ($this->is_grouped_product($product)){
                    $this->grouped_products[] = $product;
                }else {
                    $this->products[] = $product;
                }
            }
        }
    }


    /**
     * product serach by title
     * @param $where
     * @param $wp_query
     * @return string
     */
    function wpfm_post_title_filter($where, &$wp_query) {
        global $wpdb;
        if($wp_query->get('title_contain')) {
            $title_contain = $wp_query->get('title_contain');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'AND' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like( $title ) . '%\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('title_dn_contain')) {
            $title_dn_contain = $wp_query->get('title_dn_contain');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'AND' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_title NOT LIKE \'%' . $wpdb->esc_like( $title ) . '%\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('title_equal_to')) {
            $title_dn_contain = $wp_query->get('title_equal_to');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'AND' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_title = \'' . $wpdb->esc_like( $title ) . '\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('title_nequal_to')) {
            $title_dn_contain = $wp_query->get('title_nequal_to');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'AND' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_title <> \'' . $wpdb->esc_like( $title ) . '\'';
            };
            $where .= ' )';

        }


        if($wp_query->get('description_contain')) {
            $title_contain = $wp_query->get('title_contain');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'OR' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_content LIKE \'%' . $wpdb->esc_like( $title ) . '%\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('description_dn_contain')) {
            $title_dn_contain = $wp_query->get('title_dn_contain');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'OR' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_content NOT LIKE \'%' . $wpdb->esc_like( $title ) . '%\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('description_equal_to')) {
            $title_dn_contain = $wp_query->get('title_equal_to');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'OR' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_content = \'' . $wpdb->esc_like( $title ) . '\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('description_nequal_to')) {
            $title_dn_contain = $wp_query->get('title_nequal_to');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'OR' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_content <> \'' . $wpdb->esc_like( $title ) . '\'';
            };
            $where .= ' )';

        }


        if($wp_query->get('sdescription_contain')) {
            $title_contain = $wp_query->get('title_contain');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'OR' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_excerpt LIKE \'%' . $wpdb->esc_like( $title ) . '%\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('sdescription_dn_contain')) {
            $title_dn_contain = $wp_query->get('title_dn_contain');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'OR' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_excerpt NOT LIKE \'%' . $wpdb->esc_like( $title ) . '%\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('sdescription_equal_to')) {
            $title_dn_contain = $wp_query->get('title_equal_to');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'OR' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_excerpt = \'' . $wpdb->esc_like( $title ) . '\'';
            };
            $where .= ' )';
        }
        if($wp_query->get('sdescription_nequal_to')) {
            $title_dn_contain = $wp_query->get('title_nequal_to');
            $i = 0;
            $where .= ' AND (';
            foreach ($title_dn_contain as $title) {
                $i = $i + 1;
                $op = ($i > 1)? 'OR' : '';
                $where .= ' '. $op. ' '. $wpdb->posts . '.post_excerpt <> \'' . $wpdb->esc_like( $title ) . '\'';
            };
            $where .= ' )';

        }

        return $where;
    }




    /**
     * Setup the variable products from products array.
     */
    protected function setup_group_products() {

        $this->grouped_products = array();

        // Loop through all products and separate the variable products.
        foreach( $this->products as $product_id ) {
            if( $this->is_grouped_product( $product_id ) ){
                $this->grouped_products[] = $product_id;
            }
        }

        // remove variable products from products array
        if ( !empty( $this->grouped_products ) ) {
            $this->products = array_diff( $this->products, $this->grouped_products );
        }

        // remove all variable product if product variations is exclude
        if (!$this->parent_product) {
            $this->grouped_products = array();
        }
    }



    /**
     * Setup the variable products from products array.
     */
    protected function is_variable_product( $product_id = false ) {

        if ( false === $product_id ) {
            return false;
        }

        $product = wc_get_product( $product_id );

        if( $product->is_type( 'variable' ) ){
            return true;
        }

        return false;
    }



    /**
     * Setup the variable products from products array.
     */
    protected function is_grouped_product( $product_id = false ) {

        if ( false === $product_id ) {
            return false;
        }

        $product = wc_get_product( $product_id );

        if( $product->is_type( 'grouped' ) ){
            return true;
        }

        return false;
    }



    /**
     * Get Product data.
     * @param bool $id
     *
     * @return array
     */
    protected function get_product_data( $product_id = false ){
        if ( function_exists('icl_object_id') ) {
            global $sitepress;
            $wpml = get_post_meta($this->id, 'rex_feed_wpml_language', true) ? get_post_meta($this->id, 'rex_feed_wpml_language', true)  : $sitepress->get_default_language();
            if($wpml) {
                $sitepress->switch_lang($wpml);
                $data = new Rex_Product_Data_Retriever( $product_id, $this->feed_rules, null, $this->append_variation);
            }
        }else{
            $data = new Rex_Product_Data_Retriever( $product_id, $this->feed_rules, null, $this->append_variation);
        }
        return $data->get_all_data();
    }


    /**
     * Save the feed as XML file.
     *
     * @return bool
     */
    protected function save_feed($format){

        $path  = wp_upload_dir();
        $path  = $path['basedir'] . '/rex-feed';

        // make directory if not exist
        if ( !file_exists($path) ) {
            wp_mkdir_p($path);
        }

        $log = wc_get_logger();
        if($this->batch == $this->tbatch) {
            $log->info(__( 'Completed feed generation job.', 'rex-product-feed' ), array('source' => 'WPFM',));
            $log->info(__( '**************************************************', 'rex-product-feed' ), array('source' => 'WPFM',));
        }

        if($format == 'xml'){

            $file = trailingslashit($path) . "feed-{$this->id}.xml";
            if( file_exists($file) ) {
                if($this->batch == 1) {
                    return file_put_contents($file, $this->feed) ? 'true' : 'false';
                }else {
                    $feed = $this->merge_feeds($file);
                    return file_put_contents($file, $feed) ? 'true' : 'false';
                }
            }else{
                return file_put_contents($file, $this->feed) ? 'true' : 'false';
            }
        }
        elseif ($format == 'text'){
            $file = trailingslashit($path) . "feed-{$this->id}.txt";
            if( file_exists($file) ) {
                if($this->batch == 1) {
                    return file_put_contents($file, $this->feed) ? 'true' : 'false';
                }else {
                    $feed = $this->merge_feeds($file);
                    return file_put_contents($file, $feed, FILE_APPEND) ? 'true' : 'false';
                }
            }else{
                return file_put_contents($file, $this->feed) ? 'true' : 'false';
            }

        }
        elseif ($format == 'csv'){
            $file = trailingslashit($path) . "feed-{$this->id}.csv";
            if($this->batch == 1) {
                if(file_exists($file)){
                    unlink($file);
                }
                $file = fopen($file,"a+");

                $list = $this->feed;
                foreach ($list as $line)
                {
                    fputcsv($file,$line);
                }
                fclose($file);
                return 'true';
            }
            else {

                $file = fopen($file,"a+");

                $list = $this->feed;
                array_shift($list);
                foreach ($list as $line)
                {
                    fputcsv($file,$line);
                }
                fclose($file);
                return 'true';
            }
        }
        else{
            $file = trailingslashit($path) . "feed-{$this->id}.xml";
            if( file_exists($file) ) {
                if($this->batch == 1) {
                    return file_put_contents($file, $this->feed) ? 'true' : 'false';
                }else {
                    $feed = $this->merge_feeds($file);
                    return file_put_contents($file, $feed) ? 'true' : 'false';
                }
            }else{
                return file_put_contents($file, $this->feed) ? 'true' : 'false';
            }
        }
    }


    /**
     * Responsible for merge batch feeds.
     * @return string
     **/

    protected function merge_feeds($prev_feed){
        $xml_str = simplexml_load_file($prev_feed)->asXML();
        $orgdoc = new DOMDocument;
        $orgdoc->loadXML($xml_str);

        if($this->merchant === 'google' || $this->merchant === 'facebook') {
            $parent = $orgdoc->getElementsByTagName('channel')->item(0);
        }else {
            $parent = $orgdoc->getElementsByTagName('products')->item(0);
        }

        // Create a new document
        $newdoc = new DOMDocument;
        $newdoc->loadXML($this->feed);

        // The node we want to import to a new document

        if($this->merchant === 'google' || $this->merchant === 'facebook') {
            $node = $newdoc->getElementsByTagName("item");
        }else {
            $node = $newdoc->getElementsByTagName("product");
        }

        for ($i = 0; $i < $node->length; $i ++) {
            $item = $node->item($i);
            // Import the node, and all its children, to the document
            $item = $orgdoc->importNode($item, true);
            $parent->appendChild($item);
        }
        return $orgdoc->saveXML();

    }


    function cleanString($string)
    {
        // allow only letters
        $res = preg_replace("/[^a-zA-Z]/", "", $string);

        // trim what's left to 8 chars
        $res = substr($res, 0, 8);

        // make lowercase
        $res = strtolower($res);

        // return
        return $res;
    }


    /**
     * Responsible for creating the feed.
     * @return string
     **/
    abstract public function make_feed();

}
