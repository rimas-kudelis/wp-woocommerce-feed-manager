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

use LukeSnowden\GoogleShoppingFeed\Containers\GoogleShopping;

class Rex_Product_Feed_Google extends Rex_Product_Feed_Abstract_Generator {

	/**
	 * Create Feed for Google
	 *
	 * @return boolean
	 * @author
	 **/
	public function make_feed() {

		GoogleShopping::title($this->title);
		GoogleShopping::link($this->link);
		GoogleShopping::description($this->desc);

    // Loop through all products.
		foreach( $this->products as $product ) {

			$atts = $this->get_product_data( $product );
			$item = GoogleShopping::createItem();

      // add all attributes for each product.
      foreach ($atts as $key => $value) {
        $item->$key($value); // invoke $key as method of $item object.
      }

		}

		$this->feed = GoogleShopping::asRss();
		return $this->save_feed();

	}

}
