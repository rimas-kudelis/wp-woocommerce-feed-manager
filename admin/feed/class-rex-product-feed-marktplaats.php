<?php

/**
 * The file that generates xml feed for any merchant with custom configuration.
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

use RexTheme\MarktPlaatsShoppingFeed\Containers\MarktPlaatsShopping;

class Rex_Product_Feed_Marktplaats extends Rex_Product_Feed_Abstract_Generator {

    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {
        MarktPlaatsShopping::$container = null;
        MarktPlaatsShopping::init(false, $this->setItemWrapper(), 'http://admarkt.marktplaats.nl/schemas/1.0', '', $this->setItemsWrapper());
        MarktPlaatsShopping::title($this->title);
        MarktPlaatsShopping::link($this->link);
        MarktPlaatsShopping::description($this->desc);

        // Generate feed for both simple and variable products.
        $this->generate_simple_product_feed();
        $this->generate_grouped_product_feed();
        $this->generate_variable_product_feed();

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
     * Generate Feed data for Simple Products
     **/
    private function generate_simple_product_feed(){
        // Loop through all products.
        foreach( $this->products as $product ) {

            $pr = wc_get_product($product);

            $atts = $this->get_product_data( $product );
            $item = MarktPlaatsShopping::createItem();

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

            $item = MarktPlaatsShopping::createItem();
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

            $item = RexShopping::createItem();
            $atts = $this->get_product_data( $pr );

            // add all attributes for each product.
            foreach ($atts as $key => $value) {
                $item->$key($value); // invoke $key as method of $item object.
            }
//            $item->item_group_id( $pr->get_id() );
        }
    }


    /**
     * Check if the merchants is valid or not
     * @param $feed_merchants
     * @return bool
     */
    public function is_valid_merchant(){
        return true;
    }


    /**
     * @return string
     */
    public function setItemWrapper()
    {
        return 'admarkt:ad';
    }

    public function setItemsWrapper()
    {
        return 'admarkt:ads';
    }

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format == 'xml') {
            return MarktPlaatsShopping::asRss();
        } elseif ($this->feed_format == 'text') {
            return MarktPlaatsShopping::asTxt();
        } elseif ($this->feed_format == 'csv') {
            return MarktPlaatsShopping::asCsv();
        }
        return false;
    }

}
