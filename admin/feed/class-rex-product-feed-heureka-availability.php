<?php

/**
 * The file that generates xml feed for any merchant with custom configuration.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      7.2.12
 * @author     RexTheme <info@rextheme.com>
 */

use RexTheme\RexHeurekaAvailability\Containers\RexHeurekaAvailability;

class Rex_Product_Feed_Heureka_availability extends Rex_Product_Feed_Abstract_Generator {

    /**
     * @desc Create Feed
     * @since 7.2.12
     * @return bool|string|string[]
     * @throws Exception
     */
    public function make_feed() {
        RexHeurekaAvailability::init( false, 'item', null, '', 'item_list' );
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
     * @desc Adding items to feed
     * @since 7.2.12
     * @param $product
     * @param $meta_keys
     * @param string $product_type
     */
    protected function add_to_feed( $product, $meta_keys, $product_type = '' )
    {
        $attributes = $this->get_product_data( $product, $meta_keys );

        if( is_array( $attributes ) && isset( $attributes[ 'stock_quantity' ] ) && 0 < $attributes[ 'stock_quantity' ] ) {
            $attributes = $this->process_depot_attributes( $attributes );

            if( ( is_array( $attributes ) && !empty( $attributes ) && $this->rex_feed_skip_product && empty( array_keys( $attributes, '' ) ) ) || !$this->rex_feed_skip_product ) {
                $item = RexHeurekaAvailability::createItem();
                $item->id( $product->get_id() );

                foreach( $attributes as $key => $value ) {
                    if( $this->rex_feed_skip_row && $this->feed_format === 'xml' ) {
                        if( $value != '' ) {
                            $item->$key( $value ); // invoke $key as method of $item object.
                        }
                    }
                    else {
                        $item->$key( $value ); // invoke $key as method of $item object.
                    }
                }
            }
        }
    }


    /**
     * @desc process depot attribute parameters
     * @since 7.2.17
     * @param $attributes
     * @return mixed
     */
    private function process_depot_attributes( $attributes ) {
        for( $index = 1; $index <= 5; $index++ ) {
            if( isset( $attributes[ 'depot_id_' . $index ] ) ) {
                $attributes[ 'depot_' . $index ][ 'id' ] = $attributes[ 'depot_id_' . $index ];
                unset( $attributes[ 'depot_id_' . $index ] );
            }
            if( isset( $attributes[ 'stock_quantity_' . $index ] ) ) {
                $attributes[ 'depot_' . $index ][ 'stock_quantity' ] = $attributes[ 'stock_quantity_' . $index ];
                unset( $attributes[ 'stock_quantity_' . $index ] );
            }
            if( isset( $attributes[ 'orderDeadline_' . $index ] ) ) {
                $attributes[ 'depot_' . $index ][ 'orderDeadline' ] = $attributes[ 'orderDeadline_' . $index ];
                unset( $attributes[ 'orderDeadline_' . $index ] );
            }
        }
        return $attributes;
    }


    /**
     * @desc Return Feed
     * @since 7.2.12
     * @return array|bool|string
     */
    public function returnFinalProduct(){

        if ($this->feed_format === 'xml') {
            return RexHeurekaAvailability::asRss();
        }
        return RexHeurekaAvailability::asRss();
    }

    /**
     * @desc Replace Footer for xml feed
     * @since 7.2.12
     * @return void
     */
    public function footer_replace()
    {
        $this->feed = str_replace( '</item_list>', '', $this->feed );
    }
}