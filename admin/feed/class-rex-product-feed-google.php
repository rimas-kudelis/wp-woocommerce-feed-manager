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

use LukeSnowden\GoogleShoppingFeed\Containers\GoogleShopping;

class Rex_Product_Feed_Google extends Rex_Product_Feed_Abstract_Generator {

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

        $this->generate_product_feed();
        $this->feed = GoogleShopping::asRss();
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
                                $item = GoogleShopping::createItem();
                                $variation_product = wc_get_product( $variation );
                                $atts = $this->get_product_data( $variation_product, $product_meta_keys );
                                $atts = $this->process_attributes_for_shipping_tax($atts);
                                foreach ($atts as $key => $value) {
                                    if($key == 'shipping') {
                                        $item->$key($value['shipping_country'], $value[''], $value[2], $value[3]); // invoke $key as method of $item object.
                                    }
                                    elseif ($key == 'tax') {
                                        $item->$key($value['tax_country'], $value['tax_ship'], $value['tax_rate'], $value['tax_region']); // invoke $key as method of $item object.
                                    }
                                    else {
                                        $item->$key($value); // invoke $key as method of $item object.
                                    }
                                }
                                $item->item_group_id( $variation_product->get_parent_id() );
                            }
                        }
                    }
                }
            }

            if ( $product->is_type( 'simple' )) {
                $simple_products[] = $productId;
                $atts = $this->get_product_data( $product, $product_meta_keys );
                $item = GoogleShopping::createItem();
                $atts = $this->process_attributes_for_shipping_tax($atts);
                foreach ($atts as $key => $value) {
                    if($key == 'shipping') {
                        $item->$key($value['shipping_country'], $value['shipping_service'], $value['shipping_price'], $value['shipping_region']); // invoke $key as method of $item object.
                    }
                    elseif ($key == 'tax') {
                        $item->$key($value['tax_country'], $value['tax_ship'], $value['tax_rate'], $value['tax_region']); // invoke $key as method of $item object.
                    }
                    else {
                        $item->$key($value); // invoke $key as method of $item object.
                    }
                }
            }

            if ($product->get_type() == 'variation') {
                $variable_products[] = $productId;
                $item = GoogleShopping::createItem();
                $atts = $this->get_product_data( $product, $product_meta_keys );
                $atts = $this->process_attributes_for_shipping_tax($atts);
                foreach ($atts as $key => $value) {
                    if($key == 'shipping') {
                        $item->$key($value['shipping_country'], $value['shipping_service'], $value['shipping_price'], $value['shipping_region']); // invoke $key as method of $item object.
                    }
                    elseif ($key == 'tax') {
                        $item->$key($value['tax_country'], $value['tax_ship'], $value['tax_rate'], $value['tax_region']); // invoke $key as method of $item object.
                    }
                    else {
                        $item->$key($value); // invoke $key as method of $item object.
                    }
                }
                $item->item_group_id( $product->get_parent_id() );
            }

            if( $product->is_type( 'grouped' ) ){
                $group_products[] = $productId;
                $item = GoogleShopping::createItem();
                $atts = $this->get_product_data( $product, $product_meta_keys );
                $atts = $this->process_attributes_for_shipping_tax($atts);
                // add all attributes for each product.
                foreach ($atts as $key => $value) {
                    if($key == 'shipping') {
                        $item->$key($value['shipping_country'], $value['shipping_service'], $value['shipping_price'], $value['shipping_region']); // invoke $key as method of $item object.
                    }
                    elseif ($key == 'tax') {
                        $item->$key($value['tax_country'], $value['tax_ship'], $value['tax_rate'], $value['tax_region']); // invoke $key as method of $item object.
                    }
                    else {
                        $item->$key($value); // invoke $key as method of $item object.
                    }
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
     * @param $atts
     * @return array
     */
    private function process_attributes_for_shipping_tax($atts) {
        $shipping_attr = array('shipping_country', 'shipping_region', 'shipping_service', 'shipping_price');
        $default_shipping_values = array(
            'shipping_country' => '',
            'shipping_service' => '',
            'shipping_price' => '',
            'shipping_region' => '',
        );

        $tax_attr = array('tax_country', 'tax_region', 'tax_rate', 'tax_ship');
        $default_tax_values = array(
            'tax_country' => '',
            'tax_ship' => '',
            'tax_rate' => '',
            'tax_region' => '',
        );

        foreach ($atts as $key => $value) {
            if(in_array($key, $shipping_attr)) {
                $atts['shipping'][$key] = $value;
                unset($atts[$key]);
            }

            if(in_array($key, $tax_attr)) {
                $atts['tax'][$key] = $value;
                unset($atts[$key]);
            }
        }
        if(array_key_exists('shipping', $atts)) {
            $atts['shipping'] += $default_shipping_values;
        }
        if(array_key_exists('tax', $atts)) {
            $atts['tax'] += $default_tax_values;
        }
        return $atts;
    }

}
