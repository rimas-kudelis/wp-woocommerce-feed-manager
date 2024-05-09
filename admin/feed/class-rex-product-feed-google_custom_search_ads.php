<?php


/**
 * The file that generates csv,tsv feed for Google custom search ads .
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

use RexTheme\RexShoppingGoogleCustomSearchAds\Containers\RexShoppingGoogleCustomSearchAds;

class Rex_Product_Feed_Google_custom_search_ads extends Rex_Product_Feed_Abstract_Generator
{

    /**
     * Create Feed for Google
     *
     * @return boolean
     * @author
     **/
    public function make_feed()
    {
        //putting data in xml file
        RexShoppingGoogleCustomSearchAds::$container = null;
        RexShoppingGoogleCustomSearchAds::title($this->title);
        RexShoppingGoogleCustomSearchAds::link($this->link);
        RexShoppingGoogleCustomSearchAds::description($this->desc);

        $this->generate_product_feed();

        if ($this->feed_format === 'csv') {
            $this->feed = $this->returnFinalProduct();
        }

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
        $attributes = $this->process_attributes_for_shipping_tax($attributes);

        if( ( $this->rex_feed_skip_product && empty( array_keys($attributes, '') ) ) || !$this->rex_feed_skip_product ) {
            $item = RexShoppingGoogleCustomSearchAds::createItem();

            if ( $product_type === 'variation' ) {
                $check_item_group_id = 0;
            }

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

            if( $product_type === 'variation' && $check_item_group_id === 0){
                $item->item_group_id($product->get_parent_id());
            }
        }
    }


    /**
     * @param $attributes
     * @return array
     */
    private function process_attributes_for_shipping_tax($attributes)
    {
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

        foreach ($attributes as $key => $value) {
            if (in_array($key, $shipping_attr)) {
                $attributes['shipping'][$key] = $value;
                unset($attributes[$key]);
            }

            if (in_array($key, $tax_attr)) {
                $attributes['tax'][$key] = $value;
                unset($attributes[$key]);
            }
        }
        if (array_key_exists('shipping', $attributes)) {
            $attributes['shipping'] = $default_shipping_values;
        }
        if (array_key_exists('tax', $attributes)) {
            $attributes['tax'] = $default_tax_values;
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
        return RexShoppingGoogleCustomSearchAds::asCsv();
    }

    public function footer_replace()
    {
        $this->feed = str_replace('</channel></rss>', '', $this->feed);
    }

}
