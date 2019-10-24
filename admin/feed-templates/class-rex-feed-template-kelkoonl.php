<?php

/**
 * The Kelkoo.nl Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      1.2.5
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes and template for Kelkoo feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Kelkoonl
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Kelkoonl extends Rex_Feed_Abstract_Template {

    protected function init_atts(){
        $this->attributes = array(
            'Required Information'      =>  array(
                'title'                 => 'Product Title',
                'product-url'           => 'Product URL',
                'price'                 => 'Price',
                'availability'          => 'Availability',
                'delivery-cost'         => 'Delivery cost',
                'offer-id'              => 'Offer Id',
            ),
            'Recommended Fields'    => array(
                'brand'                 => 'Brand',
                'description'           => 'Description',
                'image-url'             => 'Image Url',
                'ean'                   => 'EAN',
                'merchant-category'     => 'Merchant Category',
                'delivery-time'         => 'Delivery time',
                'condition'             => 'Condition',
                'ecotax'                => 'Ecotax',
                'warranty'              => 'Warranty',
                'mpn'                   => 'Manufacturer Part Number',
                'mobile-url'            => 'Mobile url',
                'kelkoo-category-id'    => 'Kelkoo category id',
                'color'                 => 'Color',
                'unit-price'            => 'Unit price',
                'offer-type'            => 'Offer type',
                'green-product'         => 'Green product',
                'green-label'           => 'Green label',
                'shipping-method'       => 'Shipping method',
                'delivery-cost-2'       => 'Delivery cost-2',
                'delivery-cost-3'       => 'Delivery cost-3',
                'delivery-cost-4'       => 'Delivery cost-4',
                'shipping-method-2'     => 'Shipping-method-2',
                'shipping-method-3'     => 'Shipping-method-3',
                'shipping-method-4'     => 'Shipping-method-4',
                'zip-code'              => 'Zip code',
                'price-no-rebate'       => 'Price no rebate',
                'voucher-title'         => 'Voucher title',
                'fashion-type'         => 'Fashion Type',
                'fashion-gender'         => 'Fashion Gender',
                'fashion-size'         => 'Fashion Size',
                'software-platform'         => 'Software platform',
                'property-type'         => 'Property type',
                'property-source'         => 'Property source',
                'property-garage-parking'         => 'Property garage parking',
                'property-city	'         => 'Property city	',
                'property-zip-code	'         => 'Property zip code',
                'property-number-rooms	'         => 'Property number rooms',
                'property-surface	'         => 'Surface',
                'property-publication-date	'         => 'Property publication date',
                'property-tenure	'         => 'Property tenure',
            ),
            'Additional Information'    => array(
                'sku'                   => 'SKU',
                'currency'              => 'currency',
                'alternate_image_url'   => 'Alternate Image URL',
                'regular_price'         => 'Regular Price',
                'size'                  => 'Size',
                'merchant-info'         => 'Merchant info',
                'image-url-2'           => 'Image url-2',
                'image-url-3'           => 'Image url-3',
                'image-url-4'           => 'Image url-4',
                'sales-rank'            => 'Sales rank',
                'unit-quantity'         => 'Unit quantity',
                'made-in'               => 'Made in',
                'occasion'              => 'Occasion',
                'keywords'              => 'Keywords',
                'stock-quantity'        => 'Stock quantity',
                'shipping-weight'       => 'Shipping weight',
                'payment-methods'       => 'Payment methods',
                'voucher-description'   => 'Voucher description',
                'voucher-url'           => 'Voucher url',
                'voucher-code'          => 'Voucher code',
                'voucher-start-date'    => 'Voucher start date',
                'voucher-end-date'      => 'Voucher-end-date',
                'percentage-promo'      => 'Percentage promo',
                'promo-start-date'      => 'Promo start date',
                'promo-end-date'        => 'Promo end date',
                'user-rating'           => 'User rating',
                'nb-reviews'            => 'NB reviews',
                'user-review-link'      => 'User review link',
                'video-link'            => 'Video link',
                'video-title'           => 'Video title',
            ),

        );
    }

    protected function init_default_template_mappings(){
        $this->template_mappings = array(
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
                'attr'     => 'product-url',
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
                'suffix'   => ' '.get_option('woocommerce_currency'),
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
                'attr'     => 'delivery-cost',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'offer-id',
                'type'     => 'meta',
                'meta_key' => 'id',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            )
        );
    }

}
