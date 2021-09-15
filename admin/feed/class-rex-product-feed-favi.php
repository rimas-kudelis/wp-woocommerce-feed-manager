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

use RexTheme\FaviShoppingFeed\Containers\FaviShopping;

class Rex_Product_Feed_Favi extends Rex_Product_Feed_Abstract_Generator
{

    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed()
    {
        FaviShopping::$container = null;
        FaviShopping::init(false, $this->setItemWrapper(), '', '', $this->setItemsWrapper());

        $this->generate_product_feed();

        $this->feed = $this->returnFinalProduct();

        if ($this->batch >= $this->tbatch) {

            $this->save_feed($this->feed_format);
            return array(
                'msg' => 'finish'
            );
        } else {
            return $this->save_feed($this->feed_format);
        }
    }


    private function generate_product_feed()
    {
        $product_meta_keys = Rex_Feed_Attributes::get_attributes();
        $simple_products = [];
        $variation_products = [];
        $variable_parent = [];
        $group_products = [];
        $total_products = get_post_meta($this->id, 'rex_feed_total_products', true) ? get_post_meta($this->id, 'rex_feed_total_products', true) : array(
            'total' => 0,
            'simple' => 0,
            'variable' => 0,
            'variable_parent' => 0,
            'group' => 0,
        );

        if ($this->batch == 1) {
            $total_products = array(
                'total' => 0,
                'simple' => 0,
                'variable' => 0,
                'variable_parent' => 0,
                'group' => 0,
            );
        }
        foreach ($this->products as $productId) {
            $product = wc_get_product($productId);

            if (!is_object($product)) {
                continue;
            }

            if ($this->exclude_hidden_products) {
                if (!$product->is_visible()) {
                    continue;
                }
            }

            if ($product->is_type('variable') && $product->has_child()) {

                if ($this->variable_product) {

                    $variable_parent[] = $productId;
                    $variable_product = new WC_Product_Variable($productId);
                    $atts = $this->get_product_data($variable_product, $product_meta_keys);
                    $atts = $this->process_attributes_for_param($atts);
                    $item = FaviShopping::createItem();

                    foreach ($atts as $key => $value) {

                        if ($key == 'delivery') {
                            $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                        } elseif ($key === 'param') {
                            $item->$key($key, $value);
                        } else {
	                        if ( $this->rex_feed_skip_row ) {
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
                if ($this->product_scope === 'product_cat' || $this->product_scope === 'product_tag') {
                    if ($this->exclude_hidden_products) {
                        $variations = $product->get_visible_children();
                    } else {
                        $variations = $product->get_children();
                    }
                    if ( $variations && $this->product_scope !='filter' ) {
                        foreach ($variations as $variation) {
                            if ($this->variations) {
                                $variation_products[] = $variation;
                                $item = FaviShopping::createItem();
                                $variation_product = wc_get_product($variation);
                                $atts = $this->get_product_data($variation_product, $product_meta_keys);
                                $atts = $this->process_attributes_for_param($atts);
                                $check_item_group_id = 0;

                                foreach ($atts as $key => $value) {
                                    if ($key == 'delivery') {
                                        $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                                    } elseif ($key === 'param') {
                                        $item->$key($key, $value);
                                    } else {
	                                    if ( $this->rex_feed_skip_row ) {
		                                    if ( $value != '' ) {
			                                    $item->$key($value); // invoke $key as method of $item object.
		                                    }
	                                    }
	                                    else {
		                                    $item->$key($value); // invoke $key as method of $item object.
	                                    }
                                    }
                                    if('item_group_id' == $key){
                                        $check_item_group_id = 1;
                                    }
                                }
                                if($check_item_group_id == 0){
                                    $item->item_group_id($variation_product->get_parent_id());
                                }
                            }
                        }
                    }
                }
            }

            if ($product->is_type('simple') || $product->is_type('external') || $product->is_type('composite') || $product->is_type('bundle')) {
                $simple_products[] = $productId;
                $atts = $this->get_product_data($product, $product_meta_keys);

                $atts = $this->process_attributes_for_param($atts);
                $item = FaviShopping::createItem();
                foreach ($atts as $key => $value) {
                    if ($key == 'delivery') {
                        $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                    } elseif ($key === 'param') {
                        $item->$key($key, $value);
                    } else {
	                    if ( $this->rex_feed_skip_row ) {
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

            if ($this->product_scope === 'all' || $this->product_scope == 'product_filter' || $this->product_scope == 'filter') {
                if ($product->get_type() == 'variation') {
                    $variation_products[] = $productId;
                    $item = FaviShopping::createItem();
                    $atts = $this->get_product_data($product, $product_meta_keys);
                    $atts = $this->process_attributes_for_param($atts);
                    $check_item_group_id = 0;

                    foreach ($atts as $key => $value) {
                        if ($key == 'delivery') {
                            $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                        } elseif ($key === 'param') {
                            $item->$key($key, $value);
                        }
                        else {
	                        if ( $this->rex_feed_skip_row ) {
		                        if ( $value != '' ) {
			                        $item->$key($value); // invoke $key as method of $item object.
		                        }
	                        }
	                        else {
		                        $item->$key($value); // invoke $key as method of $item object.
	                        }
                        }
                        if('item_group_id' == $key){
                            $check_item_group_id = 1;
                        }
                    }
                    if($check_item_group_id == 0){
                        $item->item_group_id($product->get_parent_id());
                    }
                }
            }

            if ($product->is_type('grouped') || $product->is_type( 'woosb' )) {
                $group_products[] = $productId;
                $item = FaviShopping::createItem();
                $atts = $this->get_product_data($product, $product_meta_keys);
                $atts = $this->process_attributes_for_param($atts);
                $check_item_group_id = 0;
                // add all attributes for each product.
                foreach ($atts as $key => $value) {
                    if ($key == 'delivery') {
                        $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                    } elseif ($key === 'param') {
                        $item->$key($key, $value);
                    } else {
	                    if ( $this->rex_feed_skip_row ) {
		                    if ( $value != '' ) {
			                    $item->$key($value); // invoke $key as method of $item object.
		                    }
	                    }
	                    else {
		                    $item->$key($value); // invoke $key as method of $item object.
	                    }
                    }
                    if('item_group_id' == $key){
                        $check_item_group_id = 1;
                    }
                }
                if($check_item_group_id == 0){
                    $item->item_group_id($productId);
                }
            }
        }

        $total_products = array(
            'total' => (int)$total_products['total'] + (int)count($simple_products) + (int)count($variation_products) + (int)count($group_products) + (int)count($variable_parent),
            'simple' => (int)$total_products['simple'] + (int)count($simple_products),
            'variable' => (int)$total_products['variable'] + (int)count($variation_products),
            'variable_parent' => (int)$total_products['variable_parent'] + (int)count($variable_parent),
            'group' => (int)$total_products['group'] + (int)count($group_products),
        );

        update_post_meta($this->id, 'rex_feed_total_products', $total_products);
    }


    /**
     * Get product data
     * @param WC_Product $product
     * @return string
     */
    protected function get_product_data( WC_Product $product, $product_meta_keys ){
        $data = new Rex_Product_Glami_Data_Retriever( $product, $this, $product_meta_keys );
        return $data->get_all_data();
    }

    /**
     * Check if the merchants is valid or not
     * @param $feed_merchants
     * @return bool
     */
    public function is_valid_merchant()
    {
        return true;
    }


    /**
     * @return string
     */
    public function setItemWrapper()
    {
        return 'product';
    }

    public function setItemsWrapper()
    {
        return 'products';
    }



    /**
     * process atts for param attribute
     *
     * @param $atts
     * @return mixed
     *
     * @since 6.3.2
     */
    private function process_attributes_for_param($atts) {
        foreach ($atts as $key => $value) {
            if(preg_match('/^Attribute/im', $key)) {
                $param_no = preg_replace('/[^0-9]/', '', $key);
                $atts['attributes'][$key] = array(
                    'key'           => $key,
                    'name'          => $value,
                    'value'         => isset($atts['Attribute_value_'.$param_no]) ? $atts['Attribute_value_'.$param_no] : '',
                );
            }
        }
        foreach ($atts as $key => $value) {
            if(preg_match('/^Attribute/im', $key)) {
                $param_no = preg_replace('/[^0-9]/', '', $key);
                unset($atts['attributes']['Attribute_value_' . $param_no]);
            }
        }
        return $atts;
    }


    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format === 'xml') {
            return FaviShopping::asRss();
        } elseif ($this->feed_format === 'text') {
            return FaviShopping::asTxt();
        } elseif ($this->feed_format === 'csv' || $this->feed_format === 'csv_semicolon') {
            return FaviShopping::asCsv();
        }
        return FaviShopping::asRss();
    }


    //replace footer of feed
    public function footer_replace()
    {
        $this->feed = str_replace('</products>', '', $this->feed);
    }
}
