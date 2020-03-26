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


    private function generate_product_feed(){
        $product_meta_keys = Rex_Feed_Attributes::get_attributes();
        $simple_products = [];
        $variable_products = [];
        $group_products = [];
        $total_products = get_post_meta($this->id, 'rex_feed_total_products', true) ? get_post_meta($this->id, 'rex_feed_total_products', true) : array(
            'total' => 0,
            'simple' => 0,
            'variable' => 0,
            'group' => 0,
        );

        if($this->batch == 1) {
            $total_products = array(
                'total' => 0,
                'simple' => 0,
                'variable' => 0,
                'group' => 0,
            );
        }
        foreach( $this->products as $productId ) {
            $product = wc_get_product( $productId );

            if ( ! is_object( $product ) ) {
                continue;
            }

            if ( ! $product->is_visible() ) {
                continue;
            }

            if ( $product->is_type( 'variable' ) && $product->has_child() ) {
                if($this->product_scope === 'product_cat' || $this->product_scope === 'product_tag' || $this->product_scope === 'filter') {
                    $variations = $product->get_visible_children();
                    if($variations) {
                        foreach ($variations as $variation) {
                            if($this->variations) {
                                $variable_products[] = $variation;
                                $item = RexAdcrowd::createItem();
                                $variation_product = wc_get_product( $variation );
                                $atts = $this->get_product_data( $variation_product, $product_meta_keys );
                                foreach ($atts as $key => $value) {
                                    $item->$key($value); // invoke $key as method of $item object.
                                }
                            }
                        }
                    }
                }
            }

            if ( $product->is_type( 'simple' )) {
                $simple_products[] = $productId;
                $atts = $this->get_product_data( $product, $product_meta_keys );
                $item = RexAdcrowd::createItem();
                foreach ($atts as $key => $value) {
                    $item->$key($value); // invoke $key as method of $item object.
                }
            }

            if ($product->get_type() == 'variation') {
                $variable_products[] = $productId;
                $item = RexAdcrowd::createItem();
                $atts = $this->get_product_data( $product, $product_meta_keys );
                foreach ($atts as $key => $value) {
                    $item->$key($value); // invoke $key as method of $item object.
                }
            }

            if( $product->is_type( 'grouped' ) ){
                $group_products[] = $productId;
                $item = RexAdcrowd::createItem();
                $atts = $this->get_product_data( $product, $product_meta_keys );
                // add all attributes for each product.
                foreach ($atts as $key => $value) {
                    $item->$key($value); // invoke $key as method of $item object.
                }
            }
        }

        $total_products = array(
            'total' => (int) $total_products['total'] + (int) count($simple_products) + (int) count($variable_products) + (int) count($group_products),
            'simple' => (int) $total_products['simple'] + (int) count($simple_products),
            'variable' => (int) $total_products['variable'] + (int) count($variable_products),
            'group' => (int) $total_products['group'] + (int) count($group_products),
        );

        update_post_meta( $this->id, 'rex_feed_total_products', $total_products );
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
        return RexAdcrowd::asRss();
    }

}
