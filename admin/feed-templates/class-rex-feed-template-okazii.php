<?php

/**
 * The Okazii marketplace Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      1.1.4
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */
/**
 *
 * Defines the attributes and template for Okazii marketplace feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Okazii
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Okazii extends Rex_Feed_Abstract_Template {

    protected function init_atts(){
        $this->attributes = array(
            'Required Information' =>  array(
                'Product_ID'            => 'Product ID',
                'Product_title'         => 'Product Title',
                'Product_category'      => 'Product category',
                'Product_description'   => 'Product description',
                'Currency'              => 'Currency',
                'Product_price'         => 'Product price',
                'Product_quantity'      => 'Product Quantity',
                'Product_URL'           => 'Product URL',
            ),
        );
    }

    protected function init_default_template_mappings(){
        $this->template_mappings = array(
            array(
                'attr'     => 'Product_title',
                'type'     => 'meta',
                'meta_key' => 'title',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Product_description',
                'type'     => 'meta',
                'meta_key' => 'description',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Product_ID',
                'type'     => 'meta',
                'meta_key' => 'id',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Product_category',
                'type'     => 'meta',
                'meta_key' => 'category',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Currency',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Product_quantity',
                'type'     => 'meta',
                'meta_key' => 'quantity',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Product_price',
                'type'     => 'meta',
                'meta_key' => 'price',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => ' '.get_option('woocommerce_currency'),
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Product_URL',
                'type'     => 'meta',
                'meta_key' => 'Url',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
        );
    }

}
