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

        $this->config = $config;
        $this->posts_per_page = apply_filters('wpfm_get_products_per_page', 50);
        $this->bypass = $bypass;

        $this->setup_feed_data($config['info']);
        $this->prepare_products_args($config['products']);


        if (!$this->bypass){
            $this->setup_feed_rules($config['feed_config']);
            $this->setup_feed_filter_rules($config['feed_config']);
            $this->variations = $this->include_product_variations($config['feed_config']);
            $this->parent_product = $this->include_parent_product($config['feed_config']);
        }else {
            $this->feed_rules = $config['feed_config'];
            $this->feed_rules_filter = $config['feed_filter'];
            $this->variations   = $config['include_variations'];
            $this->parent_product   = $config['include_variations'];
        }

        $this->setup_products();
        $this->setup_variable_products();
        $this->setup_group_products();
        $this->merchant = $config['merchant'];
        $this->feed_format = $config['feed_format'];

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
            'posts_per_page'         => $this->posts_per_page,
            'offset'                 => $this->offset,
            'update_post_term_cache' => true,
            'update_post_meta_cache' => true,
            'cache_results'          => false,
            'suppress_filters'       => false
        );


        if ( $args['products_scope'] === 'custom'){
            $this->products_args['post__in'] = $args['items'];
        } elseif ( $args['products_scope'] !== 'all' && $args['products_scope'] !== 'filter') {

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
        $this->tbatch   =   ceil($totalProducts/100);
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
        $this->wpml_language = array_key_exists('rex_feed_wpml_language', $feed_rules) ? $feed_rules['rex_feed_wpml_language'] : get_post_meta($this->id, 'rex_feed_wpml_language', true);
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
            $sitepress->switch_lang($sitepress->get_default_language());
        }
        $products = get_posts( $this->products_args );
        if ( function_exists('icl_object_id') ) {
            if($this->wpml_language) {
                global $sitepress;
                $this->products = array_map(function($id){
                    $product_id = apply_filters( 'wpml_object_id', $id, 'product', TRUE, $this->wpml_language );
                    return $product_id;
                }, $products);

            }
        }else{
            $this->products = $products;
        }
    }


    /**
     * Setup the variable products from products array.
     */
    protected function setup_variable_products() {

        $this->variable_products = array();

        // Loop through all products and separate the variable products.
        foreach( $this->products as $product_id ) {
            if( $this->is_variable_product( $product_id ) ){
                $this->variable_products[] = $product_id;
            }
        }

        // remove variable products from products array
        if ( !empty( $this->variable_products ) ) {
            $this->products = array_diff( $this->products, $this->variable_products );
        }

        // remove all variable product if product variations is exclude
        if (!$this->variations) {
            $this->variable_products = array();
        }
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
            if($this->wpml_language) {
                global $sitepress;
                $original = apply_filters( 'wpml_element_trid', NULL, $product_id, 'post_product' );
                if($original == $product_id) {
                    $sitepress->switch_lang($sitepress->get_default_language());
                    $data = new Rex_Product_Data_Retriever( $product_id, $this->feed_rules);
                }else {
                    $sitepress->switch_lang($this->wpml_language);
                    $data = new Rex_Product_Data_Retriever( $product_id, $this->feed_rules);
                }
            }
        }else{
            $data = new Rex_Product_Data_Retriever( $product_id, $this->feed_rules);
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


    /**
     * Responsible for creating the feed.
     * @return string
     **/
    abstract public function make_feed();

}
