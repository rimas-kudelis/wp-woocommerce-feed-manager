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

use RexTheme\GulogGratis\Containers\GulogGratis;

class Rex_Product_Feed_Gulog_gratis extends Rex_Product_Feed_Other {

	/**
	 * Create Feed
	 *
	 * @return boolean
	 * @author
	 **/
	public function make_feed() {
		GulogGratis::init($this->get_wrapper(), $this->get_item_wrapper(), $this->get_namespace(),  $this->get_version(), $this->get_items_wrapper(), $this->get_stand_alone(), $this->get_wrapper_el(), $this->get_namespace_prefix() );

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

        if( ( $this->rex_feed_skip_product && empty( array_keys($attributes, '') ) ) || !$this->rex_feed_skip_product ) {
            $item = GulogGratis::createItem();
            $item->id($product->get_id());

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
			return GulogGratis::asRss();
		} elseif ($this->feed_format === 'text' || $this->feed_format === 'tsv') {
			return GulogGratis::asTxt();
		} elseif ($this->feed_format === 'text_pipe') {
			return GulogGratis::asTxtPipe();
		} elseif ($this->feed_format === 'csv') {
			return GulogGratis::asCsv();
		}
		return GulogGratis::asRss();
	}


	public function footer_replace()
	{
		$this->feed = str_replace( '</ads>', '', $this->feed );
	}
}
