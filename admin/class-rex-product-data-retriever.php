<?php
/**
 * Class for retriving product data based on user selected feed configuration.
 *
 * Get the product data based on feed config selected by user.
 *
 * @package    Rex_Product_Data_Retriever
 * @subpackage Rex_Product_Feed/admin
 * @author     RexTheme <info@rextheme.com>
 */
use Wdr\App\Controllers\ManageDiscount;
use Wdr\App\Models\DBTable;
use Aelia\WC\CurrencySwitcher\WC_Aelia_CurrencySwitcher;

class Rex_Product_Data_Retriever{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $feed_rules;
	/**
	 * @var string $feed_id The id of the feed
	 */
	protected $analytics_params;

	/**
	 * Contains all available meta keys for products.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $product_meta_keys;

	/**
	 * The data of product retrived by feed_rules.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $data    The current version of this plugin.
	 */
	protected $data;


	/**
	 * Metabox instance of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $metabox    The current metabox of this plugin.
	 */
	protected $product;

	/**
	 * Variant atts for feed.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $metabox    The current metabox of this plugin.
	 */
	protected $variant_atts = array( 'color', 'pattern', 'material', 'age_group', 'gender', 'size', 'size_type', 'size_system' );

	/**
	 * Additional images of current product.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $metabox    The current metabox of this plugin.
	 */
	protected $additional_images = array();


	/**
	 * Append variation
	 *
	 * @since    3.2
	 * @access   private
	 * @var      object    $append_variation
	 */
	protected $append_variation;


	/**
	 * Append variation
	 *
	 * @since    3.2
	 * @access   private
	 * @var      object    $aelia_currency
	 */
	protected $aelia_currency;


	/**
	 * check if debug is enabled
	 *
	 * @var Rex_Product_Data_Retriever $enable_log
	 */
	protected $is_logging_enabled;


	/**
	 * @var Rex_Product_Data_Retriever $feed
	 */
	protected $feed;


	protected $wcml;

	protected $wcml_currency;

	public $discount_manage;

	/**
	 * Initialize the class and set its properties.
	 *
	 * Rex_Product_Data_Retriever constructor.
	 * @param WC_Product $product
	 * @param Rex_Product_Feed_Abstract_Generator $feed
	 * @param $product_meta_keys
	 * @since 6.1.0
	 */
	public function __construct( WC_Product $product, Rex_Product_Feed_Abstract_Generator $feed, $product_meta_keys ) {


		$this->is_logging_enabled   = is_wpfm_logging_enabled();
		$this->product              = $product;
		$this->analytics_params     = $feed->analytics_params;
		$this->feed_rules           = $feed->feed_rules;
		$this->product_meta_keys    = $product_meta_keys;
		$this->append_variation     = $feed->append_variation;
		$this->aelia_currency       = $feed->aelia_currency;
		$this->feed                 = $feed;
		$this->wcml                 = false;
		$this->wcml_currency        = '';
		if( class_exists( 'SitePress' ) && function_exists( 'wcml_loader' ) ) {
			$this->wcml = true;
			$this->wcml_currency = $this->feed->wcml_currency;
		}

		if( $this->is_logging_enabled ) {
			$log = wc_get_logger();
			$log->info('*************************', array( 'source' => 'WPFM',));
			$log->info(__( 'Start product processing.', 'rex-product-feed' ), array('source' => 'WPFM',));
			$log->info('Product ID: '.$this->product->get_id(), array('source' => 'WPFM',));
			$log->info('Product Name: '.$this->product->get_title(), array('source' => 'WPFM',));
		}

		$this->set_all_value();
		if( $this->is_logging_enabled ) {
			$log->info(__( 'End product processing.', 'rex-product-feed' ), array('source' => 'WPFM',));
			$log->info('*************************', array('source' => 'WPFM',));
		}
	}



	/**
	 * Setup Testing feed rules for every attributes.
	 * Just to check if this class return proper values.
	 *
	 * @since    1.0.2
	 */
	public function set_test_feed_rules() {
		$this->feed_rules = array();
		foreach ($this->product_meta_keys as $key_cat => $attrs) {
			foreach ($attrs as $key => $attr) {
				$this->feed_rules[] = array(
					'attr'     => $key,
					'cust_attr'=> $key,
					'type'     => 'meta',
					'meta_key' => $key,
					'st_value' => '',
					'prefix'   => '',
					'suffix'   => '',
					'escape'   => 'default',
					'limit'    => 0,
				);
			}
		}
	}


	public function get_random_key() {
		return md5(uniqid(rand(), true));
	}

	/**
	 * Retrive and setup all data for every feed rules.
	 *
	 * @since    1.0.0
	 */
	public function set_all_value() {
		$this->data = array();

		foreach ( $this->feed_rules as $key => $rule ) {

			if(array_key_exists('attr', $rule)) {
				if($rule['attr']) {
					if($rule['attr'] === 'attributes') {
						$this->data[ $rule['attr']][] = array(
							'name' => str_replace( 'bwf_attr_pa_', '', $rule['meta_key']),
							'value' => $this->set_val( $rule )

						);
					}else {
						$this->data[ $rule['attr'] ] = $this->set_val( $rule );
					}
				}
			}elseif (array_key_exists('cust_attr', $rule)) {
				if($rule['cust_attr']) {
					$this->data[$rule['cust_attr']] = $this->set_val( $rule );
				}
			}else {
				$this->data[ $rule['attr'] ] = $this->set_val( $rule );
			}
		}
	}


	/**
	 * Set value for a single feed rule.
	 *
	 * @since    1.0.0
	 */
	public function set_val( $rule ) {
		$val = '';

		if ( 'static' === $rule['type'] ) {
			$val = $rule['st_value'];
		}
		elseif ( 'meta' === $rule['type'] && $this->is_primary_attr( $rule['meta_key'] ) ) {
			$val = $this->set_pr_att( $rule['meta_key'] , $rule['escape'] );
		}
		elseif ( 'meta' === $rule['type'] && $this->is_woodmart_attr( $rule['meta_key'] ) ) {
			$val = $this->set_woodmart_att( $rule['meta_key'] );
		}elseif ( 'meta' === $rule['type'] && $this->is_perfect_attr( $rule['meta_key'] ) ) {
			$val = $this->set_perfect_attr( $rule['meta_key'] );
		}elseif ( 'meta' === $rule['type'] && $this->is_wc_brand_attr( $rule['meta_key'] ) ) {
			$val = $this->set_wc_brand_attr( $rule['meta_key'] );
		}elseif ( 'meta' === $rule['type'] && $this->is_image_attr( $rule['meta_key'] ) ) {
			$val = $this->set_image_att( $rule['meta_key']  );
		}
		elseif ( 'meta' === $rule['type'] && $this->is_product_attr( $rule['meta_key'] ) ) {
			$val = $this->set_product_att( $rule['meta_key']  );
		}
		elseif ( 'meta' === $rule['type'] && $this->is_product_dynamic_attr( $rule['meta_key'] ) ) {
			$val = $this->set_product_dynamic_att( $rule['meta_key']  );
		}
		elseif ( 'meta' === $rule['type'] && $this->is_product_custom_attr( $rule['meta_key'] ) ) {
			$val = $this->set_product_custom_att( $rule['meta_key']  );
		}
		elseif ( 'meta' === $rule['type'] && $this->is_product_category_mapper_attr( $rule['meta_key'] ) ) {
			$val = $this->set_cat_mapper_att( $rule['meta_key']  );
		}
		elseif ( 'meta' === $rule['type'] && $this->is_glami_attr( $rule['meta_key'] ) ) {
			$val = $this->set_glami_att( $rule['meta_key']  );
		}

		// maybe escape
		$val = $this->maybe_escape($val, $rule['escape']);

		// maybe add prefix/suffix
		$val = $this->maybe_add_prefix_suffix($val, $rule);

		// maybe limit
		$val = $this->maybe_limit($val, $rule['limit']);
		// $val = trim(preg_replace('/(?:\s\s+|\n|\t)/', '',$val));
		return $val;

	}



	/**
	 * Return all data.
	 *
	 * @since    1.0.0
	 */
	public function get_all_data() {
		return $this->data;
	}

	/**
	 * Set a woodmart gallery attribute.
	 *
	 * @since    1.0.0
	 */
	protected function set_woodmart_att($key){
		$id = substr($key, strpos($key, "_") + 1);
		if('image_'.$id == $key){
			return $this->get_woodmart_gallery($id);
		}

	}


