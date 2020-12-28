<?php

/**
 * The Google custom search ads Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes and template for Google_Custom_Search_Ads feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Google_Custom_Search_Ads
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Google_custom_search_ads extends Rex_Feed_Abstract_Template
{
    protected function init_atts()
    {
        $this->attributes = array(
            'Required Information' => array(
                'Product_URL'       => 'Product URL',
                'category'          => 'Product Category',
                'stock'          => 'In Stock',
                'price'         => 'Price'
            ),
            'Additional Attributes' => array(
                'id'                       => 'Product Id [id]',
                'description'              => 'Product Description [description]',
                'sku'               => 'SKU',
            ),
        );
    }

    protected function init_default_template_mappings()
    {
        $this->template_mappings = array(
            array(
                'attr'     => 'Product_URL',
                'type'     => 'meta',
                'meta_key' => 'link',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),

            array(
                'attr'     => 'category',
                'type'     => 'meta',
                'meta_key' => 'product_cats_path',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'stock',
                'type'     => 'meta',
                'meta_key' => 'availability',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'price',
                'type'     => 'meta',
                'meta_key' => 'current_price',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => ' '.get_option('woocommerce_currency'),
                'escape'   => 'default',
                'limit'    => 0,
            )
        );
    }

}
