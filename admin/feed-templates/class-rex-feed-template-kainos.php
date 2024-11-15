<?php
/**
 * The Kainos.lt price aggregator Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      7.4.23
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 * Defines the attributes and template for Kainos.lt price aggregator feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Kainos
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Kainos extends Rex_Feed_Abstract_Template {

	/**
	 * Define merchant's required and optional/additional attributes
	 *
	 * @return void
	 */
	protected function init_atts() {
		$this->attributes = array(
			'Required Information' => array(
                'id'                  => 'Product ID',
                'title'               => 'Product name',
                'item_price'          => 'Item price',
                'manufacturer'        => 'Manufacturer',
                'image_url'           => 'Main image URL',
                'product_url'         => 'Product URL',
                'categories'          => 'Product categories',
            ),
            'Optional Information' => array(
                'loyalty_program_item_price' => 'Loyalty program item price',
                'stock'               => 'Stock',
                'ean_code'            => 'EAN code',
                'eans'                => 'EAN codes',
                'manufacturer_code'   => 'Manufacturer code (MPN)',
                'model'               => 'Model',
                'additional_images'   => 'Additional images',
                'specs'               => 'Specs',
                'delivery_time'       => 'Delivery time (days)',
                'delivery_text'       => 'Delivery text',
                'short_message'       => 'Short marketing phrase',
            ),
		);
	}

	/**
	 * Define merchant's default attributes
	 *
	 * @return void
	 */
	protected function init_default_template_mappings() {
		$this->template_mappings = array(
			array(
				'attr'     => 'id',
				'type'     => 'meta',
				'meta_key' => 'id',
				'st_value' => '',
				'prefix'   => '',
				'suffix'   => '',
				'escape'   => 'default',
				'limit'    => 0,
			),
			array(
				'attr'     => 'title',
				'type'     => 'meta',
				'meta_key' => 'title',
				'st_value' => '',
				'prefix'   => '',
				'suffix'   => '',
				'escape'   => 'default',
				'limit'    => 0,
			),
            array(
                'attr'     => 'item_price',
                'type'     => 'meta',
                'meta_key' => 'current_price',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'manufacturer',
                'type'     => 'meta',
                'meta_key' => 'brand',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'image_url',
                'type'     => 'meta',
                'meta_key' => 'main_image',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'product_url',
                'type'     => 'meta',
                'meta_key' => 'link',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
			array(
				'attr'     => 'categories',
				'type'     => 'meta',
				'meta_key' => 'product_cats',
				'st_value' => '',
				'prefix'   => '',
				'suffix'   => '',
				'escape'   => 'default',
				'limit'    => 0,
			),
            array(
                'attr'     => 'ean_code',
                'type'     => 'meta',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
		);
	}
}
