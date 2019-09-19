<?php

/**
 * The admarkt Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      3.4
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes and template for admarkt feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_admarkt
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Admarkt extends Rex_Feed_Abstract_Template {

    protected function init_atts(){
        $this->attributes = array(
            'Required Fields'      =>  array(
                'Product_ID'              => 'Product ID',
                'Product_category'        => 'Product Category',
                'Product_title'           => 'Product Title',
                'Product_description'     => 'Product Description',
                'Product_URL'             => 'Product URL',
                'Product_price'           => 'Product Price',
                'Price_type'              => 'Price Type',
                'Cost-per-click-value'    => 'Cost Per Click Value'
            ),
            'Optional Fields'    => array(
                'Product_image'     => 'Product image',
                'brand'             => 'Manufacturer [brand]',
            ),
        );
    }

    protected function init_default_template_mappings(){
        $this->template_mappings = array(
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
                'meta_key' => 'product_cats',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
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
                'attr'     => 'Product_price',
                'type'     => 'meta',
                'meta_key' => 'price',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Price_type',
                'type'     => 'meta',
                'meta_key' => 'static',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Cost-per-click-value',
                'type'     => 'meta',
                'meta_key' => 'static',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
        );
    }

}
