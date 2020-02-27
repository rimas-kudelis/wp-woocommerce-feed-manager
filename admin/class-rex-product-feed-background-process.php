<?php

/**
 * The Rex_Product_Feed_Background_Process class file that
 * handle background process
 *
 * @link       https://rextheme.com
 * @since      2.0.0
 *
 * @package    Rex_Product_Feed_Cron_Handler
 * @subpackage Rex_Product_Feed/admin
 */


class Rex_Product_Feed_Background_Process extends WP_Background_Process {

    protected $action = 'rex_product_feed_background_process';


    /**
     * Product Number
     *
     * @since    1.3.3
     * @access   protected
     * @var      Rex_Product_Feed_Background_Process    $product_no    Contains no of products to process.
     */
    protected $product_no;


    /**
     * Total Batch
     *
     * @since    1.3.3
     * @access   protected
     * @var      Rex_Product_Feed_Background_Process    $total_batches
     */
    protected $total_batches;


    /**
     * Batch No
     *
     * @since    1.3.3
     * @access   protected
     * @var      Rex_Product_Feed_Background_Process    $batch
     */
    protected $batch;


    /**
     * Product Batch
     *
     * @since    1.3.3
     * @access   protected
     * @var      Rex_Product_Feed_Background_Process    $offset
     */
    protected $offset;


    /**
     * Task
     *
     * Override this method to perform any actions required on each
     * queue item. Return the modified item for further processing
     * in the next pass through. Or, return false to remove the
     * item from the queue.
     *
     * @param mixed $item Queue item to iterate over
     *
     * @return mixed
     */
    protected function task( $item ) {
        sleep(5);

//        $this->product_no = Rex_Product_Feed_Ajax::get_product_number(array());
        $this->product_no = apply_filters('wpfm_get_total_number_of_products',
            array('products'  => 50)
        );
        $per_batch = get_option('rex-wpfm-product-per-batch', 50);

        $this->total_batches = ceil($this->product_no['products']/(int) $per_batch);
        $this->offset = 0;
        $this->batch = 1;
        $this->do_task($item, $this->batch, $this->offset);
        Rex_Product_Feed_Controller::remove_id_from_feed_queue($item);
        Rex_Product_Feed_Controller::update_feed_status($item, 'completed');

        return false;
    }


    protected function do_task($item_id, $batch_no, $offset) {

        $this->batch = $batch_no;
        $this->offset = $offset;
        $per_batch = get_option('rex-wpfm-product-per-batch', 50);
        for ($i = 1; $i<=$this->total_batches; $i++) {

            $cats_array = $tags_array = array();
            $merchant = get_post_meta($item_id, 'rex_feed_merchant', true);
            $feed_config = get_post_meta($item_id, 'rex_feed_feed_config', true);
            $feed_filter = get_post_meta($item_id, 'rex_feed_feed_config_filter', true);
            $feed_products = get_post_meta($item_id, 'rex_feed_products', true);
            $include_variations = get_post_meta($item_id, 'rex_feed_variations', true) === 'yes' ? true : false;
            $parent_product = get_post_meta($item_id, 'rex_feed_parent_product', true) === 'yes' ? true : false;
            $append_variations = get_post_meta($item_id, 'rex_feed_variation_product_name', true) === 'yes' ? true : false;
            $wpml = get_post_meta($item_id, 'rex_feed_wpml_language', true) ? get_post_meta($item_id, 'rex_feed_wpml_language', true) : '';

            if ( $feed_products !== 'all' && $feed_products !== 'filter') {
                $terms = $feed_products === 'product_tag' ? 'tags' : 'cats';

                if ( function_exists('icl_object_id') ) {
                    if($terms == 'tags' ) {
                        $tags = wp_get_post_terms($item_id, 'product_tag');
                        if($tags) {
                            foreach($tags as $tag) {
                                global $wpdb;
                                $translated_term = icl_object_id( $tag->term_id, 'product_tag', true, $wpml );
                                $term_slug = $wpdb->get_row("SELECT * FROM $wpdb->terms WHERE term_id = $translated_term");
                                if($term_slug) {
                                    $tags_array[] = $term_slug->slug;
                                }
                            }
                        }
                    }elseif ($terms == 'cats'){
                        $cats = wp_get_post_terms($item_id, 'product_cat');
                        if($cats) {
                            foreach($cats as $cat) {
                                global $wpdb;
                                $translated_term = icl_object_id( $cat->term_id, 'product_cat', true, $wpml );
                                $term_slug = $wpdb->get_row("SELECT * FROM $wpdb->terms WHERE term_id = $translated_term");
                                if($term_slug) {
                                    $cats_array[] = $term_slug->slug;
                                }

                            }
                        }
                    }

                }else {
                    if($terms == 'tags' ) {
                        $tags = wp_get_post_terms($item_id, 'product_tag');
                        if($tags) {
                            foreach($tags as $tag) {
                                $tags_array[] = $tag->slug;
                            }
                        }
                    }elseif ($terms == 'cats'){
                        $cats = wp_get_post_terms($item_id, 'product_cat');
                        if($cats) {
                            foreach($cats as $cat) {
                                $cats_array[] = $cat->slug;
                            }
                        }
                    }
                }
            }

            $feed_format = get_post_meta($item_id, 'rex_feed_feed_format', true);
            $payload = array(
                'merchant' => $merchant,
                'feed_format' => $feed_format,
                'info'      => array(
                    'post_id'   => $item_id,
                    'title'     => get_the_title($item_id),
                    'desc'      => get_the_title($item_id),
                    'offset'    => $this->offset,
                    'batch'     => $this->batch,
                ),
                'products'   => array(
                    'products_scope'    => $feed_products,
                    'cats'              => $cats_array,
                    'tags'              => $tags_array,
                ),
                'feed_config'    => $feed_config,
                'feed_filter'    => $feed_filter,
                'include_variations' => $include_variations,
                'append_variations' => $append_variations,
                'parent_product' => $parent_product,
                'wpml_language' => $wpml,
            );


            try {
                $merchant = Rex_Product_Feed_Factory::build( $payload, true );
            } catch (Exception $e) {
                return $e->getMessage();
            }
            $this->batch++;
            $this->offset = (int)$this->offset + (int) $per_batch;
            $merchant->make_feed();
        }

        return false;
    }


    /**
     * Complete
     *
     * Override if applicable, but ensure that the below actions are
     * performed, or, call parent::complete().
     */
    protected function complete() {

        parent::complete();
    }
}
