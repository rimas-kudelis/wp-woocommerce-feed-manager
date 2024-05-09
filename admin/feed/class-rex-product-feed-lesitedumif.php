<?php

/**
 * The file that generates xml feed for Zalando_stock_update.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Google
 * @subpackage Rex_Product_Feed_Google/includes
 * @author     RexTheme <info@rextheme.com>
 */

use RexTheme\RexShoppingFeed\Containers\RexShopping;

class Rex_Product_Feed_Lesitedumif extends Rex_Product_Feed_Abstract_Generator {

    private $product_data = array();

    /**
     * Create Feed for Google
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {

        RexShopping::$container = null;
        RexShopping::title($this->title);
        RexShopping::link($this->link);
        RexShopping::description($this->desc);

        $this->generate_product_feed();

        if ( $this->feed_format === 'csv' ) {
            $this->feed = $this->returnFinalProduct();
        }

        if ($this->batch >= $this->tbatch ) {
            $this->save_feed($this->feed_format );

            return array(
                'msg' => 'finish'
            );
        }else {
            return $this->save_feed($this->feed_format );
        }
    }

    /**
     * Adding items to feed
     *
     * @param $product
     * @param $meta_keys
     * @param string $product_type
     */
    protected function add_to_feed( $product, $meta_keys, $product_type = '' ) {
        $attributes = $this->get_product_data( $product, $meta_keys );

        if( ( $this->rex_feed_skip_product && empty( array_keys($attributes, '') ) ) || !$this->rex_feed_skip_product ) {
            $item = RexShopping::createItem();

            foreach ($attributes as $key => $value) {
                if ( $this->rex_feed_skip_row && $this->feed_format === 'xml' ) {
                    if ( $value != '' ) {
                        $item->$key($value); // invoke $key as method of $item object.
                    }
                }
                else {
                    $item->$key($value); // invoke $key as method of $item object.
                }
            }
        }
    }


    /**
     * @param $parent_atts
     * @return array
     */
    private function get_product_model($parent_atts) {
        $product_data = array();
        if ( $parent_atts ) {
            foreach ( $parent_atts as $key => $value ) {
                if ( $key == 'id' ) {
                    $product_data[ 'id' ] = $value;
                }
                if ( $key == 'title' ) {
                    $product_data[ 'title' ] = $value;
                }
                if ( $key == 'description' ) {
                    $product_data[ 'description' ] = $value;
                }
                if ( $key == 'price_with_tax' ) {
                    $product_data[ 'price_with_tax' ] = $value;
                }
                if ( $key == 'product_url' ) {
                    $product_data[ 'product_url' ] = $value;
                }
                if ( $key == 'image_link' ) {
                    $product_data[ 'image_link' ] = $value;
                }
                if ( $key == 'product_manufacturer' ) {
                    $product_data[ 'product_manufacturer' ] = $value;
                }
            }
        }
        return $product_data;
    }


    /**
     * @param $atts
     * @return array
     */
    private function get_product_simples($atts) {
        $product_data = array();
        foreach ($atts as $key=>$value) {
            if($key == 'merchant_product_simple_id') {
                $product_data['merchant_product_simple_id'] = $value;
            }
            if($key == 'ean') {
                $product_data['ean'] = $value;
            }
        }
        return $product_data;
    }


    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct(){

        if ($this->feed_format === 'xml') {
            return RexShopping::asRss();
        } elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
            return RexShopping::asTxt();
        } elseif ($this->feed_format === 'csv') {
            return RexShopping::asCsv();
        }
        return RexShopping::asRss();
    }

    public function footer_replace() {}
}