<?php

/**
 * The eBay Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      1.1.4
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes and template for eBay feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Ebay
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Connexity extends Rex_Feed_Abstract_Template {

    protected function init_atts(){
        $this->attributes = array(

            'Required Information'      =>  array(
                'Unique_ID'             => 'Unique ID',
                'Title'                 => 'Title',
                'Description'           => 'Description',
                'Category'              => 'Category', 
                'Product_URL'           => 'Product URL',
                'Image_URL'             => 'Image URL', 
                'Condition'             => 'Condition', 
                'Availability'          => 'Availability',
                'Current_Price'         => 'Price',  
                'Brand'                 => 'Brand',
            ),

            'Additional Information'        => array(
                'Gender'                    => 'Gender',
                'Age_Group'                 => 'Age Group',
                'Color'                     => 'Color',
                'Size'                      => 'Size',
                'Material'                  => 'Material',
                'Pattern'                   => 'Pattern',
                'Item_Group_ID'             => 'Item Group ID',
                'Page_ID'                   => 'Page ID',
                'Page_ID_Variant'           => 'Page ID Variant',
                'Generic_Title'             => 'Generic Title',
                'Unit_Price'                => 'Unit Price',
                'Currency'                  => 'Currency',
                'PZN'                       => 'PZN',
                'Delivery_Period'           => 'Delivery Period',
                'Energy_Efficiency_Class'   => 'Energy Efficiency Class',
            ),
        );
    }

    protected function init_default_template_mappings(){
        $this->template_mappings = array(

            array(
                'attr'     => 'Unique_ID',
                'type'     => 'meta',
                'meta_key' => 'id',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Title',
                'type'     => 'meta',
                'meta_key' => 'title',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),

            array(
                'attr'     => 'Description',
                'type'     => 'meta',
                'meta_key' => 'description',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Category',
                'type'     => 'meta',
                'meta_key' => 'product_cats',
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
                'attr'     => 'Image_URL',
                'type'     => 'meta',
                'meta_key' => 'featured_image',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),

            array(
                'attr'     => 'Condition',
                'type'     => 'meta',
                'meta_key' => 'condition',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),

            array(
                'attr'     => 'Availability',
                'type'     => 'meta',
                'meta_key' => 'availability',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),

            array(
                'attr'     => 'Current_Price',
                'type'     => 'meta',
                'meta_key' => 'price',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => ' '.get_option('woocommerce_currency'),
                'escape'   => 'default',
                'limit'    => 0,
            ),
        );
    }

}