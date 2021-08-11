<?php


/**
 *
 * Defines the attributes and template for Lesitedumif feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Lesitedumif
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Lesitedumif extends Rex_Feed_Abstract_Template {

    protected function init_atts(){
        $this->attributes = array(
            'Lesitedumif Information' => array(
                'id'                  => 'Product ID [id]',
                'title'                    => 'Product Title [title]',
                'description'                  => 'Product Description [description]',
                'price_with_tax'           => 'Sale Price [sale_price]',
                'product_url'               => 'Product URL [link]',
                'image_link'         => 'Main Image [image_link]',
                'product_manufacturer'         => 'Manufacturer [brand]'
            )
        );
    }

    protected function init_default_template_mappings(){
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
                'attr'     => 'description',
                'type'     => 'meta',
                'meta_key' => 'description',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'price_with_tax',
                'type'     => 'meta',
                'meta_key' => 'current_price_with_tax',
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
                'attr'     => 'image_link',
                'type'     => 'meta',
                'meta_key' => 'main_image',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'product_manufacturer',
                'type'     => 'meta',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            )
        );
    }
}