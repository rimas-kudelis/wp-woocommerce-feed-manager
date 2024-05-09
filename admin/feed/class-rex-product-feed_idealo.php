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

use RexTheme\RexShoppingFeedCustom\Idealo_de\Containers\Idealo_de;

class Rex_Product_Feed_Idealo extends Rex_Product_Feed_Abstract_Generator {

	/**
	 * Create Feed
	 *
	 * @return string|string[]
	 * @since 7.3.17
	 **/
	public function make_feed() {
		Idealo_de::$container = null;

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
	 * @since 7.3.17
	 */
	protected function add_to_feed( $product, $meta_keys ) {
		$attributes = $this->get_product_data( $product, $meta_keys );

		if ( ( $this->rex_feed_skip_product && is_array( $attributes ) && !empty( $attributes ) && empty( array_keys( $attributes, '' ) ) ) || !$this->rex_feed_skip_product ) {
			$item = Idealo_de::createItem();

			foreach( $attributes as $key => $value ) {
				$item->$key( $value ); // invoke $key as method of $item object.
			}
		}
	}

	/**
	 * Return Feed
	 *
	 * @return array|bool|string
	 * @since 7.3.17
	 */
	public function returnFinalProduct(){
		return Idealo_de::asCSVFeeds();
	}

	/**
	 * This method serves as a placeholder for replacing the footer content.
	 * Subclasses should extend this class and provide their own implementation
	 * to customize or replace the footer content as needed.
	 *
	 * @return void
	 * @since 7.3.17
	 */
	public function footer_replace() {}
}