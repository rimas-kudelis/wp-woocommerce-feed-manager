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

    /**
     * Adding items to feed
     *
     * @param $product
     * @param $meta_keys
     * @param string $product_type
     */
    protected function add_to_feed( $product, $meta_keys, $product_type = '' ) {
        $attributes = $this->get_product_data( $product, $meta_keys );
        $attributes = $this->process_attributes_for_delivery($attributes);
        $attributes = $this->process_attributes_for_param($attributes);

        if( ( $this->rex_feed_skip_product && empty( array_keys($attributes, '') ) ) || !$this->rex_feed_skip_product ) {
            $item = GlamiShopping::createItem();

            if ( $product_type === 'variation' ) {
                $check_item_group_id = 0;
            }

            foreach ($attributes as $key => $value) {
                if ($key == 'delivery') {
                    $item->$key($value['DELIVERY_ID'], $value['DELIVERY_PRICE'], $value['DELIVERY_PRICE_COD']); // invoke $key as method of $item object.
                } elseif ($key === 'param') {
                    $item->$key($key, $value);
                } else {
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

            if( $product_type === 'variation' && $check_item_group_id === 0){
                $item->item_group_id($product->get_parent_id());
            }
        }
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
        return 'SHOPITEM';
    }

    public function setItemsWrapper()
    {
        return 'SHOP';
    }


    /**
     * @param $attributes
     * @return array
     */
    private function process_attributes_for_delivery($attributes)
    {
        $shipping_attr = array('DELIVERY_ID', 'DELIVERY_PRICE', 'DELIVERY_PRICE_COD');
        $default_delivery_atts = array(
            'DELIVERY_ID' => '',
            'DELIVERY_PRICE' => '',
            'DELIVERY_PRICE_COD' => ''
        );

        foreach ($attributes as $key => $value) {
            if (in_array($key, $shipping_attr)) {
                $attributes['delivery'][$key] = $value;
                unset($attributes[$key]);
            }
        }
        if (array_key_exists('delivery', $attributes)) {
            $attributes['delivery'] += $default_delivery_atts;
        }
        return $attributes;
    }


    /**
     * process atts for param attribute
     *
     * @param $attributes
     * @return mixed
     *
     * @since 6.3.2
     */
    private function process_attributes_for_param($attributes) {
        foreach ($attributes as $key => $value) {
            if(preg_match('/^PARAM/im', $key)) {
                $param_no = preg_replace('/[^0-9]/', '', $key);
                $attributes['param'][] = array(
                    'key'           => $key,
                    'name'          => $value,
                    'value'         => isset($attributes['VALUE_'.$param_no]) ? $attributes['VALUE_'.$param_no] : '',
                    'percentage'    => isset($attributes['PERCENTAGE_'.$param_no]) ? $attributes['PERCENTAGE_'.$param_no] : '',
                );
            }
        }
        foreach ($attributes as $key => $value) {
            if(preg_match('/^PARAM/im', $key)) {
                $param_no = preg_replace('/[^0-9]/', '', $key);
                unset($attributes['VALUE_' . $param_no]);
                unset($attributes['PERCENTAGE_' . $param_no]);
                unset($attributes['PARAM_NAME_' . $param_no]);
            }
        }
        return $attributes;
    }


    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format === 'xml') {
            return GlamiShopping::asRss();
        } elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
            return GlamiShopping::asTxt();
        } elseif ($this->feed_format === 'csv') {
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
