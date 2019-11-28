<?php

/**
 * The file that generates xml feed for RSS.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Rss
 * @subpackage Rex_Product_Feed_Rss/includes
 * @author     RexTheme <info@rextheme.com>
 */

use RexTheme\RssShoppingFeed\Containers\RssShopping;

class Rex_Product_Feed_Rss extends Rex_Product_Feed_Abstract_Generator {


    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {
        RssShopping::$container = null;

        // Generate feed for both simple and variable products.
        $this->generate_simple_product_feed();
        $this->generate_grouped_product_feed();
        $this->generate_variable_product_feed();

        $this->feed = RssShopping::asRss();

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
     * Generate Feed data for Simple Products
     **/
    private function generate_simple_product_feed(){
        // Loop through all products.
        foreach( $this->products as $product ) {
            $pr = wc_get_product($product);

            $atts = $this->get_product_data( $product );
            $item = RssShopping::createItem();

            // add all attributes for each product.
            foreach ($atts as $key => $value) {
                $item->$key($value); // invoke $key as method of $item object.
            }
        }
    }


    /**
     * Generate Feed data for Variable Products
     **/
    private function generate_variable_product_feed(){
        // Loop through all variable products.
        foreach( $this->variable_products as $product ) {
            $pr = wc_get_product($product);

            $item = RexShopping::createItem();
            $atts = $this->get_product_data( $pr );

            // add all attributes for each product.
            foreach ($atts as $key => $value) {
                $item->$key($value); // invoke $key as method of $item object.
            }
        }
    }



    /**
     * Generate Feed data for Grouped Products
     **/
    private function generate_grouped_product_feed(){
        // Loop through all variable products.
        foreach( $this->grouped_products as $product ) {

            $pr  = new WC_Product_Grouped( $product );

            $item = RssShopping::createItem();
            $atts = $this->get_product_data( $product );
            // add all attributes for each product.
            foreach ($atts as $key => $value) {
                $item->$key($value); // invoke $key as method of $item object.
            }
        }
    }

}
