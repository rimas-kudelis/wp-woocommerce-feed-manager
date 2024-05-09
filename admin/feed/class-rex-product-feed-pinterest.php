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

use Rex\Pinterest\Containers\Pinterest;

class Rex_Product_Feed_Pinterest extends Rex_Product_Feed_Abstract_Generator {

    /**
     * Create Feed for Google
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {

        //putting data in xml file
        Pinterest::$container = null;
        Pinterest::title($this->title);
        Pinterest::link($this->link);
        Pinterest::description($this->desc);

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
        $attributes = $this->process_attributes_for_shipping_tax( $attributes );

        if( ( $this->rex_feed_skip_product && empty( array_keys($attributes, '') ) ) || !$this->rex_feed_skip_product ) {
            $item = Pinterest::createItem();

            if ( $product_type === 'variation' ) {
                $check_item_group_id = 0;
            }

            foreach ($attributes as $key => $value) {
                if($key == 'shipping') {
                    $item->$key($value['shipping_country'], $value['shipping_service'], $value['shipping_price'], $value['shipping_region']); // invoke $key as method of $item object.
                }
                elseif ($key == 'tax') {
                    $item->$key($value['tax_country'], $value['tax_ship'], $value['tax_rate'], $value['tax_region']); // invoke $key as method of $item object.
                }
                else {
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
            return Pinterest::asRss();
        } elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
            return Pinterest::asTxt();
        } elseif ($this->feed_format === 'csv') {
            return Pinterest::asCsv();
        }
        return Pinterest::asRss();
    }

    public function footer_replace() {
        $this->feed = str_replace('</channel></rss>', '', $this->feed);

    }

}