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

use RexTheme\RexAdcrowdFeed\Containers\RexAdcrowd;

class Rex_Product_Feed_Adcrowd extends Rex_Product_Feed_Abstract_Generator {

    private $feed_merchants = array(
        "nextag" => array(
            'item_wrapper'  => 'product',
            'items_wrapper' => 'products',
        ),
    );

    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {
        RexAdcrowd::$container = null;
        RexAdcrowd::init(true, $this->setItemWrapper(), null, '', $this->setItemsWrapper());
        RexAdcrowd::title($this->title);
        RexAdcrowd::link($this->link);
        RexAdcrowd::description($this->desc);
        
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
            $item = RexAdcrowd::createItem();

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

            $item = RexAdcrowd::createItem();
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

            $item = RexAdcrowd::createItem();
            $atts = $this->get_product_data( $pr );

            // add all attributes for each product.
            foreach ($atts as $key => $value) {
                $item->$key($value); // invoke $key as method of $item object.
            }

//          $item->item_group_id( $pr->get_parent_id() );

        }
    }


    /**
     * Check if the merchants is valid or not
     * @param $feed_merchants
     * @return bool
     */
    public function is_valid_merchant(){
        return array_key_exists($this->merchant, $this->feed_merchants)? true : false;
    }


    /**
     * @return string
     */
    public function setItemWrapper()
    {
        return 'item';
    }

    public function setItemsWrapper()
    {
        return 'channel';
    }

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format == 'xml') {
            return RexAdcrowd::asRss();
        } elseif ($this->feed_format == 'text') {
            return RexAdcrowd::asTxt();
        } elseif ($this->feed_format == 'csv') {
            return RexAdcrowd::asCsv();
        }
        return false;
    }

}
