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
class Rex_Feed_Template_Ebay extends Rex_Feed_Abstract_Template {

    protected function init_atts(){
        $this->attributes = array(
            'Required Information'  =>  array(
                'Merchant_SKU'      => 'Merchant SKU',
                'Product_Name'      => 'Product Name',
                'category'          => 'Product Category',
                'Producl_URL'       => 'Product URL',
                'Image_URL'         => 'Image URL',
                'Current_Price'     => 'Current Price',
                'Stock_Availability'=> 'Stock Availability',
                'Condition'         => 'Condition',
            ),

            'Additional Information'        => array(
                'Shipping_Rate'             => 'Shipping Rate',
                'mpn'                       => 'MPN(Manufacturer Part Number)',
                'ISBN'                      => 'ISBN',
                'upc'                       => 'UPC',
                'EAN'                       => 'EAN',
                'Original_Price'            => 'Original Price',
                'Coupon_Code'               => 'Coupon Code',
                'Coupon_Code_Description'   => 'Coupon Code Description',
                'Manufacturer'              => 'Manufacturer',
                'Product_Description'       => 'Product Description',
                'Product_Type'              => 'Product Type',
                'Category'                  => 'Category',
                'Category_ID'               => 'Category ID',
                'Parent_SKU'                => 'Parent SKU',
                'Parent_Name'               => 'Parent Name',
                'Top_Seller_Rank'           => 'Top Seller Rank',
                'Estimated_Ship_Date'       => 'Estimated Ship Date',
                'Colour'                    => 'Colour',
                'Material'                  => 'Material',
                'Size'                      => 'Size',
                'Size_Unit_of_Measure'      => 'Size_Unit_of_Measure',
                'Age_Range'                 => 'Age_Range',
                'Cell_Phone_Plan_Type'      => 'Cell_Phone_Plan_Type',
                'Cell_Phone_Service_Provider'=> 'Cell_Phone_Service_Provider',
                'Stock_Description'         => 'Stock_Description',
                'Product_Launch_Date'       => 'Product_Launch_Date',
                'Product_Bullet_Point_1'    => 'Product_Bullet_Point_1',
                'Product_Bullet_Point_3'    => 'Product_Bullet_Point_3',
                'Product_Bullet_Point_4'    => 'Product_Bullet_Point_4',
                'Product_Bullet_Point_5'    => 'Product_Bullet_Point_5',
                'Alternative_Image_URL_1'   => 'Alternative_Image_URL_1',
                'Alternative_Image_URL_2'   => 'Alternative_Image_URL_2',
                'Alternative_Image_URL_3'   => 'Alternative_Image_URL_3',
                'Alternative_Image_URL_4'   => 'Alternative_Image_URL_4',
                'Alternative_Image_URL_5'   => 'Alternative_Image_URL_5',
                'Mobile_URL'                => 'Mobile_URL',
                'Related_Products'          => 'Related_Products',
                'Merchandising_Type'        => 'Merchandising_Type',
                'Product_Weight'            => 'Product_Weight',
                'Shipping_Weight'           => 'Shipping_Weight',
                'Weight_Unit_of_Measure'    => 'Weight_Unit_of_Measure',
                'Format'                    => 'Format',
                'Unit_Price'                => 'Unit_Price',
                'Bundle'                    => 'Bundle',
                'Software_Platform'         => 'Software_Platform',
                'Watch_Display_Type'        => 'Watch_Display_Type',
            ),

        );
    }

    protected function init_default_template_mappings(){
        $this->template_mappings = array(
            array(
                'attr'     => 'Merchant_SKU',
                'type'     => 'meta',
                'meta_key' => 'sku',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Product_Name',
                'type'     => 'meta',
                'meta_key' => 'title',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Producl_URL',
                'type'     => 'meta',
                'meta_key' => 'link',
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
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'Stock_Availability',
                'type'     => 'meta',
                'meta_key' => 'availability',
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
        );
    }

}
