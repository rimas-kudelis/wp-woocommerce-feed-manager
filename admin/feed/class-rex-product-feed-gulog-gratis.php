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
	 * Generate feed
	 */
	protected function generate_product_feed(){
		$product_meta_keys = Rex_Feed_Attributes::get_attributes();
		$simple_products = [];
		$variation_products = [];
		$variable_parent = [];
		$group_products = [];
		$total_products = get_post_meta($this->id, 'rex_feed_total_products', true) ? get_post_meta($this->id, 'rex_feed_total_products', true) : array(
			'total' => 0,
			'simple' => 0,
			'variable' => 0,
			'variable_parent' => 0,
			'group' => 0,
		);

		if($this->batch == 1) {
			$total_products = array(
				'total' => 0,
				'simple' => 0,
				'variable' => 0,
				'variable_parent' => 0,
				'group' => 0,
			);
		}

		foreach( $this->products as $productId ) {
			$product = wc_get_product( $productId );

			if ( ! is_object( $product ) ) {
				continue;
			}

			if ( $this->exclude_hidden_products ) {
				if ( !$product->is_visible() ) {
					continue;
				}
			}

			if ( $product->is_type( 'variable' ) && $product->has_child() ) {
				if($this->variable_product) {
					$variable_parent[] = $productId;
					$variable_product = new WC_Product_Variable($productId);
					$atts = $this->get_product_data( $variable_product, $product_meta_keys );
					$item = GulogGratis::createItem();
					$item->id($productId);
					foreach ($atts as $key => $value) {
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

				if($this->product_scope === 'product_cat' || $this->product_scope === 'product_tag') {
					if ( $this->exclude_hidden_products ) {
						$variations = $product->get_visible_children();
					}else {
						$variations = $product->get_children();
					}

					if( $variations && $this->product_scope !='filter' ) {
						foreach ($variations as $variation) {
							if($this->variations) {
								$variation_products[] = $variation;
								$item = GulogGratis::createItem();
								$item->id($variation);
								$variation_product = wc_get_product( $variation );
								$atts = $this->get_product_data( $variation_product, $product_meta_keys );
								foreach ($atts as $key => $value) {
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
					}
				}
			}

			if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'composite' ) || $product->is_type( 'bundle' )) {
				$simple_products[] = $productId;
				$atts = $this->get_product_data( $product, $product_meta_keys );
				$item = GulogGratis::createItem();
				$item->id($productId);
				foreach ($atts as $key => $value) {
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

			if( $this->product_scope === 'all' || $this->product_scope =='product_filter' || $this->product_scope =='filter') {
				if ($product->get_type() == 'variation') {
					$variation_products[] = $productId;
					$item = GulogGratis::createItem();
					$item->id($productId);
					$atts = $this->get_product_data($product, $product_meta_keys);
					foreach ($atts as $key => $value) {
						if ( $this->rex_feed_skip_row && $this->feed_format === 'xml' ) {
							if ( $value != '' ) {
								$item->$key($value); // invoke $key as method of $item object.
							}
						}
						else {
							$item->$key($value); // invoke $key as method of $item object.
						}
					} // invoke $key as method of $item object.
				}
			}

			if( $product->is_type( 'grouped' ) && $this->parent_product || $product->is_type( 'woosb' )){
				if($this->parent_product) {
					$group_products[] = $productId;
					$item = GulogGratis::createItem();
					$item->id($productId);
					$atts = $this->get_product_data( $product, $product_meta_keys );
					// add all attributes for each product.
					foreach ($atts as $key => $value) {
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
		}

		$total_products = array(
			'total' => (int) $total_products['total'] + (int) count($simple_products) + (int) count($variation_products) + (int) count($group_products) + (int) count($variable_parent),
			'simple' => (int) $total_products['simple'] + (int) count($simple_products),
			'variable' => (int) $total_products['variable'] + (int) count($variation_products),
			'variable_parent' => (int) $total_products['variable_parent'] + (int) count($variable_parent),
			'group' => (int) $total_products['group'] + (int) count($group_products),
		);
		update_post_meta( $this->id, 'rex_feed_total_products', $total_products );
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