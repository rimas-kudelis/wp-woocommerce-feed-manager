<?php

/**
 * The file that generates xml feed for Google Local Products.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Google_local_products
 * @subpackage Rex_Product_Feed_Google_local_products/includes
 * @author     RexTheme <info@rextheme.com>
 */

use RexTheme\GoogleLocalProducts\Containers\GoogleLocalProducts;


class Rex_Product_Feed_Google_local_products extends Rex_Product_Feed_Abstract_Generator {

    /**
     * Create Feed for Google
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {
        GoogleLocalProducts::$container = null;
        GoogleLocalProducts::init(true, 'product', null,  '', 'products', false, '', '' );
        GoogleLocalProducts::title($this->title);
        GoogleLocalProducts::link($this->link);
        GoogleLocalProducts::description($this->desc);

        // Generate feed for both simple and variable products.
        $this->generate_product_feed();
        $this->feed = $this->returnFinalProduct();

        if ($this->batch >= $this->tbatch ) {
            $this->save_feed($this->feed_format);
            return array(
                'msg' => 'finish'
            );
        }else {
            return $this->save_feed($this->feed_format);
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
            $item = GoogleLocalProducts::createItem();

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
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format === 'xml') {
            return GoogleLocalProducts::asRss();
        } elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
            return GoogleLocalProducts::asTxt();
        } elseif ($this->feed_format === 'csv') {
            return GoogleLocalProducts::asCsv();
        }
        return GoogleLocalProducts::asRss();
    }


    //replace footer of feed
    public function footer_replace() {
	    $this->feed = str_replace('</products>', '', $this->feed);
    }

}