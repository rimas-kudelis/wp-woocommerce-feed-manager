<?php
/**
 * The file that generates xml feed for any merchant with custom configuration.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Rakuten
 * @subpackage Rex_Product_Feed_Rakuten/includes
 * @author     RexTheme <info@rextheme.com>
 */ 
use RexTheme\RexShoppingMirakl\Containers\RexShopping;

class Rex_Product_Feed_Mirakl extends Rex_Product_Feed_Abstract_Generator
{
    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed()
    {
        RexShopping::$container = null;
        RexShopping::init(false, $this->setItemWrapper(), '', '', $this->setItemsWrapper());

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
        $attributes =$this->process_channel_prices( $attributes );
        $attributes = $this->process_attributes_for_delivery( $attributes );
        $attributes = $this->process_attributes_for_param( $attributes );

        if( ( $this->rex_feed_skip_product && empty( array_keys($attributes, '') ) ) || !$this->rex_feed_skip_product ) {
            $item = RexShopping::createItem();

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
        return 'shop';
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
     * process atts for param attribute
     *
     * @param $atts
     * @return mixed
     *
     * @since 6.3.2
     */
    private function process_attributes_for_param($atts) {
        foreach ($atts as $key => $value) {
            if(preg_match('/^PARAM/im', $key)) {
                $param_no = preg_replace('/[^0-9]/', '', $key);
                $atts['param'][] = array(
                    'key'           => $key,
                    'name'          => $value,
                    'value'         => isset($atts['VALUE_'.$param_no]) ? $atts['VALUE_'.$param_no] : '',
                    'percentage'    => isset($atts['PERCENTAGE_'.$param_no]) ? $atts['PERCENTAGE_'.$param_no] : '',
                );
                unset($atts['VALUE_' . $param_no]);
                unset($atts['PERCENTAGE_' . $param_no]);
                unset($atts['PARAM_NAME_' . $param_no]);
            }
        }
        return $atts;
    }

    /**
     * Process and format Mirakl channel prices
     *
     * @param array $attributes Feed attributes
     *
     * @return mixed
     * @since 7.3.3
     */
    private function process_channel_prices( $attributes ) {
        if( is_array( $attributes ) && !empty( $attributes ) ) {
            foreach( $attributes as $key => $value ) {
                if(
                    preg_match( '/^channel-code-[0-9]$/', $key )
                    || preg_match( '/^price-[0-9]$/', $key )
                    || preg_match( '/^discount-price-[0-9]$/', $key )
                    || preg_match( '/^discount-start-date-[0-9]$/', $key )
                    || preg_match( '/^discount-end-date-[0-9]$/', $key )
                ) {
                    $index = preg_replace( '/[^0-9]/', '', $key );
                    if( preg_match( '/^channel-code-[0-9]$/', $key ) ) {
                        $attributes[ "Channel {$index} Prices" ][ 'channel-code' ] = $value;
                    }
                    elseif( preg_match( '/^price-[0-9]$/', $key ) ) {
                        $attributes[ "Channel {$index} Prices" ][ 'price' ] = $value;
                    }
                    elseif( preg_match( '/^discount-price-[0-9]$/', $key ) ) {
                        $attributes[ "Channel {$index} Prices" ][ 'discount-price' ] = $value;
                    }
                    elseif( preg_match( '/^discount-start-date-[0-9]$/', $key ) ) {
                        $attributes[ "Channel {$index} Prices" ][ 'discount-start-date' ] = $value;
                    }
                    elseif( preg_match( '/^discount-end-date-[0-9]$/', $key ) ) {
                        $attributes[ "Channel {$index} Prices" ][ 'discount-end-date' ] = $value;
                    }
                    unset( $attributes[ $key ] );
                }
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
            return RexShopping::asRss();
        } elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
            return RexShopping::asTxt();
        } elseif ($this->feed_format === 'csv') {
            return RexShopping::asCsv();
        }
        return RexShopping::asRss();
    }

    /**
     * Replace footer of feed
     *
     * @return void
     * @since 6.6.1
     */
    public function footer_replace()
    {
        $this->feed = str_replace('</offers></import>', '', $this->feed);
    }
}
