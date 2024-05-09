<?php

/**
 * The file that generates xml feed for Google Local Products Inventory.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Google_local_products_inventory
 * @subpackage Rex_Product_Feed_Google_local_products_inventory/includes
 * @author     RexTheme <info@rextheme.com>
 */

use RexTheme\GoogleLocalProducts\Containers\GoogleLocalProducts;

class Rex_Product_Feed_Google_local_inventory_ads extends Rex_Product_Feed_Abstract_Generator {

    private $feed_merchants = array(
        "nextag" => array(
            'item_wrapper'  => 'product',
            'items_wrapper' => 'products',
        ),
    );
    /**
     * Create Feed for Google
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {

        GoogleLocalProducts::$container = null;
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
	    if ( $this->feed_format === 'xml' ) {
		    return GoogleLocalProducts::asRss();
	    }
	    elseif ( $this->feed_format === 'text' || $this->feed_format === 'tsv' ) {
		    return GoogleLocalProducts::asTxt();
	    }
	    elseif ( $this->feed_format === 'csv' ) {
		    return GoogleLocalProducts::asCsv();
	    }
	    return GoogleLocalProducts::asRss();
    }

    public function footer_replace() {
        $this->feed = str_replace('</channel></rss>', '', $this->feed);
    }
}
