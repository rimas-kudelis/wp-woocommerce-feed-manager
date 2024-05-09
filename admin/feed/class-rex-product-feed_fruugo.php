<?php

/**
 * The file that generates xml feed for Google.
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

use RexTheme\RexFruggoFeed\Containers\Fruugo;

class Rex_Product_Feed_Fruugo extends Rex_Product_Feed_Abstract_Generator {

    public function make_feed() {
        Fruugo::$container = null;
        Fruugo::init(true, 'Product', null,  '', 'Products', false, '', '' );
        Fruugo::title($this->title);
        Fruugo::link($this->link);
        Fruugo::description($this->desc);

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
            $item = Fruugo::createItem();

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
    public function returnFinalProduct(){

        if ($this->feed_format === 'xml') {
            return Fruugo::asRss();
        } elseif ($this->feed_format === 'text') {
            return Fruugo::asTxt();
        } elseif ($this->feed_format === 'csv') {
            return Fruugo::asCsv();
        }

    }


    public function footer_replace() {
        $this->feed = str_replace('</Products>', '', $this->feed);
    }

}
