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

use RexTheme\RexYandexShoppingFeed\Containers\RexShopping;

class Rex_Product_Feed_Yandex extends Rex_Product_Feed_Abstract_Generator {

    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {
        RexShopping::$container = null;
        RexShopping::init( true, $this->setItemWrapper(), null, '', $this->setItemsWrapper(), false, 'shop' );
        RexShopping::title( get_option( 'blogname' ) );
        RexShopping::company( $this->yandex_company_name ?: get_option( 'blogname' ) );
        RexShopping::link( $this->link );

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
    protected function add_to_feed( $product, $meta_keys, $product_type = '' ) {
        $attributes = $this->get_product_data( $product, $meta_keys );

        if( ( $this->rex_feed_skip_product && is_array( $attributes ) && empty( array_keys($attributes, '') ) ) || !$this->rex_feed_skip_product ) {
            $item = RexShopping::createItem();

            foreach ($attributes as $key => $value) {
                if( 'picture' === $key && !empty( $value ) && is_array( $value ) ) {
                    $value = array_slice( $value, 0, 10 );
                }
                elseif( 'oldprice' === $key ) {
                    $regular_price = 0;
                    if( isset( $attributes[ 'woo_discount_rules_price' ] ) ) {
                        $regular_price = $attributes[ 'woo_discount_rules_price' ];
                    }
                    elseif( isset( $attributes[ 'price' ] ) ) {
                        $regular_price = $attributes[ 'price' ];
                    }

                    if( !$this->yandex_old_price && $regular_price && $regular_price >= $value ) {
                        continue;
                    }
                }
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
    }


    /**
     * @return string
     */
    public function setItemWrapper()
    {
        return 'offer';
    }

    public function setItemsWrapper()
    {
        return 'yml_catalog';
    }

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format === 'xml' || $this->feed_format === 'yml') {
            return RexShopping::asRss();
        } elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
            return RexShopping::asTxt();
        } elseif ($this->feed_format === 'csv') {
            return RexShopping::asCsv();
        }
        return RexShopping::asRss();
    }

    //replace footer of feed
    public function footer_replace() {
        $this->feed = str_replace( '</offers></shop></yml_catalog>', '', $this->feed);
    }

}

