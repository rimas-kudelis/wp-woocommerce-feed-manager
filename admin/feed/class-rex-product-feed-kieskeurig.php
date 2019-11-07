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

use RexTheme\RexShoppingFeed\Containers\RexShopping;

class Rex_Product_Feed_Kieskeurig extends Rex_Product_Feed_Abstract_Generator {

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
        RexShopping::$container = null;
        RexShopping::init(false, $this->setItemWrapper(), null, '', $this->setItemsWrapper());
        RexShopping::title($this->title);
        RexShopping::link($this->link);
        RexShopping::description($this->desc);
        
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
            $item = RexShopping::createItem();

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

            $item = RexShopping::createItem();
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

            $product  = wc_get_product( $product );
            $children =  $product->get_children();

            // add all variants into feed
            foreach ($children as $child) {

                $pr = wc_get_product($child);

                $item = RexShopping::createItem();
                $atts = $this->get_product_data( $child );

                // add all attributes for each product.
                foreach ($atts as $key => $value) {
                    $item->$key($value); // invoke $key as method of $item object.
                }



            }
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
        return $this->is_valid_merchant()? $this->feed_merchants[$this->merchant]['item_wrapper'] : 'product';
    }

    public function setItemsWrapper()
    {
        return $this->is_valid_merchant()? $this->feed_merchants[$this->merchant]['items_wrapper'] : 'products';
    }

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format == 'xml') {
            return RexShopping::asRss();
        } elseif ($this->feed_format == 'text') {
            return RexShopping::asTxt();
        } elseif ($this->feed_format == 'csv') {
            return RexShopping::asCsv();
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
                    $data = new Rex_Kieskeurig_Product_Data_Retriever( $product_id, $this->feed_rules, null, $this->append_variation);
                }else {
                    $sitepress->switch_lang($this->wpml_language);
                    $data = new Rex_Kieskeurig_Product_Data_Retriever( $product_id, $this->feed_rules, null, $this->append_variation);
                }
            }
        }else{
            $data = new Rex_Kieskeurig_Product_Data_Retriever( $product_id, $this->feed_rules, null, $this->append_variation);
        }
        return $data->get_all_data();
    }

}
