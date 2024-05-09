<?php

/**
 * The file that generates xml feed for any merchant with custom configuration.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @author     RexTheme <info@rextheme.com>
 */

use RexTheme\Rozetka\Containers\Rozetka;

class Rex_Product_Feed_Rozetka extends Rex_Product_Feed_Other {

    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {
        Rozetka::init($this->get_wrapper(), $this->get_item_wrapper(), $this->get_namespace(),  $this->get_version(), $this->get_items_wrapper(), $this->get_stand_alone(), $this->get_wrapper_el() );
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
     * Adding items to feed
     *
     * @param $product
     * @param $meta_keys
     * @param string $product_type
     */
    protected function add_to_feed( $product, $meta_keys, $product_type = '' )
    {
        $attributes = $this->get_product_data( $product, $meta_keys );

        if( ( $this->rex_feed_skip_product && empty( array_keys( $attributes, '' ) ) ) || !$this->rex_feed_skip_product ) {
            $item         = Rozetka::createItem();
            $availability = $product->get_availability();
            $availability = isset( $availability[ 'class' ] ) && $availability[ 'class' ] === 'in-stock' ? 'true' : 'false';
            $item->id( $product->get_id() );
            $item->available( $availability );

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

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct(){

        if ($this->feed_format === 'xml') {
            return Rozetka::asRss();
        } elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
            return Rozetka::asTxt();
        } elseif ($this->feed_format === 'text_pipe') {
            return Rozetka::asTxtPipe();
        } elseif ($this->feed_format === 'csv') {
            return Rozetka::asCsv();
        }
        return Rozetka::asRss();
    }


    public function footer_replace()
    {
        $this->feed = str_replace( '</offers></shop></yml_catalog>', '', $this->feed );
    }
}