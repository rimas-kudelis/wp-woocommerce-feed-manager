<?php

/**
 * The file that generates xml feed for Facebook.
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

use LukeSnowden\GoogleShoppingFeed\Containers\GoogleShopping;

class Rex_Product_Feed_Facebook extends Rex_Product_Feed_Abstract_Generator {

    /**
     * Create Feed for Google
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {

        GoogleShopping::$container = null;

        GoogleShopping::title($this->title);
        GoogleShopping::link($this->link);
        GoogleShopping::description($this->desc);

        // Generate feed for both simple and variable products.
        $this->generate_simple_product_feed();
        $this->generate_grouped_product_feed();
        $this->generate_variable_product_feed();
//        $this->feed = $this->returnFinalProduct();

        if ($this->feed_format == 'xml') {
            $this->feed = GoogleShopping::asRss();
        }elseif ($this->feed_format == 'text') {
            $this->feed = GoogleShopping::asTxt();
        } elseif ($this->feed_format == 'csv') {
            $this->feed = GoogleShopping::asCsv();
        }else {
            $this->feed = GoogleShopping::asRss();
        }

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
            $item = GoogleShopping::createItem();

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

            $item = GoogleShopping::createItem();
            $atts = $this->get_product_data( $product );
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

            $item = GoogleShopping::createItem();
            $atts = $this->get_product_data( $pr );

            // add all attributes for each product.
            foreach ($atts as $key => $value) {
                $item->$key($value); // invoke $key as method of $item object.
            }
            $item->item_group_id( $pr->get_parent_id() );
        }
    }


    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format == 'xml') {
            return GoogleShopping::asRss();
        } elseif ($this->feed_format == 'text') {
            return GoogleShopping::asTxt();
        } elseif ($this->feed_format == 'csv') {
            return GoogleShopping::asCsv();
        }
        return false;
    }

}
