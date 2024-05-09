<?php

/**
 * The file that generates xml feed for Shopee Feed.
 *
 * A class definition that includes functions used for generating csv feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Google
 * @subpackage Rex_Product_Feed_Google/includes
 * @author     RexTheme <info@rextheme.com>
 */

use RexTheme\RexShoppingFeed\Containers\RexShopping;

class Rex_Product_Feed_Shopee extends Rex_Product_Feed_Abstract_Generator {

	private $product_data = array();

	/**
	 * Create Feed for Google
	 *
	 * @return boolean
	 * @author
	 **/
	public function make_feed() {

		RexShopping::$container = null;
		RexShopping::title($this->title);
		RexShopping::link($this->link);
		RexShopping::description($this->desc);

		$this->generate_product_feed();

		if ( $this->feed_format === 'csv' ) {
			$this->feed = $this->returnFinalProduct();
		}

		if ($this->batch >= $this->tbatch ) {
			$this->save_feed($this->feed_format );

			return array(
				'msg' => 'finish'
			);
		}else {
			return $this->save_feed($this->feed_format );
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
            $item = RexShopping::createItem();

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
	 * Return Feed
	 *
	 * @return array|bool|string
	 */
	public function returnFinalProduct(){

		if ($this->feed_format === 'xml') {
			return RexShopping::asRss();
		} elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
			return RexShopping::asTxt();
		} elseif ($this->feed_format === 'csv') {
			return RexShopping::asCsv();
		}
		return RexShopping::asRss();
	}

	public function footer_replace() {}
}