	/**
	 * get a woodmart gallery attribute.
	 *
	 * @since    1.0.0
	 */
	public function get_woodmart_gallery($id){
		$product_id = $this->product->get_id();
		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			$parent_id = $this->product->get_parent_id();
			$all_gallery = get_post_meta($parent_id,'woodmart_variation_gallery_data',true);
			if(isset($all_gallery[$product_id])){
				$image_ids = $all_gallery[$product_id];
				if($image_ids) {
					$image_ids = explode(',', $image_ids);
					if(isset($image_ids[$id])) {
						$image_id = $image_ids[$id];
						if($image_id){
							return  wp_get_attachment_url($image_id);
						}
					}
				}
			}
		}
		return '';
	}

	/**
	 * Set Perfect woocommerce brand attribute
	 */
	protected function set_perfect_attr( $key ) {
		$brands = wp_get_object_terms($this->product->get_id(), 'pwb-brand');
		$brnd = '';
		$i = 0;
		foreach($brands as $brand){
			if($i == 0){
				$brnd .= $brand->name;
			}else{
				$brnd .= ', '.$brand->name;
			}
			$i++;
		}
		return $brnd;
	}
	/**
	 * Set woocommerce brand attribute
	 * @param key meta_key
	 */
	protected function set_wc_brand_attr( $key ) {
		$brands = '';

		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			$brands = wp_get_post_terms( $this->product->get_parent_id(), 'berocket_brand', array("fields" => "all") );

		}else{
			$brands = wp_get_post_terms( $this->product->get_ID(), 'berocket_brand', array("fields" => "all") );
		}
		$brnd = '';
		if(!empty($brands)){

			$i = 0;
			foreach($brands as $brand){
				if($i == 0){
					$brnd .= $brand->name;
				}else{
					$brnd .= ', '.$brand->name;
				}
				$i++;
			}

		}

		return $brnd;
	}

	/**
	 * gets conditioned price
	 *
	 * @param $key
	 * @param $price
	 * @return float|int
	 */
	protected function get_condition_price( $key, $price ) {

		$rules = $this->feed_rules;
		$condition = '';
		foreach ( $rules as $rule ) {
			if ( $rule[ 'meta_key' ] === $key ) {
				$condition = $rule[ 'limit' ];
				break;
			}
		}
		$operator = $condition[ 0 ] == '+' || $condition[ 0 ] == '-' || $condition[ 0 ] == '*' || $condition[ 0 ] == '/' ? $condition[ 0 ] : '';
		$addition = $operator != '' ? str_replace( $operator, '', $condition) : '';

		$percentage = '';
		if ( strlen( $addition) > 1 && $addition[ strlen( $addition) - 1 ] == '%' ) {
			$percentage = $addition[ strlen( $addition) - 1 ];
			$addition = str_replace( $addition[ strlen( $addition) - 1 ], '', $addition );
			$addition = ( int ) $addition;
			$addition = $addition / 100;
		}

		$price = ( float ) $price ;
		$percentage_price = '';
		if ( $addition != '' && $operator != '' ) {
			if ( $percentage != '' ) {
				$percentage_price = $price * $addition;
			}
			switch ( $operator ) {
				case '+':
					$price = $percentage_price != '' ? $price + ( float ) $percentage_price : $price + ( float ) $addition;
					break;
				case '-':
					$price = $percentage_price != '' ? $price - ( float ) $percentage_price : $price - ( float ) $addition;
					break;
				case '*':
					$price = $percentage_price != '' ? $price * ( float ) $percentage_price : $price * ( float ) $addition;
					break;
				case '/':
					$price = $percentage_price != '' ? $price / ( float ) $percentage_price : $price / ( float ) $addition;
					break;
				default:
					break;
			}
		}

		return $price;
	}

	/**
	 * Set a primary attribute.
	 *
	 * @since    1.0.0
	 */
	protected function set_pr_att( $key, $rule = 'default' ) {

		switch ( $key ) {
			case 'id':
				return $this->product->get_id(); break;

			case 'sku':
				return $this->product->get_sku(); break;

			case 'parent_sku':
				$pr_id = '';
				if($this->product->is_type('variation')){
					$parent_id = $this->product->get_parent_id();
					$wc_parent_product = wc_get_product( $parent_id );

					$pr_id = $wc_parent_product->get_sku();

				}else{

					$pr_id = $this->product->get_sku();
				}
				return $pr_id; break;

			case 'title':
				if($this->append_variation === 'no') {
					if($this->product->is_type('variation')) {
						/*$pr_id = $this->product->get_parent_id();*/

						return $this->product->get_title();
					}

					return $this->product->get_name();
				}
				else {
					if ($this->is_children()) {
						$_product = wc_get_product( $this->product );
						$attr_summary = $_product->get_attribute_summary();
						$attr_array = explode(",", $attr_summary);

						$each_child_attr= [];
						foreach ($attr_array as $ata){
							$attr = strpbrk($ata,":");
							$each_child_attr[]=$attr;
						}

						$each_child_attr_two= [];
						foreach ($each_child_attr as $eca){
							$each_child_attr_two[]= str_replace(": "," ",$eca);
						}

						$_title = $this->product->get_title() . " - ";
						$_title = $_title . implode(', ', $each_child_attr_two);


						return $_title;

					}else {
						return $this->product->get_name();
					}
				}
				break;

			case 'yoast_title':
				$yoast_title = preg_replace('/\s+/', ' ',$this->get_yoast_seo_title());
				return $yoast_title; break;

			case 'price':
				if ($this->product->is_type( 'grouped' )) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price = apply_filters('wcml_raw_price_amount', wc_format_decimal($this->get_grouped_price($this->product, 'regular'), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_regular_price'] > 0){
							$_price = $_custom_prices['_regular_price'];
						}

						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}

						return $this->get_condition_price( $key, $_price );
					}
					else {
						$_price = $this->get_condition_price( $key, $this->get_grouped_price($this->product, 'regular') );

						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}

						return wc_format_decimal( $_price, wc_get_price_decimals());
					}
				}
				elseif ($this->product->is_type( 'composite' )) {
					$_pr  = new WC_Product_Composite($this->product->get_id());
					if($this->wcml) {
						global $woocommerce_wpml;

						if ( is_plugin_active( 'wpc-composite-products/wpc-composite-products.php' ) ) {
							$_price = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_composite_price(), wc_get_price_decimals()), $this->wcml_currency);
						}
						else {
							$_price = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_composite_regular_price(), wc_get_price_decimals()), $this->wcml_currency);
						}

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );

						if($_custom_prices['_regular_price'] > 0){
							$_price = $_custom_prices['_regular_price'];
						}

						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}

						return $_price;
					}else {
						if ( is_plugin_active( 'wpc-composite-products/wpc-composite-products.php' ) ) {
							$_price = $this->get_condition_price( $key, $_pr->get_composite_price() );
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return wc_format_decimal( $_price, wc_get_price_decimals());
						}
						else {
							$_price = $this->get_condition_price( $key, $_pr->get_composite_regular_price() );
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return wc_format_decimal( $_price, wc_get_price_decimals());
						}
					}
				}
				elseif ($this->product->is_type( 'variable' )) {

					$default_attributes = $this->get_default_attributes( $this->product );
					if($default_attributes) {
						$variation_id = $this->find_matching_product_variation( $this->product, $default_attributes );
						if($variation_id) {
							$_variation_product = wc_get_product($variation_id);
							if($this->wcml) {
								global $woocommerce_wpml;
								$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_variation_product->get_regular_price(), wc_get_price_decimals()), $this->wcml_currency);
								//if WCML price is set manually
								$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $variation_id, $this->wcml_currency );
								if($_custom_prices['_regular_price'] > 0){
									$_price = $_custom_prices['_regular_price'];
								}

								if ( $this->is_aelia_active() ){
									$_price = $this->get_converted_price( $_price );
								}

								return $this->get_condition_price( $key, $_price );
							}else {
								$_price = $this->get_condition_price( $key, $_variation_product->get_regular_price() );
								if ( $this->is_aelia_active() ){
									$_price = $this->get_converted_price( $_price );
								}
								return wc_format_decimal(  $_price, wc_get_price_decimals());
							}
						}
						if($this->wcml) {
							global $woocommerce_wpml;
							$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $this->product->get_variation_regular_price(), wc_get_price_decimals()), $this->wcml_currency);

							//if WCML price is set manually
							$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
							if($_custom_prices['_regular_price'] > 0){
								$_price = $_custom_prices['_regular_price'];
							}

							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return $this->get_condition_price( $key, $_price );
						}else {
							$_price = $this->get_condition_price( $key, $this->product->get_variation_regular_price() );
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return wc_format_decimal(  $_price, wc_get_price_decimals());
						}
					}
					else {
						if($this->wcml) {
							global $woocommerce_wpml;
							$_price   = apply_filters('wcml_raw_price_amount', wc_format_decimal( $this->product->get_variation_regular_price(), wc_get_price_decimals()), $this->wcml_currency);
							//if WCML price is set manually
							$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
							if($_custom_prices['_regular_price'] > 0){
								$_price = $_custom_prices['_regular_price'];
							}

							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}

							return $this->get_condition_price( $key, $_price );
						}
						$_price = $this->get_condition_price( $key, $this->product->get_variation_regular_price() );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return wc_format_decimal(  $_price, wc_get_price_decimals());
					}
				}
				elseif($this->product->is_type('bundle')){
					$_price = $this->product->get_bundle_price();
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return $this->get_condition_price( $key, $_price );
				}


				if($this->wcml) {
					global $woocommerce_wpml;
					$_price  = apply_filters('wcml_raw_price_amount', wc_format_decimal( $this->product->get_regular_price(), wc_get_price_decimals()), $this->wcml_currency);

					//if WCML price is set manually
					$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
					if($_custom_prices['_regular_price'] > 0){
						$_price = $_custom_prices['_regular_price'];
					}
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return $this->get_condition_price( $key, $_price );

				}else {
					$_price = $this->get_condition_price( $key, $this->product->get_regular_price() );
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return wc_format_decimal(  $_price, wc_get_price_decimals());
				}
				break;

			case 'current_price':
				if (!defined('WAD_INITIALIZED') ) {
					if ($this->product->is_type( 'grouped' )) {
						if($this->wcml) {
							global $woocommerce_wpml;
							$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal($this->get_grouped_price($this->product, 'price'), wc_get_price_decimals()), $this->wcml_currency);

							//if WCML price is set manually
							$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
							if($_custom_prices['_price'] > 0){
								$_price = $_custom_prices['_price'];
							}
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return $this->get_condition_price( $key, $_price );
						}else {
							$_price = $this->get_condition_price( $key, $this->get_grouped_price($this->product, 'price') );
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return wc_format_decimal(  $_price, wc_get_price_decimals());
						}
					}
					elseif ($this->product->is_type( 'composite' )) {
						$_pr  = new WC_Product_Composite($this->product->get_id());
						if($this->wcml) {
							global $woocommerce_wpml;
							$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_composite_price(), wc_get_price_decimals()), $this->wcml_currency);

							//if WCML price is set manually
							$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
							if($_custom_prices['_price'] > 0){
								$_price = $_custom_prices['_price'];
							}
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return $this->get_condition_price( $key, $_price );
						}else {
							$_price = $this->get_condition_price( $key, $_pr->get_composite_price() );
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return wc_format_decimal(  $_price, wc_get_price_decimals());
						}
					}elseif ($this->product->is_type( 'variable' )) {
						$default_attributes = $this->get_default_attributes( $this->product );
						if($default_attributes) {
							$variation_id = $this->find_matching_product_variation( $this->product, $default_attributes );
							if($variation_id) {
								$_variation_product = wc_get_product($variation_id);
								if($this->wcml) {
									global $woocommerce_wpml;
									$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_variation_product->get_price(), wc_get_price_decimals()), $this->wcml_currency);

									//if WCML price is set manually
									$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $variation_id, $this->wcml_currency );
									if($_custom_prices['_price'] > 0){
										$_price = $_custom_prices['_price'];
									}
									if ( $this->is_aelia_active() ){
										$_price = $this->get_converted_price( $_price );
									}
									return $this->get_condition_price( $key, $_price );
								}else {
									$_price = $this->get_condition_price( $key, $_variation_product->get_price() );
									if ( $this->is_aelia_active() ){
										$_price = $this->get_converted_price( $_price );
									}
									return wc_format_decimal(  $_price, wc_get_price_decimals());
								}
							}
						}
						else {
							if($this->wcml) {
								global $woocommerce_wpml;
								$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $this->product->get_price(), wc_get_price_decimals()), $this->wcml_currency);

								//if WCML price is set manually
								$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
								if($_custom_prices['_price'] > 0){
									$_price = $_custom_prices['_price'];
								}
								if ( $this->is_aelia_active() ){
									$_price = $this->get_converted_price( $_price );
								}
								return $this->get_condition_price( $key, $_price );
							}else{
								$_price = $this->get_condition_price( $key, $this->product->get_price() );
								if ( $this->is_aelia_active() ){
									$_price = $this->get_converted_price( $_price );
								}
								return wc_format_decimal(  $_price, wc_get_price_decimals());

							}
						}
					}
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $this->product->get_price(), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_price'] > 0){
							$_price = $_custom_prices['_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $this->get_condition_price( $key, $_price );
					}else {
						$_price = $this->get_condition_price( $key, $this->product->get_price() );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return wc_format_decimal(  $_price, wc_get_price_decimals());
					}
				}
				else {
					global $wad_discounts;

					$all_discounts = wad_get_active_discounts(true);
					foreach ($all_discounts as $discount_type => $discounts) {
						$wad_discounts[$discount_type] = array();
						foreach ($discounts as $discount_id) {
							$wad_discounts[$discount_type][$discount_id] = new WAD_Discount($discount_id);
						}
					}
					if ($this->product->is_type( 'grouped' )) {
						$sale_price = number_format( (float) $this->get_grouped_price( $this->product, 'sale' ), 2, '.', '' );

						if ( $this->is_aelia_active() ){
							$sale_price = $this->get_converted_price( $sale_price );
						}
						return $sale_price;
					}
					elseif ($this->product->is_type( 'composite' )) {
						$_pr  = new WC_Product_Composite($this->product->get_id());
						$_price = $_pr->get_composite_price();

						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return  wc_format_decimal( $_price, wc_get_price_decimals());
					}
					elseif ($this->product->is_type( 'variable' )) {
						$default_attributes = $this->get_default_attributes( $this->product );
						if($default_attributes) {
							$variation_id = $this->find_matching_product_variation( $this->product, $default_attributes );
							if($variation_id) {
								$_variation_product = wc_get_product($variation_id);
								$sale_price = number_format( (float)$_variation_product->get_price(), 2, '.', '');
							}
							else {
								$sale_price = number_format( (float)$this->product->get_variation_price(), 2, '.', '');
							}
						}
						else {
							$sale_price = number_format( (float)$this->product->get_variation_price(), 2, '.', '');
						}
					}
					else
						$sale_price = number_format((float)$this->product->get_price(), 2, '.', '');

					$_pid = wad_get_product_id_to_use($this->product);
					$_product = wc_get_product($_pid);
					if( $_product->is_type( 'variation' ) ) {
						$_pid = $_product->get_parent_id();
					}
					foreach ($wad_discounts["product"] as $discount_id => $discount_obj) {
						$o_discount = get_post_meta($discount_id, 'o-discount', true);
						$pr_list_id = $o_discount['products-list'];
						$product_list = new WAD_Products_List($pr_list_id);
						$raw_args = get_post_meta($pr_list_id, "o-list", true);
						$args = $product_list->get_args($raw_args);
						$args['fields'] = 'ids';
						$products = get_posts( $args );

						if ($discount_obj->is_applicable($_pid) && in_array($_pid, $products)) {
							$to_widthdraw = 0;
							if (in_array($discount_obj->settings["action"], array("percentage-off-pprice", "percentage-off-osubtotal")))
								$to_widthdraw = floatval (floatval($sale_price)) * floatval ($discount_obj->settings["percentage-or-fixed-amount"]) / 100;
							//Fixed discount
							else if (in_array($discount_obj->settings["action"], array("fixed-amount-off-pprice", "fixed-amount-off-osubtotal"))) {
								$to_widthdraw = $discount_obj->settings["percentage-or-fixed-amount"];
							} else if ($discount_obj->settings["action"] == "fixed-pprice")
								$to_widthdraw = floatval(floatval($sale_price)) - floatval($discount_obj->settings["percentage-or-fixed-amount"]);
							$decimals = wc_get_price_decimals();
							$discount = round( $to_widthdraw, $decimals );
							$sale_price = floatval($sale_price) - $discount;
							if($this->wcml) {
								global $woocommerce_wpml;
								$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $sale_price, wc_get_price_decimals()), $this->wcml_currency);

								//if WCML price is set manually
								$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
								if($_custom_prices['_price'] > 0){
									$_price = $_custom_prices['_price'];
								}
								if ( $this->is_aelia_active() ){
									$_price = $this->get_converted_price( $_price );
								}
								return $this->get_condition_price( $key, $_price );
							}else {
								$_price = $this->get_condition_price( $key, $sale_price );
								if ( $this->is_aelia_active() ){
									$_price = $this->get_converted_price( $_price );
								}
								return  wc_format_decimal( $_price, wc_get_price_decimals());
							}
						}
					}
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $sale_price, wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_price'] > 0){
							$_price = $_custom_prices['_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $this->get_condition_price( $key, $_price );
					}else {
						$_price = $this->get_condition_price( $key, $sale_price );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return  wc_format_decimal( $_price, wc_get_price_decimals());
					}
				}
				break;

			case 'sale_price':

				if (!defined('WAD_INITIALIZED') ) {
					if ($this->product->is_type( 'grouped' ))
						$sale_price = number_format((float)$this->get_grouped_price($this->product, 'sale'), 2, '.', '');
					elseif ($this->product->is_type( 'composite' )) {
						$sale_price =  wc_format_decimal( $this->product->get_sale_price(), wc_get_price_decimals());
					}elseif ($this->product->is_type( 'variable' )) {
						$default_attributes = $this->get_default_attributes( $this->product );
						if($default_attributes) {
							$variation_id = $this->find_matching_product_variation( $this->product, $default_attributes );
							if($variation_id) {
								$_variation_product = wc_get_product($variation_id);
								$sale_price = wc_format_decimal( $_variation_product->get_sale_price(), wc_get_price_decimals());
							}else {
								$sale_price = wc_format_decimal( $this->product->get_variation_sale_price(), wc_get_price_decimals());
							}
						}else {
							$sale_price = wc_format_decimal( $this->product->get_variation_sale_price(), wc_get_price_decimals());
						}
					}else {
						$sale_price = wc_format_decimal( $this->product->get_sale_price(), wc_get_price_decimals());
					}
					if($sale_price > 0) {
						if($this->wcml) {
							global $woocommerce_wpml;
							$_price         = apply_filters('wcml_raw_price_amount', $sale_price, $this->wcml_currency);

							//if WCML price is set manually
							$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
							if($_custom_prices['_sale_price'] > 0){
								$_price = $_custom_prices['_sale_price'];
							}

							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}

							return $this->get_condition_price( $key, $_price );
						}else {

							if ( $this->is_aelia_active() ){
								$sale_price = $this->get_converted_price( $sale_price );
							}
							return $this->get_condition_price( $key, $sale_price );
						}
					}
					return '';
				}
				else {
					global $wad_discounts;

					$all_discounts = wad_get_active_discounts(true);
					foreach ($all_discounts as $discount_type => $discounts) {
						$wad_discounts[$discount_type] = array();
						foreach ($discounts as $discount_id) {
							$wad_discounts[$discount_type][$discount_id] = new WAD_Discount($discount_id);
						}
					}

					if ($this->product->is_type( 'grouped' ))
						$sale_price = number_format((float)$this->get_grouped_price($this->product, 'sale'), 2, '.', '') ;
					elseif ($this->product->is_type( 'variable' )) {
						$default_attributes = $this->get_default_attributes( $this->product );
						if($default_attributes) {
							$variation_id = $this->find_matching_product_variation( $this->product, $default_attributes );
							if($variation_id) {
								$_variation_product = wc_get_product($variation_id);
								$sale_price = number_format( (float)$_variation_product->get_sale_price(), 2, '.', '');
							}else {
								$sale_price = number_format( (float)$this->product->get_variation_sale_price(), 2, '.', '');
							}
						}else {
							$sale_price = number_format( (float)$this->product->get_variation_sale_price(), 2, '.', '');
						}
					}
					elseif ($this->product->is_type( 'composite' )) {
						$_pr  = new WC_Product_Composite($this->product->get_id());
						if($this->wcml) {
							global $woocommerce_wpml;
							$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_sale_price(), wc_get_price_decimals()), $this->wcml_currency);

							//if WCML price is set manually
							$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
							if($_custom_prices['_sale_price'] > 0){
								$_price = $_custom_prices['_sale_price'];
							}

							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return $this->get_condition_price( $key, $_price );
						}else {
							$_price = $this->get_condition_price( $key, $_pr->get_sale_price() );
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return  wc_format_decimal( $_price, wc_get_price_decimals());
						}
					}
					else
						$sale_price = number_format((float)$this->product->get_sale_price(), 2, '.', '');


					$_pid = wad_get_product_id_to_use($this->product);
					$_product = wc_get_product($_pid);
					if( $_product->is_type( 'variation' ) ) {
						$_pid = $_product->get_parent_id();
					}
					foreach ($wad_discounts["product"] as $discount_id => $discount_obj) {
						$o_discount = get_post_meta($discount_id, 'o-discount', true);
						$pr_list_id = $o_discount['products-list'];
						$product_list = new WAD_Products_List($pr_list_id);
						$raw_args = get_post_meta($pr_list_id, "o-list", true);
						$args = $product_list->get_args($raw_args);
						$args['fields'] = 'ids';
						$products = get_posts( $args );
						if ($discount_obj->is_applicable($_pid) && in_array($_pid, $products)) {
							$to_widthdraw = 0;
							if (in_array($discount_obj->settings["action"], array("percentage-off-pprice", "percentage-off-osubtotal")))
								$to_widthdraw = floatval (floatval($sale_price)) * floatval ($discount_obj->settings["percentage-or-fixed-amount"]) / 100;
							//Fixed discount
							else if (in_array($discount_obj->settings["action"], array("fixed-amount-off-pprice", "fixed-amount-off-osubtotal"))) {
								$to_widthdraw = $discount_obj->settings["percentage-or-fixed-amount"];
							} else if ($discount_obj->settings["action"] == "fixed-pprice")
								$to_widthdraw = floatval(floatval($sale_price)) - floatval($discount_obj->settings["percentage-or-fixed-amount"]);
							$decimals = wc_get_price_decimals();
							$discount = round( $to_widthdraw, $decimals );
							$sale_price = floatval($sale_price) - $discount;
							if($this->wcml) {
								global $woocommerce_wpml;
								$_price         = apply_filters('wcml_raw_price_amount',wc_format_decimal( $sale_price, wc_get_price_decimals()), $this->wcml_currency);

								//if WCML price is set manually
								$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
								if($_custom_prices['_sale_price'] > 0){
									$_price = $_custom_prices['_sale_price'];
								}

								if ( $this->is_aelia_active() ){
									$_price = $this->get_converted_price( $_price );
								}
								return $this->get_condition_price( $key, $_price );
							}else {
								$_price = $this->get_condition_price( $key, $sale_price );
								if ( $this->is_aelia_active() ){
									$_price = $this->get_converted_price( $_price );
								}
								return  wc_format_decimal( $_price, wc_get_price_decimals());
							}
						}
					}
					$sale_price = wc_format_decimal( $sale_price, wc_get_price_decimals());
					if($sale_price > 0) {
						if($this->wcml) {
							global $woocommerce_wpml;
							$_price         = apply_filters('wcml_raw_price_amount',$sale_price, $this->wcml_currency);

							//if WCML price is set manually
							$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
							if($_custom_prices['_sale_price'] > 0){
								$_price = $_custom_prices['_sale_price'];
							}

							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return $this->get_condition_price( $key, $_price );
						}else {

							if ( $this->is_aelia_active() ){
								$sale_price = $this->get_converted_price( $sale_price );
							}
							return $this->get_condition_price( $key, $sale_price );
						}
					}

					return '';
				}
				break;

			case 'price_with_tax':
				if ($this->product->is_type( 'grouped' )) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount',wc_get_price_including_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'regular') ) ), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_regular_price'] > 0){
							$_price = $_custom_prices['_regular_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $this->get_condition_price( $key, $_price );
					}else {
						$_price = wc_get_price_including_tax( $this->product, array( 'price' => $this->get_condition_price( $key, $this->get_grouped_price($this->product, 'regular') ) ) );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price ;
					}
				}
				elseif ($this->product->is_type( 'composite' )) {
					$_pr  = new WC_Product_Composite($this->product->get_id());
					if($this->wcml) {
						global $woocommerce_wpml;

						if ( is_plugin_active( 'wpc-composite-products/wpc-composite-products.php' ) ) {
							$_price = apply_filters('wcml_raw_price_amount',wc_format_decimal( $_pr->get_composite_price(), wc_get_price_decimals()), $this->wcml_currency);
						}
						else {
							$_price = apply_filters('wcml_raw_price_amount',wc_format_decimal( $_pr->get_composite_regular_price(), wc_get_price_decimals()), $this->wcml_currency);
						}

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_regular_price'] > 0){
							$_price = $_custom_prices['_regular_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $this->get_condition_price( $key, $_price );
					}else {

						if ( is_plugin_active( 'wpc-composite-products/wpc-composite-products.php' ) ) {
							$_price = $this->get_condition_price( $key, $_pr->get_composite_price() );
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return  wc_format_decimal( $_price, wc_get_price_decimals());
						}
						else {
							$_price = $this->get_condition_price( $key, $_pr->get_composite_regular_price() );
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return  wc_format_decimal( $_price, wc_get_price_decimals());
						}
					}
				}
				if($this->wcml) {
					global $woocommerce_wpml;
					$_price         = apply_filters('wcml_raw_price_amount',wc_format_decimal( wc_get_price_including_tax( $this->product, array( 'price' => $this->product->get_regular_price() ) ), wc_get_price_decimals()), $this->wcml_currency);

					//if WCML price is set manually
					$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
					if($_custom_prices['_regular_price'] > 0){
						$_price = $_custom_prices['_regular_price'];
					}
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return $this->get_condition_price( $key, $_price );
				}else {
					$_price = wc_get_price_including_tax( $this->product, array( 'price' => $this->get_condition_price( $key, $this->product->get_regular_price() ) ) );
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return  wc_format_decimal( $_price, wc_get_price_decimals() );
				}
				break;

			case 'current_price_with_tax':
				if ($this->product->is_type( 'grouped' )) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_get_price_including_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'price') ) ), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_price'] > 0){
							$_price = $_custom_prices['_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $this->get_condition_price( $key, $_price );
					}else {
						$_price = wc_get_price_including_tax( $this->product, array( 'price' => $this->get_condition_price( $key, $this->get_grouped_price($this->product, 'price') ) ) );;
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}
				}
				elseif ($this->product->is_type( 'composite' )) {
					$_pr  = new WC_Product_Composite($this->product->get_id());
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_composite_price(), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_price'] > 0){
							$_price = $_custom_prices['_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $this->get_condition_price( $key, $_price );
					}else {
						$_price = $this->get_condition_price( $key, $_pr->get_composite_price() );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return  wc_format_decimal( $_price, wc_get_price_decimals());
					}
				}
				if($this->wcml) {
					global $woocommerce_wpml;
					$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( wc_get_price_including_tax( $this->product, array( 'price' => $this->product->get_price() ) ), wc_get_price_decimals()), $this->wcml_currency);

					//if WCML price is set manually
					$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
					if($_custom_prices['_price'] > 0){
						$_price = $_custom_prices['_price'];
					}
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return $this->get_condition_price( $key, $_price );
				}else {
					$_price = wc_get_price_including_tax( $this->product, array( 'price' => $this->get_condition_price( $key, $this->product->get_price() ) ) );
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return wc_format_decimal( $_price, wc_get_price_decimals());
				}
				break;

			case 'sale_price_with_tax':
				if ($this->product->is_type( 'grouped' )) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( wc_get_price_including_tax( $this->product, array( 'price' => $this->product->get_price() ) ), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_sale_price'] > 0){
							$_price = $_custom_prices['_sale_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = wc_get_price_including_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'sale') ) );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}
				}
				elseif ($this->product->is_type( 'composite' )) {
					$_pr  = new WC_Product_Composite($this->product->get_id());
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_sale_price(), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_sale_price'] > 0){
							$_price = $_custom_prices['_sale_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = $_pr->get_sale_price();
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return  wc_format_decimal( $_price, wc_get_price_decimals());
					}
				}
				$sale_price = $this->product->get_sale_price();
				if($sale_price > 0) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( wc_get_price_including_tax( $this->product, array( 'price' => $this->product->get_sale_price() ) ), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_sale_price'] > 0){
							$_price = $_custom_prices['_sale_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = wc_get_price_including_tax( $this->product, array( 'price' => $this->product->get_sale_price() ) );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return wc_format_decimal( $_price, wc_get_price_decimals());
					}
				}
				return '';
				break;

			case 'price_excl_tax':

				if ($this->product->is_type( 'grouped' )) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_get_price_excluding_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'regular') ) ), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_regular_price'] > 0){
							$_price = $_custom_prices['_regular_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = wc_get_price_excluding_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'regular') ) );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}
				}
				elseif ($this->product->is_type( 'composite' )) {
					$_pr  = new WC_Product_Composite($this->product->get_id());
					if($this->wcml) {
						global $woocommerce_wpml;

						if ( is_plugin_active( 'wpc-composite-products/wpc-composite-products.php' ) ) {
							$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_composite_price(), wc_get_price_decimals()), $this->wcml_currency);
						}
						else {
							$_price = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_composite_regular_price(), wc_get_price_decimals()), $this->wcml_currency);
						}
						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );

						if($_custom_prices['_regular_price'] > 0){
							$_price = $_custom_prices['_regular_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						if ( is_plugin_active( 'wpc-composite-products/wpc-composite-products.php' ) ) {
							$_price = $_pr->get_composite_price();
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return  wc_format_decimal( $_price, wc_get_price_decimals());
						}
						else {
							$_price = $_pr->get_composite_regular_price();
							if ( $this->is_aelia_active() ){
								$_price = $this->get_converted_price( $_price );
							}
							return  wc_format_decimal( $_price, wc_get_price_decimals());
						}
					}
				}
				if($this->wcml) {
					global $woocommerce_wpml;
					$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( wc_get_price_excluding_tax( $this->product, array( 'price' => $this->product->get_regular_price() ) ), wc_get_price_decimals()), $this->wcml_currency);

					//if WCML price is set manually
					$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
					if($_custom_prices['_regular_price'] > 0){
						$_price = $_custom_prices['_regular_price'];
					}
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return $_price;
				}else {
					$_price = wc_get_price_excluding_tax( $this->product, array( 'price' => $this->product->get_regular_price() ) );
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return  wc_format_decimal( $_price, wc_get_price_decimals());
				}
				break;

			case 'current_price_excl_tax':
				if ($this->product->is_type( 'grouped' )) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_get_price_excluding_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'price') ) ), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_price'] > 0){
							$_price = $_custom_prices['_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = wc_get_price_excluding_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'price') ) );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}
				}
				elseif ($this->product->is_type( 'composite' )) {
					$_pr  = new WC_Product_Composite($this->product->get_id());
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_composite_price(), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_price'] > 0){
							$_price = $_custom_prices['_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = $_pr->get_composite_price();
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return  wc_format_decimal( $_price, wc_get_price_decimals());
					}
				}
				if($this->wcml) {
					global $woocommerce_wpml;
					$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( wc_get_price_excluding_tax( $this->product, array( 'price' => $this->product->get_price() ) ), wc_get_price_decimals()), $this->wcml_currency);

					//if WCML price is set manually
					$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
					if($_custom_prices['_price'] > 0){
						$_price = $_custom_prices['_price'];
					}
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return $_price;
				}else {
					$_price = wc_get_price_excluding_tax( $this->product, array( 'price' => $this->product->get_price() ) );
					if ( $this->is_aelia_active() ){
						$_price = $this->get_converted_price( $_price );
					}
					return wc_format_decimal( $_price, wc_get_price_decimals());
				}
				break;

			case 'sale_price_excl_tax':
				if ($this->product->is_type( 'grouped' )) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_get_price_excluding_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'sale') ) ), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_sale_price'] > 0){
							$_price = $_custom_prices['_sale_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = wc_get_price_excluding_tax( $this->product, array( 'price' => $this->get_grouped_price($this->product, 'sale') ) );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}
				}
				elseif ($this->product->is_type( 'composite' )) {
					$_pr  = new WC_Product_Composite($this->product->get_id());
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $_pr->get_sale_price(), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_sale_price'] > 0){
							$_price = $_custom_prices['_sale_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = $_pr->get_sale_price();
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return  wc_format_decimal( $_price, wc_get_price_decimals());
					}
				}
				$sale_price = $this->product->get_sale_price();
				if($sale_price > 0) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( wc_get_price_excluding_tax( $this->product, array( 'price' => $this->product->get_sale_price() ) ), wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_sale_price'] > 0){
							$_price = $_custom_prices['_sale_price'];
						}
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return $_price;
					}else {
						$_price = wc_get_price_excluding_tax( $this->product, array( 'price' => $this->product->get_sale_price() ) );
						if ( $this->is_aelia_active() ){
							$_price = $this->get_converted_price( $_price );
						}
						return wc_format_decimal( $_price, wc_get_price_decimals());
					}
				}
				return '';

			case 'price_db':
				if($this->wcml) {
					global $woocommerce_wpml;
					$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( get_post_meta( $this->product->get_id(), '_regular_price', true), wc_get_price_decimals()), $this->wcml_currency);

					//if WCML price is set manually
					$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
					if($_custom_prices['_regular_price'] > 0){
						$_price = $_custom_prices['_regular_price'];
					}
					return $_price;
				}else {
					$meta_key = '_regular_price';
					if ( $this->product->is_type( 'variable' ) || $this->product->is_type( 'grouped' ) ){
						$meta_key = '_price';
					}
					return  wc_format_decimal( get_post_meta( $this->product->get_id(), $meta_key, true), wc_get_price_decimals());
				}
				break;

			case 'current_price_db':
				if($this->wcml) {
					global $woocommerce_wpml;
					$_price = apply_filters('wcml_raw_price_amount', wc_format_decimal( get_post_meta( $this->product->get_id(), '_price', true), wc_get_price_decimals()), $this->wcml_currency);

					//if WCML price is set manually
					$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
					if($_custom_prices['_price'] > 0){
						$_price = $_custom_prices['_price'];
					}
					return $_price;
				}else {
					return  wc_format_decimal( get_post_meta( $this->product->get_id(), '_price', true), wc_get_price_decimals());
				}
				break;

			case 'sale_price_db':
				$sale_price = get_post_meta( $this->product->get_id(), '_sale_price', true);
				if( (int) $sale_price > 0) {
					if($this->wcml) {
						global $woocommerce_wpml;
						$_price         = apply_filters('wcml_raw_price_amount', wc_format_decimal( $sale_price, wc_get_price_decimals()), $this->wcml_currency);

						//if WCML price is set manually
						$_custom_prices = $woocommerce_wpml->get_multi_currency()->custom_prices->get_product_custom_prices( $this->product->get_id(), $this->wcml_currency );
						if($_custom_prices['_sale_price'] > 0){
							$_price = $_custom_prices['_sale_price'];
						}
						return $_price;
					}else {
						return wc_format_decimal( $sale_price, wc_get_price_decimals());
					}
				}
				return '';
				break;

			case 'description':
				if(($this->is_children())):
					$description = $this->product->get_description();
					if(empty($description)) {
						$_product = wc_get_product( $this->product->get_parent_id() );
						if ( is_object( $_product ) ) {

							return $this->remove_short_codes($_product->get_description());
						}
					}else {

						return $this->remove_short_codes($description);
					}
				else:
					// $des = preg_replace('/(?:\s\s+|\n|\t)/', ' ',$this->product->get_description());
					return $this->remove_short_codes($this->product->get_description());
				endif;

				break;

			case 'parent_desc':

			    if( $this->is_children() ) {
			        $parent_product = wc_get_product( $this->product->get_parent_id() );

                    if ( is_object( $parent_product ) ) {

                        return $this->remove_short_codes( $parent_product->get_description() );
                    }
                }

			    return $this->product->get_description();
				break;

			case 'short_description':
				if(($this->is_children())):
					$short_description = $this->product->get_short_description();
					if(empty($short_description)) {
						$_product = wc_get_product( $this->product->get_parent_id() );
						if ( is_object( $_product ) ) {

							return  $this->remove_short_codes($_product->get_short_description());
						}
					}else {

						return $this->remove_short_codes($short_description);
					}
				else:
					return $this->remove_short_codes($this->product->get_short_description()) ;
				endif;
				break;

			case 'yoast_meta_desc':
				return $this->get_yoast_meta_description(); break;

			case 'product_cats':
				return $this->get_product_cats(); break;

			case 'product_cats_path':
				return $this->get_product_cats_with_seperator(); break;

			case 'product_cats_path_pipe':
				return $this->get_product_cats_with_seperator('', ' | ', ''); break;

			case 'yoast_primary_cats_path':
				return $this->get_yoast_product_cats_with_seperator(); break;

			case 'yoast_primary_cats_pipe':
				return $this->get_yoast_product_cats_with_seperator('', ' | ', ''); break;

			case 'yoast_primary_cats_comma':
				return $this->get_yoast_product_cats_with_seperator('', ', ', ''); break;

			case 'product_subcategory':
				return $this->get_product_subcategory(); break;

			case 'product_tags':
				return $this->get_product_tags(); break;

			case 'yoast_primary_cat':
				return $this->get_yoast_primary_cat(); break;

			case 'spartoo_product_cats':
				return $this->get_spartoo_product_cats(); break;

			case 'sooqr_cats':
				return $this->get_product_cats_for_sooqr(); break;

			case 'perfect_brand':
				$brand = get_products_brands($this->product->get_id());
				return $this->product->get_id(); break;

			case 'link':

				if($this->analytics_params) {
					if ( ! empty( $this->analytics_params['utm_source'] ) &&
					     ! empty( $this->analytics_params['utm_medium'] ) &&
					     ! empty( $this->analytics_params['utm_campaign'] )
					) {
						if($rule === 'decode_url') {
							return add_query_arg( array_filter( $this->analytics_params ), urldecode($this->product->get_permalink())); break;
						}
						return $this->safeCharEncodeURL(add_query_arg( array_filter( $this->analytics_params ), urldecode($this->product->get_permalink()) )); break;
					}
					if($rule === 'decode_url') {
						return urldecode($this->product->get_permalink()); break;
					}
					return $this->safeCharEncodeURL(urldecode($this->product->get_permalink())); break;
				}
				if($rule === 'decode_url') {
					return urldecode($this->product->get_permalink()); break;
				}

				return $this->safeCharEncodeURL(urldecode($this->product->get_permalink())); break;

			case 'parent_url':
				$_pr = $this->product;
				if ( 'WC_Product_Variation' == get_class($this->product) ) {
					$_pr = wc_get_product($this->product->get_parent_id());
				}
				if($this->analytics_params) {
					if ( ! empty( $this->analytics_params['utm_source'] ) &&
					     ! empty( $this->analytics_params['utm_medium'] ) &&
					     ! empty( $this->analytics_params['utm_campaign'] )
					) {
						if($rule === 'decode_url') {
							return add_query_arg( array_filter( $this->analytics_params ), urldecode($_pr->get_permalink())); break;
						}
						return $this->safeCharEncodeURL(add_query_arg( array_filter( $this->analytics_params ), urldecode($_pr->get_permalink()) )); break;
					}
					if($rule === 'decode_url') {
						return urldecode($_pr->get_permalink()); break;
					}
					return $this->safeCharEncodeURL(urldecode($_pr->get_permalink())); break;
				}
				if($rule === 'decode_url') {
					return urldecode($_pr->get_permalink()); break;
				}
				return $this->safeCharEncodeURL(urldecode($_pr->get_permalink())); break;

			case 'condition':
				return $this->get_condition(); break;

			case 'item_group_id':
				return $this->get_item_group_id(); break;

			case 'availability':
				return $this->get_availability(); break;

			case 'availability_zero_three':
			    $if_available = $this->get_availability();
			    if ( 'out_of_stock' == $if_available ) {
			        return '3';
                }
			    return '0'; break;

			case 'availability_underscore':
				return $this->get_availability_underscore(); break;

			case 'availability_backorder_instock':
				return $this->get_availability_backorder_instock(); break;

			case 'availability_backorder':
				return $this->get_availability_backorder(); break;

			case 'quantity':
				return $this->product->get_stock_quantity(); break;

			case 'weight':
				return $this->product->get_weight(); break;

			case 'width':
				return $this->product->get_width(); break;

			case 'height':
				return $this->product->get_height(); break;

			case 'length':
				return $this->product->get_length(); break;

			case 'shipping_class':
				return $this->product->get_shipping_class(); break;

			case 'shipping_cost':
				return $this->get_shipping_cost(); break;

			case 'type':
				return $this->product->get_type(); break;

			case 'in_stock':
				return $this->get_stock(); break;

			case 'rating_average':
				return $this->product->get_average_rating(); break;

			case 'rating_total':
				return $this->product->get_rating_count(); break;

			case 'sale_price_dates_from':
				return date( get_option( 'date_format' ), $this->product->get_date_on_sale_from() ); break;

			case 'sale_price_dates_to':
				return date( get_option( 'date_format' ), $this->product->get_date_on_sale_to() ); break;

			case 'sale_price_effective_date':
				$sale_price_dates_to        = ( $date = get_post_meta( $this->product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
				$sale_price_dates_from      = ( $date = get_post_meta( $this->product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';

				if ( ! empty( $sale_price_dates_to ) && ! empty( $sale_price_dates_from ) ) {
					$from   = date( "c", strtotime( $sale_price_dates_from ) );
					$to     = date( "c", strtotime( $sale_price_dates_to ) );


					return $from . '/' . $to;
				}else {
					return '';
				}

			case 'identifier_exists':
				return $this->calculate_identifier_exists($this->data);

			case 'post_publish_date':
				$product_id = '';
				if ( $this->product->is_type('variation') ) {
					$product_id = $this->product->get_parent_id();
				}
				else {
					$product_id = $this->product->get_id();
				}
				return get_the_date( '', $product_id ) . 'T' . get_the_time( 'g:i:s', $product_id ) . 'Z';

			case 'post_modified_date':
				$product_id = '';
				if ( $this->product->is_type('variation') ) {
					$product_id = $this->product->get_parent_id();
				}
				else {
					$product_id = $this->product->get_id();
				}
				return get_the_modified_date( '', $product_id ) . 'T' . get_the_modified_time( 'g:i:s', $product_id ) . 'Z';

			case 'current_page':
				$product_id = '';
				if ( $this->product->is_type('variation') ) {
					$product_id = $this->product->get_parent_id();
				}
				else {
					$product_id = $this->product->get_id();
				}
				return get_permalink( $product_id );

			case 'author_name':
				$author_id = '';
				if ( $this->product->is_type('variation') ) {
					$author_id = get_post_field( 'post_author', $this->product->get_parent_id() );
				}
				else {
					$author_id = get_post_field( 'post_author', $this->product->get_id() );
				}
				return get_the_author_meta( 'display_name', $author_id );

			case 'author_url':
				$author_id = '';
				if ( $this->product->is_type('variation') ) {
					$author_id = get_post_field( 'post_author', $this->product->get_parent_id() );
				}
				else {
					$author_id = get_post_field( 'post_author', $this->product->get_id() );
				}
				return get_author_posts_url( $author_id );

			default: return ''; break;
		}
	}


	/**
	 * @desc check if aelia is active.
	 *
	 * @return bool
	 */
	protected function is_aelia_active(){
		$active_plugings         = get_option( 'active_plugins' );
		$aelia_plugin            = 'woocommerce-aelia-currencyswitcher/woocommerce-aelia-currencyswitcher.php';
		$aelia_foundation_plugin = 'wc-aelia-foundation-classes/wc-aelia-foundation-classes.php';

		return in_array( $aelia_plugin, $active_plugings ) && in_array( $aelia_foundation_plugin, $active_plugings );
	}

	/**
	 * @desc Gets price converted by Aelia
	 *
	 * @param $price
	 * @return mixed|void
	 */
	protected function get_converted_price( $price ) {
		$from_currency = get_woocommerce_currency();
		$to_currency   = $this->aelia_currency;

		return apply_filters( 'wc_aelia_cs_convert', $price, $from_currency, $to_currency );
	}


	/**
	 * @desc retrieves image metadata
	 *
	 * @return array|false
	 */
	protected function get_image_meta(){
		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			$_pr = wc_get_product($this->product->get_parent_id());
			return wp_get_attachment_metadata( $_pr->get_image_id() );
		}else {
			return wp_get_attachment_metadata( $this->product->get_image_id() );
		}
	}


	/**
	 * Set a Image attribute.
	 *
	 * @since    1.0.0
	 */
	protected function set_image_att( $key ) {

		switch ( $key ) {
			case 'main_image':
				if ( 'WC_Product_Variation' == get_class($this->product) ) {
					$_pr = wc_get_product($this->product->get_parent_id());
					return wp_get_attachment_url(  $_pr->get_image_id() ); break;
				}else {
					return wp_get_attachment_url(  $this->product->get_image_id() ); break;
				}
				return '';

			case 'image_height':
				$image_src = $this->get_image_meta();

				return $image_src[ 'height' ];

			case 'image_width':
				$image_src = $this->get_image_meta();

				return $image_src[ 'width' ];

			case 'encoding_format':
				$image_src = $this->get_image_meta();

				return $image_src[ 'sizes' ][ 'woocommerce_thumbnail' ][ 'mime-type' ];

			case 'image_size':
				if ( 'WC_Product_Variation' == get_class($this->product) ) {
					$_pr = wc_get_product($this->product->get_parent_id());
					$image_size = filesize(get_attached_file(  $_pr->get_image_id() ) );

				}else {
					$image_size = filesize(get_attached_file(  $this->product->get_image_id() ) );
				}

				return $image_size;

			case 'keywords':
				$image_src = $this->get_image_meta();
				return $image_src[ 'image_meta' ][ 'keywords' ];

			case 'thumbnail_image':
				if ( 'WC_Product_Variation' == get_class($this->product) ) {
					return get_the_post_thumbnail_url( $this->product->get_parent_id() );

				}else {
					return get_the_post_thumbnail_url(  $this->product->get_id() );
				}

			case 'featured_image':
				if( wp_get_attachment_url(  $this->product->get_image_id() ) ){
					return wp_get_attachment_url(  $this->product->get_image_id() ); break;
				}
				return ''; break;
			case 'all_image':
				return $this->get_all_image(); break;
			case 'all_image_pipe':
				return $this->get_all_image('|'); break;
			default: return $this->get_additional_image( $key ); break;
		}
	}


	/**
	 * get all product images with separators
	 *
	 * @param string $sep
	 * @return string
	 */
	private function get_all_image( $sep = ',' ) {
		$attachment_ids = $this->product->get_gallery_image_ids();
		$all_images = [];
		foreach ($attachment_ids as $key => $val){
			$all_images[] = wp_get_attachment_url($val);
		}
		return implode( $sep , $all_images );
	}


	/**
	 * Set a Product attribute.
	 *
	 * @since    1.0.0
	 */
	protected function set_product_att( $key ) {
		if ( 'WC_Product_Variation' != get_class($this->product) ) {
			return;
		}
		$key = str_replace( 'bwf_attr_pa_', '', $key);
		$value = $this->product->get_attribute( $key );

		if ( ! empty( $value ) ) {
			$value = trim( $value );
		}
		return $value;
	}


	/**
	 * Set a Glami attribute.
	 *
	 * @since    1.0.0
	 */
	protected function set_glami_att( $key ) {
		if ( 'WC_Product_Variation' != get_class($this->product) ) {
			return;
		}
		$key = str_replace( 'param_', '', $key);
		$value = $this->product->get_attribute( $key );

		if ( ! empty( $value ) ) {
			$value = trim( $value );
		}
		return $value;
	}

	/**
	 * Set a Product Dynamic attribute.
	 *
	 * @since    1.0.0
	 */
	protected function set_product_dynamic_att( $key ) {

		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			$attr_name = $this->get_product_dynamic_tags($this->product->get_parent_id(), $key);
		} else{

			$attr_name = $this->get_product_dynamic_tags($this->product->get_id(), $key);
		}
		if($attr_name){
			return $attr_name;
		}
		return '';
	}

	/**
	 * Set a Product Custom attribute.
	 *
	 * @since    1.0.0
	 */
	protected function set_product_custom_att( $key ) {
		$new_key = str_replace('custom_attributes_', '', $key);
		$meta_value = '';

		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			if($new_key === '_wpfm_product_brand') {
				$meta_value = get_post_meta($this->product->get_parent_id(), $new_key, true);
			}else {
				$meta_value = get_post_meta($this->product->get_id(), $new_key, true);
				// need to check if these attributes value is assigned to the mother product
				if(!$meta_value) {
					$list = $this->get_product_attributes($this->product->get_parent_id());
					if(array_key_exists($new_key, $list)) {
						$meta_value = str_replace('|', ',', $list[$new_key]);
					}
				}
			}
		} else{
			$meta_value = get_post_meta($this->product->get_id(), $new_key, true);
			if(!$meta_value) {
				$list = $this->get_product_attributes($this->product->get_id());
				if(array_key_exists($new_key, $list)) {
					$meta_value = str_replace('|', ',', $list[$new_key]);
				}
			}

		}

		if ( is_plugin_active( 'woo-discount-rules/woo-discount-rules.php' ) ) {

			if ( $new_key === 'woo_discount_rules_price' ) {
				$this->discount_manage = new ManageDiscount();
				$price                 = $this->discount_manage->calculateInitialAndDiscountedPrice( $this->product, 1 );
				$meta_value =  $price[ 'discounted_price' ];
			}
			elseif ( $new_key === 'woo_discount_rules_expire_date' ) {
				$rules = DBTable::getRules();
				foreach ( $rules as $rule ) {
					if ( $rule->discount_type === 'wdr_simple_discount' ) {
						$format = "Y-m-d H:i";
						$end_date = $rule->date_to;
						$end_date = date($format, (int)$end_date);
						$meta_value = $end_date;
						break;
					}
				}
			}
		}

		return apply_filters("product_custom_att_value_{$new_key}", $meta_value, $new_key, $this->product);

	}


	/**
	 * get all the product attributes
	 * @param $id
	 * @return array
	 */
	protected function get_product_attributes($id) {

		global $wpdb;
		$list = [];
		$sql = "SELECT meta_key as name, meta_value as value FROM {$wpdb->prefix}postmeta  as postmeta
                            INNER JOIN {$wpdb->prefix}posts AS posts
                            ON postmeta.post_id = posts.id
                            WHERE posts.post_type LIKE '%product%'
                            AND postmeta.meta_key = '_product_attributes'
                            AND postmeta.post_id = %d";
		$data = $wpdb->get_results($wpdb->prepare($sql, $id));
		if(count($data)) {
			foreach ($data as $key => $value) {
				$value_display = str_replace("_", " ",$value->name);
				if (!preg_match("/_product_attributes/i", $value->name)) {
					$list[$value->name] = ucfirst($value_display);
				}else {
					$product_attributes = unserialize($value->value);
					if (!empty($product_attributes)) {
						foreach ($product_attributes as $k => $arr_value) {
							$value_display = str_replace("_", " ", $arr_value['value']);
							$list[$k] = ucfirst($value_display);
						}
					}
				}
			}
		}
		return $list;
	}


	public function price_array($price){
		$del = array('<span class="amount">', '</span>','<del>','<ins>');
		$price = str_replace($del, '', $price);
		$price = str_replace('</del>', '|', $price);
		$price = str_replace('</ins>', '|', $price);
		$price_arr = explode('|', $price);
		$price_arr = array_filter($price_arr);
		return $price_arr;
	}

	/**
	 * Set Product Category Map
	 *
	 * @since    3.0
	 */
	protected function set_cat_mapper_att( $key ) {

		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			$cat_lists = get_the_terms( $this->product->get_parent_id(), 'product_cat' );
		} else{
			$cat_lists = get_the_terms( $this->product->get_id(), 'product_cat' );
		}
		$wpfm_category_map = get_option('rex-wpfm-category-mapping');

		if($wpfm_category_map) {
			$map = $wpfm_category_map[$key];
			$map_config = $map['map-config'];

			if($cat_lists) {
				foreach ( $cat_lists as $key=>$term ) {
					$map_key = array_search($term->term_id, array_column($map_config, 'map-key'));

					if( $map_key == 0 || $map_key ) {
						$map_array = $map_config[$map_key];
						$map_value = $map_array['map-value'];
						if(!empty($map_value)){
							preg_match("~^(\d+)~", $map_value, $m);
							if(count($m) > 1) {
								if($m[1]) {
									return utf8_decode(urldecode($m[1]));
								} else {
									return $map_value;
								}
							}else {
								return $map_value;

							}
						}
					}
				}
			}
		}
		return '';
	}


	/**
	 * Get yoast seo title
	 * @return string
	 */
	public function get_yoast_seo_title() {
		$title = '';
		if ($this->product->get_type() == 'variation') {
			$product_id = $this->product->get_parent_id();
		}else {
			$product_id = $this->product->get_id();
		}
		if ( function_exists( 'wpseo_replace_vars' ) ) {
			$wpseo_title = get_post_meta($product_id, '_yoast_wpseo_title', true);
			if($wpseo_title) {
				$product_title_pattern = $wpseo_title;
			}else {
				$wpseo_titles = get_option('wpseo_titles');
				$product_title_pattern = $wpseo_titles['title-product'];
			}
			$title = wpseo_replace_vars($product_title_pattern, get_post($product_id));
		}
		if ( ! empty( $title ) ) {
			return $title;
		}
		else {
			return $this->product->get_title();
		}
	}


	/**
	 * Get yoast meta descriptions
	 * @return string
	 */
	public function get_yoast_meta_description() {
		$description = '';
		if ($this->product->get_type() == 'variation') {
			$product_id = $this->product->get_parent_id();
		}else {
			$product_id = $this->product->get_id();
		}
		if ( function_exists( 'wpseo_replace_vars' ) ) {
			$wpseo_meta_description = get_post_meta($product_id, '_yoast_wpseo_metadesc', true);
			if($wpseo_meta_description) {
				$product_meta_desc_pattern = $wpseo_meta_description;
			}else {
				$wpseo_titles = get_option('wpseo_titles');
				$product_meta_desc_pattern = $wpseo_titles['metadesc-product'];
			}
			$description = wpseo_replace_vars($product_meta_desc_pattern, get_post($product_id));
		}

		if ( ! empty( $description ) ) {
			return $description;
		}
		else {
			return $this->product->get_description();
		}
	}


	/**
	 * Get additional image url by key.
	 *
	 * @since    1.0.0
	 */
	protected function get_additional_image( $key ) {

		if ( empty( $this->additional_images ) ) {
			$this->set_additional_images();
		}


		if ( array_key_exists( $key, $this->additional_images ) ) {
			return $this->additional_images[$key];
		}

		return '';

	}

	/**
	 * Retrieve a product's categories as a list with specified format.
	 *
	 * @param string $before Optional. Before list.
	 * @param string $sep Optional. Separate items using this.
	 * @param string $after Optional. After list.
	 * @return string|false
	 */
	protected function get_product_cats( $before = '', $sep = ', ', $after = '' ) {
		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			return $this->get_the_term_list( $this->product->get_parent_id(), 'product_cat', $before, $sep, $after );
		}else {
			return $this->get_the_term_list( $this->product->get_id(), 'product_cat', $before, $sep, $after );
		}

	}


	/**
	 * @param string $before
	 * @param string $sep
	 * @param string $after
	 * @return array
	 */
	protected function get_spartoo_product_cats( $before = '', $sep = ', ', $after = '' ) {
		$term_array = array();
		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			$terms = get_the_terms( $this->product->get_parent_id(), 'product_cat' );
		}else {
			$terms = get_the_terms( $this->product->get_id(), 'product_cat' );
		}

		$count = 0;
		if($terms) $count = count($terms);
		if($count > 1) {
			foreach ($terms as $term) {
				$term_array[] = $term->name;
			}
		}
		return $term_array;
	}


	/**
	 * Retrieve a product's categories as a list with specified format.
	 *
	 * @param string $before Optional. Before list.
	 * @param string $sep Optional. Separate items using this.
	 * @param string $after Optional. After list.
	 * @return string|false
	 */
	protected function get_product_cats_with_seperator( $before = '', $sep = ' > ', $after = '' ) {

		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			return $this->get_the_term_list_with_path( $this->product->get_parent_id(), 'product_cat', $before, $sep, $after );
		}else {
			return $this->get_the_term_list_with_path( $this->product->get_id(), 'product_cat', $before, $sep, $after );
		}
	}


	/**
	 * Retrieve a product's categories as a list with specified format.
	 *
	 * @param string $before Optional. Before list.
	 * @param string $sep Optional. Separate items using this.
	 * @param string $after Optional. After list.
	 * @return string
	 */
	protected function get_yoast_product_cats_with_seperator( $before = '', $sep = ' > ', $after = '' ) {
		$pr_id = $this->product->get_id();
		if($this->product->is_type('variation')) {
			$pr_id = $this->product->get_parent_id();
		}
		$primary_cat_id=get_post_meta($pr_id,'_yoast_wpseo_primary_product_cat',true);
		$term_name = [];
		if($primary_cat_id){
			$product_cat = get_term($primary_cat_id, 'product_cat');
			if(isset($product_cat->name)) {
				$term_name[] = $product_cat->name;
				$term_name_arr = $this->get_cat_names_array($pr_id, 'product_cat',$primary_cat_id, $term_name);
				if(is_array($term_name_arr)) {
					return implode($sep, $term_name_arr);
				}
				return $this->get_product_cats('', ' > ', '');
			}
		}
		return $this->get_product_cats('', ' > ', '');
	}


	/**
	 * Retrieve a product's sub categories as a list with specified format.
	 *
	 * @param string $sep Optional. Separate items using this.
	 * @return string|false
	 */
	protected function get_product_subcategory( $sep = ' > ') {
		$parent = 0;
		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			$terms = get_the_terms( $this->product->get_parent_id(), 'product_cat' );
			if ( empty( $terms ) || is_wp_error( $terms ) ){
				return '';
			}
			$term_names = array();
			foreach($terms as $term) {
				if($term->parent) {
					$term_names[] = $term->name;
				}
			}
			ksort($term_names);
			return '' . join( $sep, $term_names ) . '';
		}else {
			$terms = get_the_terms( $this->product->get_id(), 'product_cat' );
			if ( empty( $terms ) || is_wp_error( $terms ) ){
				return '';
			}
			$term_names = array();
			foreach($terms as $term) {
				if($term->parent) {
					$term_names[] = $term->name;
				}
			}
			ksort($term_names);
			return '' . join( $sep, $term_names ) . '';
		}

	}

	/**
	 * Retrieve a product's tags as a list with specified format.
	 *
	 *
	 * @param string $before Optional. Before list.
	 * @param string $sep Optional. Separate items using this.
	 * @param string $after Optional. After list.
	 * @return string|false
	 */
	protected function get_product_tags( $before = '', $sep = ', ', $after = '' ) {

		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			return $this->get_the_term_list( $this->product->get_parent_id(), 'product_tag', $before, $sep, $after );
		}else {
			return $this->get_the_term_list( $this->product->get_id(), 'product_tag', $before, $sep, $after );
		}
	}


	/**
	 * get yoast primary category
	 * @return string
	 */
	public function get_yoast_primary_cat()
	{
		$pr_id = $this->product->get_id();
		if ( $this->product->is_type( 'variation' ) ) {
			$pr_id = $this->product->get_parent_id();
		}
		$primary_cat_id = get_post_meta( $pr_id, '_yoast_wpseo_primary_product_cat', true );

		if ( $primary_cat_id ) {
			$product_cat = get_term( $primary_cat_id, 'product_cat' );
			if ( isset( $product_cat->name ) )
				return $product_cat->name;
		}
		return $this->get_product_cats();
	}


	/**
	 * @param string $before
	 * @param string $sep
	 * @param string $after
	 * @return array
	 */
	public function get_product_cats_for_sooqr($before = '', $sep = ' > ', $after = '') {
		$categories = [];
		if ( 'WC_Product_Variation' == get_class($this->product) ) {
			$term_list = wp_get_post_terms($this->product->get_parent_id(), 'product_cat');
			foreach ($term_list as $term) {
				if($term->parent) {
					$categories['subcategories'][] = $term->name;
				}else {
					$categories['categories'][] = $term->name;
				}
			}
			return $categories;
		}else {
			$term_list = wp_get_post_terms($this->product->get_id(), 'product_cat');
			foreach ($term_list as $term) {
				if($term->parent) {
					$categories['subcategories'][] = $term->name;
				}else {
					$categories['categories'][] = $term->name;
				}
			}
			return $categories;
		}
	}



	/**
	 * Retrieve a product's dynamic attributes as a list with specified format.
	 *
	 *
	 * @param string $before Optional. Before list.
	 * @param string $sep Optional. Separate items using this.
	 * @param string $after Optional. After list.
	 * @return string|false
	 * @return string|false
	 */
	protected function get_product_dynamic_tags( $id, $key, $before = '', $sep = ', ', $after = '' ) {
		return $this->get_the_term_list($id, $key, $before, $sep, $after );
	}

	/**
	 * Retrieve a product's terms as a list with specified format.
	 *
	 *
	 * @param int $id Product ID.
	 * @param string $taxonomy Taxonomy name.
	 * @param string $before Optional. Before list.
	 * @param string $sep Optional. Separate items using this.
	 * @param string $after Optional. After list.
	 * @return string|false
	 */
	protected function get_the_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {
		$terms = wp_get_post_terms( $id, $taxonomy , array( 'hide_empty' => false, 'orderby' => 'term_id' ));

		if ( empty( $terms ) || is_wp_error( $terms ) ){
			return '';
		}
		$output = array();
		$child_terms = array();
		$parent_terms = array();

		foreach ($terms as $term) {
			if($term->parent) {
				$child_terms = $this->get_cat_names_array($id, $taxonomy, $term->parent, $parent_terms);
			}else {
				$parent_terms[] = $term->name;
			}

		}
		$output = array_merge( $parent_terms, $child_terms);

		return implode(', ', $output);
	}


	/**
	 *
	 * @param $id
	 * @param $taxonomy
	 * @param string $before
	 * @param string $sep
	 * @param string $after
	 * @return string
	 */
	protected function get_the_term_list_with_path( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {
		$terms = wp_get_post_terms( $id, $taxonomy , array( 'hide_empty' => false, 'orderby' => 'term_id' ));

		if ( empty( $terms ) || is_wp_error( $terms ) ){
			return '';
		}

		$terms_id = array();
		foreach ($terms as $term) {
			$terms_id[] = $term->term_id;
		}

		$output   = array();

		foreach ($terms as $term) {
			$term_names = [];
			$term->name = htmlspecialchars_decode($term->name);
			$term_names[] = $term->name;


			$term_name_arr = $this->get_cat_names_array($id, $taxonomy, $term->term_id, $term_names);

			if ( !empty( array_diff( $term_name_arr, $term_names) ) ) {

				foreach ( $term_name_arr as $t_name ) {
					$temp = array();
					$temp[] = $term->name;
					$temp[] = $t_name;
					$output[] = implode($sep, $temp);
				}
			}
			else if ( $term->parent == 0 ) {
				if( is_array($term_name_arr) ) {
					$output[] = implode($sep, $term_name_arr);
				}
			}
			else if ( !in_array( $term->parent, $terms_id) ) {
				if( is_array($term_name_arr) ) {
					$output[] = implode($sep, $term_name_arr);
				}
			}
		}
		return implode(', ', $output);
	}

	protected function get_cat_names_array($id, $taxonomy, $parent, $term_name_array) {
		$terms = wp_get_post_terms( $id, $taxonomy , array( 'hide_empty' => false, 'parent' => $parent,'orderby' => 'term_id' ));

		if ( empty( $terms ) || is_wp_error( $terms ) ){
			return $term_name_array;
		}
		$term_arr = array();
		foreach ( $terms as $term ) {
			$term_name_array = array();
			$term_name_array[] = $term->name;
			$term_name_array = $this->get_cat_names_array($id, $taxonomy, $term->term_id, $term_name_array);
			$term_arr[] = $term_name_array[0];
		}
		return $term_arr;
	}


	/**
	 * @param $id
	 * @param $taxonomy
	 * @param string $before
	 * @param string $sep
	 * @param string $after
	 * @since 5.35
	 */
	protected function get_the_term_list_with_separator( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {

	}


	/**
	 * get product default attributes
	 *
	 * @param $product
	 * @return mixed
	 */
	protected function get_default_attributes($product) {
		if( method_exists( $product, 'get_default_attributes' ) ) {
			return $product->get_default_attributes();
		} else {
			return $product->get_variation_default_attributes();
		}
	}


	/**
	 * Get matching variation
	 *
	 * @param $product
	 * @param $attributes
	 * @return int Matching variation ID or 0.
	 * @throws Exception
	 */
	protected function find_matching_product_variation( $product, $attributes ) {
		foreach( $attributes as $key => $value ) {
			if( strpos( $key, 'attribute_' ) === 0 ) {
				continue;
			}
			unset( $attributes[ $key ] );
			$attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
		}
		if( class_exists('WC_Data_Store') ) {
			$data_store = WC_Data_Store::load( 'product' );
			return $data_store->find_matching_product_variation( $product, $attributes );
		} else {
			return $product->get_matching_variation( $attributes );
		}
	}


	/**
	 * Set additional images url.
	 *
	 * @since    1.0.0
	 */
	protected function set_additional_images() {

		$_product = $this->product;
		if($this->product->is_type('variation')) {
			$_product = wc_get_product($this->product->get_parent_id());
		}

		$img_ids = $_product->get_gallery_image_ids();

		$images = array();
		if ( ! empty( $img_ids ) ) {
			foreach ($img_ids as $key => $img_id) {
				$img_key = 'image_' . ($key+1);
				$images[$img_key] = wp_get_attachment_url($img_id);
			}
			// set images to the property
			$this->additional_images = $images;
		}

	}

	/**
	 * Helper to check if a attribute is a Primary Attribute.
	 *
	 * @since    1.0.0
	 */
	protected function is_primary_attr( $key ) {
		return array_key_exists( $key, $this->product_meta_keys['Primary Attributes'] );
	}
	/**
	 * Helper to check if a attribute is a Woodmart Attribute.
	 *
	 */
	protected function is_woodmart_attr( $key ) {
		if(isset($this->product_meta_keys['Woodmart Image Gallery'])){
			return array_key_exists( $key, $this->product_meta_keys['Woodmart Image Gallery'] );
		}
	}

	/**
	 * Helper to check if a attribute is a Woocommerce Brand Attribute.
	 *
	 */
	protected function is_wc_brand_attr( $key ) {
		if(isset($this->product_meta_keys['Woocommerce Brand'])){
			return array_key_exists( $key, $this->product_meta_keys['Woocommerce Brand'] );
		}
	}

	/**
	 * Helper to check if a attribute is a Perfect Brand Attribute.
	 *
	 */
	protected function is_perfect_attr( $key ) {
		if(isset($this->product_meta_keys['Perfect Brand'])){
			return array_key_exists( $key, $this->product_meta_keys['Perfect Brand'] );
		}
	}

	/**
	 * Helper to check if a attribute is a Image Attribute.
	 *
	 * @since    1.0.0
	 */
	protected function is_image_attr( $key ) {
		return array_key_exists( $key, $this->product_meta_keys['Image Attributes'] );
	}

	/**
	 * Helper to check if a attribute is a Product Attribute.
	 *
	 * @since    1.0.0
	 */
	protected function is_product_attr( $key ) {
		return array_key_exists( $key, $this->product_meta_keys['Product Attributes'] );
	}

	/**
	 * Helper to check if a attribute is a Glami Attribute.
	 */
	protected function is_glami_attr( $key ) {
		return array_key_exists( $key, $this->product_meta_keys['Glami Attributes'] );
	}


	/**
	 * Helper to check if a attribute is a Product dynamic Attribute.
	 *
	 * @since    1.0.0
	 */
	protected function is_product_dynamic_attr( $key ) {
		return array_key_exists( $key, $this->product_meta_keys['Product Dynamic Attributes'] );
	}


	/**
	 * Helper to check if a attribute is a Product Custom Attribute.
	 *
	 * @since    1.0.0
	 */
	protected function is_product_custom_attr( $key ) {
		return array_key_exists( $key, $this->product_meta_keys['Product Custom Attributes'] );
	}

	/**
	 * Helper to check if a attribute is a Category Mapper.
	 *
	 * @since    1.0.0
	 */
	protected function is_product_category_mapper_attr( $key ) {

		return array_key_exists( $key, $this->product_meta_keys['Category Map'] );
	}


	/**
	 * Helper to get condition of a product.
	 *
	 * @since    1.0.0
	 */
	protected function get_condition( ) {
		return 'New';
	}


	/**
	 * Helper to get parent product id of a product.
	 *
	 * @return int
	 */
	protected function get_item_group_id() {
		if($this->product->is_type('variation')) {
			return $this->product->get_parent_id();
		}
		return '';
	}


	/**
	 * Get grouped price
	 *
	 * @since    2.0.3
	 */
	public function get_grouped_price($product, $type) {
		$groupProductIds = $product->get_children();
		$sum = 0;
		if(!empty($groupProductIds)){
			foreach($groupProductIds as $id){
				$product = wc_get_product($id);
				$regularPrice = $product->get_regular_price();
				$currentPrice = $product->get_price();
				if($type == "regular"){

					$sum += (int)($regularPrice);
				}else{
					$sum += (int)$currentPrice;
				}
			}
		}

		return $sum;
	}



	/**
	 * Helper to get availability of a product
	 *
	 * @since    1.0.0
	 */
	protected function get_availability( ) {
		if ($this->product->is_on_backorder()) {
			return apply_filters('wpfm_product_availability_backorder', 'out_of_stock');
		} elseif ( $this->product->is_in_stock() == TRUE ) {
			return apply_filters('wpfm_product_availability', 'in_stock');
		} else {
			return apply_filters('wpfm_product_availability', 'out_of_stock');
		}
	}

	/**
	 * Helper to get availability underscore of a product
	 *
	 * @since    1.0.0
	 */
	protected function get_availability_underscore( ) {
		if ($this->product->is_on_backorder()) {
			return apply_filters('wpfm_product_availability_backorder', 'out of stock');
		} elseif ( $this->product->is_in_stock() == TRUE ) {
			return apply_filters('wpfm_product_availability', 'in stock');
		} else {
			return apply_filters('wpfm_product_availability', 'out of stock');
		}
	}

	/**
	 * Helper to get availability underscore of a product
	 *
	 * @since    1.0.0
	 */
	protected function get_availability_backorder_instock( ) {
		if ($this->product->is_on_backorder()) {
			return apply_filters('wpfm_product_availability_backorder', 'in_stock');
		} elseif ( $this->product->is_in_stock() == TRUE ) {
			return apply_filters('wpfm_product_availability', 'in_stock');
		} else {
			return apply_filters('wpfm_product_availability', 'out_of_stock');
		}
	}

	/**
	 * Helper to get availability underscore of a product
	 *
	 * @since    1.0.0
	 */
	protected function get_availability_backorder( ) {
		if ($this->product->is_on_backorder()) {
			return apply_filters('wpfm_product_availability_backorder', 'on_backorder');
		} elseif ( $this->product->is_in_stock() == TRUE ) {
			return apply_filters('wpfm_product_availability', 'in_stock');
		} else {
			return apply_filters('wpfm_product_availability', 'out_of_stock');
		}
	}


	/**
	 * @return string
	 */
	protected function get_stock( ) {
		if ( $this->product->is_in_stock() == TRUE ) {
			return 'Y';
		} else {
			return 'N';
		}
	}

	/**
	 * Add neccessary prefix/suffix to a value.
	 *
	 * @since    1.0.0
	 */
	protected function maybe_add_prefix_suffix($val, $rule) {
		$prefix =  $rule['prefix'];
		$suffix =  $rule['suffix'];

		if ( !empty( $prefix ) ) {
			$val = $val ? $prefix . $val : '';
		}

		if ( !empty( $suffix ) ) {
			$val = $val ? $val . $suffix : '';
		}

		return $val;
	}

	/**
	 * Escape a value with specific escape method.
	 *
	 * @since    1.0.0
	 */
	protected function maybe_escape($val, $escape) {
		switch ($escape){
			case 'strip_tags':
				$val = preg_replace('/(?:<|&lt;).*?(?:>|&gt;)/', '', $val);
				$striped_string =  strip_tags($val);

				if(substr($striped_string, -1) == " "){
					return rtrim($striped_string);
				}
				return $striped_string;
			case 'utf_8_encode':
				return utf8_encode($val);
			case 'htmlentities':
				return htmlentities($val);
			case 'integer':
			case 'price':
				return intval($val);
			case 'remove_space':
				return preg_replace('/\s+/', '', $val);;
			case 'remove_shortcodes_and_tags':
				$val = preg_replace('/(?:<|&lt;).*?(?:>|&gt;)/', '', $val);
				$striped_string =  strip_tags($val);
				if(substr($striped_string, -1) == " "){
					$striped_string = preg_replace('#\[[^\]]+\]#', '',$striped_string);
					return rtrim(strip_shortcodes( $striped_string ));
				}

				$striped_string = preg_replace('#\[[^\]]+\]#', '',$striped_string);
				return strip_shortcodes( $striped_string );

			case 'remove_shortcodes':
				$val = preg_replace('#\[[^\]]+\]#', '',$val);
				return strip_shortcodes( $val );
			case 'remove_special':
				return filter_var($val, FILTER_SANITIZE_STRING);
			case 'cdata':
				return $val ? "CDATA $val " : $val;
				return $val ? "&#x3C;![CDATA [$val]]&#x3E;" : $val;
			case 'cdata_without_space':
				return $val ? "CDATA$val" : $val;
			case 'remove_underscore':
				return str_replace('_', ' ', $val);
			case 'remove_decimal':
				if($this->checkIfFloat($val)) {
					$val = number_format($val, 2, '.', '');
					for ($i=0; $i<2; $i++) {
						$val = $val * 10;
					}
				}else {
					return intval($val) * 100;
				}
				return $val;
			case 'add_two_decimal':
				if($this->checkIfFloat($val)) {
					$val = round($val, 2);
				}
				return $val;
			case 'remove_hyphen':
				return str_replace('-', '', $val);

			case 'remove_hyphen_space':
				return str_replace('-', ' ', $val);

			case 'replace_space_with_hyphen':
				return str_replace(' ', '-', $val);

			default:
				return $val;
				break;

		}
	}


	/**
	 * check if float
	 *
	 * @param $num
	 * @return bool
	 */
	private function checkIfFloat($num) {
		return is_float($num) || is_numeric($num) && ((float) $num != (int) $num);
	}


	/**
	 * Limit the output chars to specified length.
	 *
	 * @since    1.0.0
	 */
	protected function maybe_limit($val, $limit) {
		$limit = (int) $limit;
		if ( $limit > 0) {
			return substr($val, 0, $limit);
		}
		return $val;
	}

	/**
	 * Setup variation data if current product is a variable product.
	 *
	 * @since    1.0.0
	 */
	protected function maybe_set_variation_data() {

		if ( 'WC_Product_Variation' != get_class($this->product) ) {
			return;
		}

		$variant_atts = $this->product->get_variation_attributes();

		foreach ($variant_atts as $key => $value) {
			$key = str_replace( 'attribute_pa_', '', $key);

			if( in_array($key, $this->variant_atts) ){
				$this->data[$key] = $value;
			}
		}

	}


	/**
	 * Remove shortcode
	 * from content
	 *
	 * @param $content
	 * @return string
	 * @since    2.0.3
	 */

	public function remove_short_codes($content) {
		if(empty($content)){
			return "";
		}
		$content = $this->remove_invalid_xml($content);
		return strip_shortcodes($content);
	}

	/**
	 * Removes invalid XML
	 *
	 * @param string $value
	 * @return string
	 */
	public function remove_invalid_xml($value) {

		$ret = "";
		$current = "";
		if (empty($value)) {
			return $ret;
		}

		$length = strlen($value);
		for ($i=0; $i < $length; $i++) {
			$current = ord($value[$i]);
			if (($current == 0x9) ||
			    ($current == 0xA) ||
			    ($current == 0xD) ||
			    (($current >= 0x20) && ($current <= 0xD7FF)) ||
			    (($current >= 0xE000) && ($current <= 0xFFFD)) ||
			    (($current >= 0x10000) && ($current <= 0x10FFFF)))
			{
				$ret .= chr($current);
			}
			else
			{
				$ret .= " ";
			}
		}
		return $ret;

	}


	/**
	 * calculate the value of identifier_exists
	 *
	 * @return string
	 * @since    1.2.5
	 */

	public function calculate_identifier_exists ($data) {

		$identifier_exists = "no";

		if (array_key_exists("brand", $data) AND ($data['brand'] != "")){
			if ((array_key_exists("gtin", $data)) AND ($data['gtin'] != "")){
				$identifier_exists = "yes";
			} elseif ((array_key_exists("mpn", $data)) AND ($data['mpn'] != "")){
				$identifier_exists = "yes";
			} else {
				$identifier_exists = "no";
			}
		} else {
			if ((array_key_exists("gtin", $data)) AND ($data['gtin'] != "")){
				$identifier_exists = "no";
			} elseif ((array_key_exists("mpn", $data)) AND ($data['mpn'] != "")){
				$identifier_exists = "no";
			} else {
				$identifier_exists = "no";
			}
		}

		return $identifier_exists;
	}

	/**
	 * Returns the product shipping cost.
	 *
	 * @return string
	 */
	public function get_shipping_cost() {

		return '';
	}

	/**
	 * Check if this product is child product or not
	 *
	 * @return bool
	 * @since    1.0.0
	 */
	protected function is_children(){
		return $this->product->get_parent_id() ? true: false;
	}

	function __call($name, $arguments)
	{
		// TODO: Implement __call() method.
	}

	public function get_args($raw_args = false) {
		if(!$raw_args)
			$raw_args=  $this->args;

		$args = array(
			"post_type"=>array("product", "product_variation")
		);
		if(isset($raw_args["type"])&&$raw_args["type"]=="by-id")
		{
			$args['post__in'] = explode(",",$raw_args["ids"]);
		}
		else
		{
			//Tax queries
			if (isset($raw_args["tax_query"]["queries"])) {
				$args["tax_query"] = array();
				$args["tax_query"]["relation"] = $raw_args["tax_query"]["relation"];
				foreach ($raw_args["tax_query"]["queries"] as $query) {
					array_push($args["tax_query"], $query);
				}
			}

			//Metas
			if (isset($raw_args["meta_query"]["queries"])) {
				$args["meta_query"] = array();
				$args["meta_query"]["relation"] = $raw_args["meta_query"]["relation"];
				foreach ($raw_args["meta_query"]["queries"] as $query) {
					//Some operators expect an array as value
					$array_operators = array('IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN');
					if (in_array($query["compare"], $array_operators))
						$query["value"] = explode(",", $query["value"]);
					array_push($args["meta_query"], $query);
				}
			}

			//Other parameters
			$other_parameters = array("author__in", "post__not_in");
			foreach ($other_parameters as $parameter) {
				if (!isset($raw_args[$parameter]))
					continue;
				if ($parameter == "post__not_in")
					$args[$parameter] = explode(",", $raw_args[$parameter]);
				else if ($parameter == "author__in" && $raw_args[$parameter] == array(""))
					continue;
				else
					$args[$parameter] = $raw_args[$parameter];
			}
		}

		$args["nopaging"]=true;

		return $args;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	private function safeCharEncodeURL($string)
	{
		return str_replace(
			array('%', '[', ']', '{', '}', '|', ' ', '"', '<', '>', '#', '\\', '^', '~', '`'),
			array('%25', '%5b', '%5d', '%7b', '%7d', '%7c', '%20', '%22', '%3c', '%3e', '%23', '%5c', '%5e', '%7e', '%60'),
			$string);
	}
}