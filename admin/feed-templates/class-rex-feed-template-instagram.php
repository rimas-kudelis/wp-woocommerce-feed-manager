<?php

/**
 * The Instagram Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes and template for Instagram feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Instagram
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Instagram extends Rex_Feed_Abstract_Template {

    protected function init_atts(){
        $this->attributes = array(
            'Basic Information' =>  array(
                'id'                       => 'Product Id [id]',
                'title'                    => 'Product Title [title]',
                'description'              => 'Product Description [description]',
                'link'                     => 'Product URL [link]',
                'product_type'             => 'Product Categories [product_type] ',
                'image_link'               => 'Main Image [image_link]',
                'condition'                => 'Condition [condition]',
                'availability'              => 'Stock Status [availability]',
                'brand'             => 'Manufacturer [brand]',
                'gtin'               => 'GTIN [gtin]',
                'mpn'               => 'MPN [mpn]',

            ),

            'Optional Information' => array(
                'additional_image_link'                     => 'Additional image link',
                'age_group'     => 'Age Group [age_group]',
                'color'         => 'Color [color]',
                'custom_label_0' => 'Custom label 0 [custom_label_0]',
                'custom_label_1' => 'Custom label 1 [custom_label_1]',
                'custom_label_2' => 'Custom label 2 [custom_label_2]',
                'custom_label_3' => 'Custom label 3 [custom_label_3]',
                'custom_label_4' => 'Custom label 4 [custom_label_4]',
                'expiration_date'      => 'Expiration Date [expiration_date]',
                'gender'        => 'Gender [gender]',
                'google_product_category'  => 'Google Product Category [google_product_category]',
                'item_group_id' => 'Item Group Id [item_group_id]',
                'material'      => 'Material [material]',
                'pattern'       => 'Pattern [pattern]',
                'product_type'             => 'Product Categories [product_type] ',
                'sale_price'                => 'Sale Price [sale_price]',
                'sale_price_effective_date' => 'Sale Price Effective Date [sale_price_effective_date]',
                'shipping'           => 'Shipping',
                'size'           => 'Size',
                'shipping_weight'           => 'Shipping Weight [shipping_weight]',
            ),

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
                'attr'     => 'image_link',
                'type'     => 'meta',
                'meta_key' => 'featured_image',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'link',
                'type'     => 'meta',
                'meta_key' => 'link',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'product_type',
                'type'     => 'meta',
                'meta_key' => 'product_cats',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'availability',
                'type'     => 'meta',
                'meta_key' => 'availability',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'condition',
                'type'     => 'meta',
                'meta_key' => 'condition',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'brand',
                'type'     => 'meta',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'gtin',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'mpn',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'google_product_category',
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
