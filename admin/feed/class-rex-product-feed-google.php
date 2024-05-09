<?php

/**
 * The file that generates xml feed for Facebook.
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
     * @return string|string[]
     **/
    public function make_feed() {
        GoogleShopping::$container = null;
        GoogleShopping::title( $this->title );
        GoogleShopping::link( $this->link );
        GoogleShopping::description( $this->desc );

        // Generate feed for both simple and variable products.
        $this->generate_product_feed();

        $this->feed = $this->returnFinalProduct();

        if ( $this->batch >= $this->tbatch ) {
            $this->save_feed( $this->feed_format );
            return [ 'msg' => 'finish' ];
        }
        else {
            return $this->save_feed( $this->feed_format );
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

        if( ( $this->rex_feed_skip_product && empty( array_keys( $attributes, '' ) ) ) || !$this->rex_feed_skip_product ) {
            $item = GoogleShopping::createItem();

            if( $product_type === 'variation' ) {
                $check_item_group_id = 0;
            }

            foreach( $attributes as $key => $value ) {
                if( 'shipping' === $key ) {
                    if ( is_array( $value ) && !empty( $value ) ) {
                        $shipping_vals = [];
                        foreach ( $value as $shipping ) {
                            if ( 'xml' === $this->feed_format ) {
                                $shipping_country = isset($shipping['country']) ? $shipping['country'] : '';
                                $shipping_region = isset($shipping['region']) ? $shipping['region'] : '';
                                $shipping_service = isset($shipping['service']) ? $shipping['service'] : '';
                                $shipping_price = isset($shipping['price']) ? $shipping['price'] : '';

                                $item->$key( $shipping_country, $shipping_region, $shipping_service, $shipping_price ); // invoke $key as method of $item object.
                            }
                            elseif ( 'csv' === $this->feed_format ) {
                                $shipping_vals[] = implode( ':', $shipping );
                            }
                        }
                        if ( 'csv' === $this->feed_format ) {
                            $item->$key( null, null, null, null, implode( '||', $shipping_vals ) );
                        }
                    }
                }
                elseif( 'tax' === $key ) {
                    if ( is_array( $value ) && !empty( $value ) ) {
                        $tax_vals = [];
                        foreach ( $value as $tax ) {
                            $tax_country = isset( $tax->tax_rate_country ) ? $tax->tax_rate_country : '';
                            $tax_region = isset( $tax->tax_rate_state ) ? $tax->tax_rate_state : '';
                            $tax_postcode = isset( $tax->postcode ) && !empty( $tax->postcode ) ? implode( ', ', $tax->postcode ) : '';
                            $tax_rate = isset( $tax->tax_rate ) ? $tax->tax_rate : '';
                            $tax_ship = isset( $tax->tax_rate_shipping ) && $tax->tax_rate_shipping === '1' ? 'yes' : 'no';

                            if ( 'xml' === $this->feed_format ) {
                                $item->$key( $tax_country, $tax_region, $tax_postcode, $tax_rate, $tax_ship ); // invoke $key as method of $item object.
                            }
                            elseif ( 'csv' === $this->feed_format ) {
                                $tax_vals[] = $tax_country . ':' . $tax_region. ':' . $tax_postcode. ':' . $tax_rate. ':' . $tax_ship;
                            }
                        }

                        if ( 'csv' === $this->feed_format ) {
                            $item->$key( null, null, null, null, null, implode( '||', $tax_vals ) );
                        }
                    }
                }
                else {
                    if( $key == 'custom' || $key == 'Custom' ) {
                        $key = $key . ' ';
                    }
                    if( $this->rex_feed_skip_row && $this->feed_format === 'xml' ) {
                        if( $value != '' ) {
                            $item->$key( $value ); // invoke $key as method of $item object.
                        }
                    }
                    else {
                        $item->$key( $value ); // invoke $key as method of $item object.
                    }
                }

                if( $product_type === 'variation' && 'item_group_id' == $key ) {
                    $check_item_group_id = 1;
                }
            }

            if( $product_type === 'variation' && $check_item_group_id === 0 ) {
                $item->item_group_id( $product->get_parent_id() );
            }
        }
    }

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct() {
        if ( $this->feed_format === 'xml' ) {
            return GoogleShopping::asRss();
        }
        elseif ( $this->feed_format === 'text' || $this->feed_format === 'tsv' ) {
            return GoogleShopping::asTxt();
        }
        elseif ( $this->feed_format === 'csv' ) {
            return GoogleShopping::asCsv();
        }
        return GoogleShopping::asRss();
    }

    /**
     * XML footer remove by replacing
     * for multiple batches
     *
     * @return void
     */
    public function footer_replace() {
        $this->feed = str_replace('</channel></rss>', '', $this->feed);
    }
}
