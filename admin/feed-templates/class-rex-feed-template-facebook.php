<?php

/**
 * The Facebook Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes and template for facebook feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Facebook
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Facebook extends Rex_Feed_Abstract_Template {

  protected function init_atts(){
    $this->attributes = array(
        'Basic Information' =>  array(
            'id'                       => 'Product Id [id]',
            'title'                    => 'Product Title [title]',
            'description'              => 'Product Description [description]',
            'link'                     => 'Product URL [link]',
            'product_type'             => 'Product Categories [product_type] ',
            'image_link'               => 'Main Image [image_link]',
            'google_product_category'  => 'Google Product Category [google_product_category]',
            'additional_image_link_1'  => 'Additional Image 1 [additional_image_link]',
            'additional_image_link_2'  => 'Additional Image 2 [additional_image_link]',
            'additional_image_link_3'  => 'Additional Image 3 [additional_image_link]',
            'additional_image_link_4'  => 'Additional Image 4 [additional_image_link]',
            'additional_image_link_5'  => 'Additional Image 5 [additional_image_link]',
            'additional_image_link_6'  => 'Additional Image 6 [additional_image_link]',
            'additional_image_link_7'  => 'Additional Image 7 [additional_image_link]',
            'additional_image_link_8'  => 'Additional Image 8 [additional_image_link]',
            'additional_image_link_9'  => 'Additional Image 9 [additional_image_link]',
            'additional_image_link_10' => 'Additional Image 10 [additional_image_link]',
            'condition'                => 'Condition [condition]',
        ),

        'Availability & Price' => array(
            'availability'              => 'Stock Status [availability]',
            'price'                     => 'Regular Price [price]',
            'sale_price'                => 'Sale Price [sale_price]',
            'sale_price_effective_date' => 'Sale Price Effective Date [sale_price_effective_date]',
        ),

        'Unique Product Identifiers' => array(
            'brand'             => 'Manufacturer [brand]',
            'upc'               => 'GTIN [gtin]',
            'mpn'               => 'MPN [mpn]',
        ),

        'Detailed Product Attributes' => array(
            'item_group_id' => 'Item Group Id [item_group_id]',
            'color'         => 'Color [color]',
            'gender'        => 'Gender [gender]',
            'age_group'     => 'Age Group [age_group]',
            'material'      => 'Material [material]',
            'pattern'       => 'Pattern [pattern]',
            'size'          => 'Size of the item [size]',
        ),

        'Tax & Shipping' => array(
            'tax'              => 'Tax [tax]',
            'weight'           => 'Shipping Weight [shipping_weight]',
            'length'           => 'Shipping Length [shipping_length]',
            'width'            => 'Shipping Width [shipping_width]',
            'height'           => 'Shipping Height [shipping_height]',
        ),


        'Custom Label Attributes' => array(
            'custom_label_0' => 'Custom label 0 [custom_label_0]',
            'custom_label_1' => 'Custom label 1 [custom_label_1]',
            'custom_label_2' => 'Custom label 2 [custom_label_2]',
            'custom_label_3' => 'Custom label 3 [custom_label_3]',
            'custom_label_4' => 'Custom label 4 [custom_label_4]',
        ),

        'Additional Attributes' => array(
            'expiration_date'      => 'Expiration Date [expiration_date]',
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
            'attr'     => 'price',
            'type'     => 'meta',
            'meta_key' => 'price',
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