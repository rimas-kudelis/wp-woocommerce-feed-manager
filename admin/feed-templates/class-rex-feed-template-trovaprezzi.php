<?php

/**
 * The Uvinum Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      5.5
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes and template for trovaprezzi feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Trovaprezzi
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Trovaprezzi extends Rex_Feed_Abstract_Template {

    protected function init_atts(){
        $this->attributes = array(
            'Required Information'      =>  array(
                'Categories'          => 'Product Categories',
                'Image_Link'         => 'Image Link',
                'Internal_Code'                   => 'Internal Code',
                'Link'           => 'Product URL',
                'Name'      => 'Product Name',
                'Retail_Price'      => 'Retail price',
                'Shipping_Cost'      => 'Shipping Cost'
            ),

            'Additional Information'    => array(
                'Additional_Image_Link'  => 'Additional Image Link',
                'Availability'  => 'Availability',
                'Brand'  => 'Brand',
                'Description'  => 'Description',
                'EAN'  => 'EAN',
                'Manafacturer_Code'  => 'Manafacturer Code',
                'Orginal_Price'  => 'Orginal Price',
            ),

        );
    }

    protected function init_default_template_mappings(){
        $this->template_mappings = array(
            array(
                'attr'     => 'Name',
                'type'     => 'meta',
                'meta_key' => 'title',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Internal_Code',
                'type'     => 'meta',
                'meta_key' => 'id',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Categories',
                'type'     => 'meta',
                'meta_key' => 'product_cats',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Image_Link',
                'type'     => 'meta',
                'meta_key' => 'featured_image',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Link',
                'type'     => 'meta',
                'meta_key' => 'link',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'cdata',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Retail_Price',
                'type'     => 'meta',
                'meta_key' => 'price',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => ' '.get_option('woocommerce_currency'),
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Shipping_Cost',
                'type'     => 'static',
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
