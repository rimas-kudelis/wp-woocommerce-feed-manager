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

use RexTheme\GlamiShoppingFeed\Containers\GlamiShopping;

class Rex_Product_Feed_Glami extends Rex_Product_Feed_Abstract_Generator
{

    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed()
    {
        GlamiShopping::$container = null;
        GlamiShopping::init(false, $this->setItemWrapper(), '', '', $this->setItemsWrapper());

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
                    $atts = $this->process_attributes_for_delivery($atts);
                    $item = GlamiShopping::createItem();
                    foreach ($atts as $key => $value) {
                        if ($key == 'delivery') {
                            $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                        } else {
                            $item->$key($value); // invoke $key as method of $item object.
                        }
                    }
                }
                if ($this->product_scope === 'product_cat' || $this->product_scope === 'product_tag' || $this->product_scope === 'filter') {
                    if ($this->exclude_hidden_products) {
                        $variations = $product->get_visible_children();
                    } else {
                        $variations = $product->get_children();
                    }
                    if ($variations) {
                        foreach ($variations as $variation) {
                            if ($this->variations) {
                                $variation_products[] = $variation;
                                $item = GlamiShopping::createItem();
                                $variation_product = wc_get_product($variation);
                                $atts = $this->get_product_data($variation_product, $product_meta_keys);
                                $atts = $this->process_attributes_for_delivery($atts);
                                foreach ($atts as $key => $value) {
                                    if ($key == 'delivery') {
                                        $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                                    } else {
                                        $item->$key($value); // invoke $key as method of $item object.
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($product->is_type('simple') || $product->is_type('external') || $product->is_type('composite') || $product->is_type('bundle')) {
                $simple_products[] = $productId;
                $atts = $this->get_product_data($product, $product_meta_keys);
                $atts = $this->process_attributes_for_delivery($atts);
                $item = GlamiShopping::createItem();
                foreach ($atts as $key => $value) {
                    if ($key == 'delivery') {
                        $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                    } else {
                        $item->$key($value); // invoke $key as method of $item object.
                    }

                }
            }

            if ($this->product_scope === 'all'|| $this->product_scope =='product_filter') {
                if ($product->get_type() == 'variation') {
                    $variation_products[] = $productId;
                    $item = GlamiShopping::createItem();
                    $atts = $this->get_product_data($product, $product_meta_keys);
                    $atts = $this->process_attributes_for_delivery($atts);
                    foreach ($atts as $key => $value) {
                        if ($key == 'delivery') {
                            $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                        } else {
                            $item->$key($value); // invoke $key as method of $item object.
                        }
                    }
                }
            }

            if ($product->is_type('grouped')) {
                $group_products[] = $productId;
                $item = GlamiShopping::createItem();
                $atts = $this->get_product_data($product, $product_meta_keys);
                $atts = $this->process_attributes_for_delivery($atts);
                // add all attributes for each product.
                foreach ($atts as $key => $value) {
                    if ($key == 'delivery') {
                        $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                    } else {
                        $item->$key($value); // invoke $key as method of $item object.
                    }
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
        $data = new Rex_Product_Data_Retriever( $product, $this, $product_meta_keys );
        return $data->get_all_data();

        if ( function_exists('icl_object_id') ) {
            global $sitepress;
            $wpml = get_post_meta($this->id, 'rex_feed_wpml_language', true) ? get_post_meta($this->id, 'rex_feed_wpml_language', true)  : $sitepress->get_default_language();
            if($wpml) {
                $sitepress->switch_lang($wpml);
                $data = new Rex_Product_Glami_Data_Retriever( $product, $this->feed_rules, null, $this->append_variation, $product_meta_keys, $analytics_params);
            }
        }else{
            $data = new Rex_Product_Glami_Data_Retriever( $product, $this->feed_rules, null, $this->append_variation, $product_meta_keys, $analytics_params);
        }
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
        return 'SHOPITEM';
    }

    public function setItemsWrapper()
    {
        return 'SHOP';
    }


    /**
     * @param $atts
     * @return array
     */
    private function process_attributes_for_delivery($atts)
    {
        $shipping_attr = array('DELIVERY_ID', 'DELIVERY_PRICE', 'DELIVERY_PRICE_COD');
        $default_delivery_atts = array(
            'DELIVERY_ID' => '',
            'DELIVERY_PRICE' => '',
            'DELIVERY_PRICE_COD' => ''
        );

        foreach ($atts as $key => $value) {
            if (in_array($key, $shipping_attr)) {
                $atts['delivery'][$key] = $value;
                unset($atts[$key]);
            }
        }
        if (array_key_exists('delivery', $atts)) {
            $atts['delivery'] += $default_delivery_atts;
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
        if ($this->feed_format == 'xml') {
            return GlamiShopping::asRss();
        } elseif ($this->feed_format == 'text') {
            return GlamiShopping::asTxt();
        } elseif ($this->feed_format == 'csv') {
            return GlamiShopping::asCsv();
        }
        return GlamiShopping::asRss();
    }


    //replace footer of feed
    public function footer_replace()
    {
        $this->feed = str_replace('</SHOP>', '', $this->feed);
    }
}
