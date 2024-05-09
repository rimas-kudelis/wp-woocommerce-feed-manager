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

use RexTheme\MarktPlaatsShoppingFeed\Containers\MarktPlaatsShopping;

class Rex_Product_Feed_Marktplaats extends Rex_Product_Feed_Abstract_Generator {

    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {
        MarktPlaatsShopping::$container = null;
        MarktPlaatsShopping::init(false, $this->setItemWrapper(), 'http://admarkt.marktplaats.nl/schemas/1.0', '', $this->setItemsWrapper());
        MarktPlaatsShopping::title($this->title);
        MarktPlaatsShopping::link($this->link);
        MarktPlaatsShopping::description($this->desc);

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

        if( ( $this->rex_feed_skip_product && empty( array_keys($attributes, '') ) ) || !$this->rex_feed_skip_product ) {
            $item = MarktPlaatsShopping::createItem();

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
        }
    }

    /**
     * Check if the merchants is valid or not
     * @param $feed_merchants
     * @return bool
     */
    public function is_valid_merchant(){
        return true;
    }


    /**
     * @return string
     */
    public function setItemWrapper()
    {
        return 'admarkt:ad';
    }

    public function setItemsWrapper()
    {
        return 'admarkt:ads';
    }

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->feed_format === 'xml') {
            return MarktPlaatsShopping::asRss();
        } elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
            return MarktPlaatsShopping::asTxt();
        } elseif ($this->feed_format === 'csv') {
            return MarktPlaatsShopping::asCsv();
        }
        return MarktPlaatsShopping::asRss();
    }


    public function footer_replace() {
        $this->feed = str_replace('</admarkt:ads>', '', $this->feed);
    }

}

