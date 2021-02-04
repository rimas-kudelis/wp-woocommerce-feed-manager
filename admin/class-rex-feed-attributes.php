<?php

/**
 * Helper Class to retrive Feed Attributes
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes for feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Attributes
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Attributes {

    public static function get_attributes(){

        $attributes = array(
            'Primary Attributes'        => array(
                'id'                        => 'Product Id',
                'title'                     => 'Product Title',
                'yoast_title'               => 'Product Title [Yoast SEO]',
                'description'               => 'Product Description',
                'yoast_meta_desc'           => 'Product Description [Yoast SEO]',
                'short_description'         => 'Product Short Description',
                'product_cats'              => 'Product Categories',
                'product_cats_path'         => 'Product Categories Path (with separator ">")',
                'product_cats_path_pipe'    => 'Product Categories Path (with separator "|")',
                'yoast_primary_cats_path'   => 'Yoast Primary Category Path (with separator ">")',
                'yoast_primary_cats_pipe'   => 'Yoast Primary Category Path (with separator "|")',
                'yoast_primary_cats_comma'  => 'Yoast Primary Category Path (with separator ",")',
                'product_subcategory'       => 'Product Sub Categories Path (with separator ">")',
                'yoast_primary_cat'         => 'Yoast primary category',
                'link'                      => 'Product URL',
                'parent_url'                => 'Parent Product URL',
                'condition'                 => 'Condition',
                'item_group_id'             => 'Parent ID (Group ID)',
                'sku'                       => 'SKU',
                'parent_sku'                => 'Parent SKU',
                'availability'              => 'Availability',
                'availability_underscore'   => 'Availability (Without Underscore)',
                'availability_backorder_instock'   => 'Availability (Backorder = in stock)',
                'availability_backorder'   => 'Availability (Backorder = backorder)',
                'quantity'                  => 'Quantity',
                'price'                     => 'Regular Price',
                'current_price'             => 'Price',
                'sale_price'                => 'Sale Price',
                'price_with_tax'            => 'Regular price with tax',
                'current_price_with_tax'    => 'Price with tax',
                'sale_price_with_tax'       => 'Sale price with tax',
                'price_excl_tax'            => 'Regular price excl. tax',
                'current_price_excl_tax'    => 'Price excl. tax',
                'sale_price_excl_tax'       => 'Sale price excl. tax',
                'price_db'                  => 'Regular Price (From DB)',
                'current_price_db'          => 'Price (From DB)',
                'sale_price_db'             => 'Sale Price (From DB)',
                'weight'                    => 'Weight',
                'width'                     => 'Width',
                'height'                    => 'Height',
                'length'                    => 'Length',
                "shipping_class"            => "Shipping Class",
//                "shipping_cost"             => "Shipping Cost",
                'rating_total'              => 'Total Rating',
                'rating_average'            => 'Average Rating',
                'product_tags'              => 'Tags',
                'sale_price_dates_from'     => 'Sale Start Date',
                'sale_price_dates_to'       => 'Sale End Date',
                'sale_price_effective_date' => 'Sale Price Effective Date',
                'identifier_exists'         => 'Identifier Exists Calculator',
                'in_stock'                  => 'In Stock (Y/N)',
                'promotion_id'              => 'Promotion ID',
            ),
            'Image Attributes' => array(
                'main_image'     => 'Main Image',
                'featured_image' => 'Featured Image',
                'all_image'      => 'All Images (comma separated)',
                'all_image_pipe' => 'All Images (separated by "|")',
                'image_1'        => 'Additional Image 1',
                'image_2'        => 'Additional Image 2',
                'image_3'        => 'Additional Image 3',
                'image_4'        => 'Additional Image 4',
                'image_5'        => 'Additional Image 5',
                'image_6'        => 'Additional Image 6',
                'image_7'        => 'Additional Image 7',
                'image_8'        => 'Additional Image 8',
                'image_9'        => 'Additional Image 9',
                'image_10'       => 'Additional Image 10',
            ),
        );

        // Get product attributes
        $_attributes = self::get_product_attributes();
        $attributes['Product Attributes'] = $_attributes;



        // Get product dynamic attributes
        $_dynamic_attributes = self::get_product_dynamic_attributes();
        $attributes['Product Dynamic Attributes'] = $_dynamic_attributes;

        // Get product dynamic attributes
        $_custom_attributes = self::get_product_custom_attributes();
        $attributes['Product Custom Attributes'] = $_custom_attributes;



        // Get category map list
        $cat_maps_array = array();
        $cat_maps = get_option('rex-wpfm-category-mapping');
        if($cat_maps){
            foreach ($cat_maps as $key => $cat_map){
                $cat_maps_array[$key] = $cat_map['map-name'];
            }
        }
        $attributes['Category Map'] = $cat_maps_array;

        return $attributes;
    }


    /**
     * get product attributes
     *
     * @return array
     */
    public static function get_product_attributes() {
        $taxonomies = wpfm_get_cached_data( 'product_attributes' );
        if ( false === $taxonomies ) {
            // Load the main attributes
            $globalAttributes = wc_get_attribute_taxonomy_labels();
            if ( count( $globalAttributes ) ) {
                foreach ( $globalAttributes as $key => $value ) {
                    $taxonomies['bwf_attr_pa_' . $key ] = $value;
                }
            }
            wpfm_set_cached_data( 'product_attributes', $taxonomies );
        }
        return $taxonomies;
    }


    /**
     * get product dynamic attributes
     *
     * @return array
     */
    public static function get_product_dynamic_attributes() {
        $attributes = wpfm_get_cached_data( 'product_dynamic_attributes' );
        if ( false === $attributes ) {
            // Load the main attributes
            $list = array();
            $no_taxonomies = array("category","post_tag","nav_menu","link_category","post_format","product_type","product_visibility","product_cat","product_shipping_class","product_tag");
            $taxonomies = get_taxonomies();
            $attr_name_clean = '';
            $attr_name = '';
            $diff_taxonomies = array_diff($taxonomies, $no_taxonomies);
            foreach($diff_taxonomies as $tax_diff){
                $taxonomy_details = get_taxonomy( $tax_diff );
                foreach($taxonomy_details as $kk => $vv){
                    if($kk == "name"){
                        $attr_name = $vv;
                    }
                    if($kk == "labels"){
                        foreach($vv as $kw => $kv){
                            if($kw == "singular_name"){
                                $attr_name_clean = ucfirst($kv);
                            }
                        }
                    }
                }
                $attributes["$attr_name"] = $attr_name_clean;
            }
            wpfm_set_cached_data( 'product_dynamic_attributes', $attributes );
        }
        return $attributes;
    }


    /**
     * get product custom attributes
     *
     * @return array
     */
    public static function get_product_custom_attributes() {
        $attributes = wpfm_get_cached_data( 'product_custom_attributes' );
        if ( false === $attributes ) {
            global $wpdb;
            $list = array();
            $sql = "SELECT meta_key as name, meta_value as value FROM {$wpdb->prefix}postmeta  as postmeta
                INNER JOIN {$wpdb->prefix}posts AS posts
                ON postmeta.post_id = posts.id
                WHERE posts.post_type = 'product' OR posts.post_type = 'product_variation'
                AND posts.post_status = 'publish'
                AND postmeta.meta_key NOT LIKE 'pyre%'
                AND postmeta.meta_key NOT LIKE 'sbg_%'
                group by meta_key
                ORDER BY postmeta.meta_key";
            $data = $wpdb->get_results($sql);
            if (count($data)) {
                foreach ($data as $key => $value) {
                    if (!preg_match("/_product_attributes/i", $value->name)) {
                        $value_display = str_replace("_", " ",$value->name);
                        $attributes["custom_attributes_" . $value->name] = ucfirst($value_display);
                    }else {
                        $sql = "SELECT meta_key as name, meta_value as value FROM {$wpdb->prefix}postmeta as postmeta
                            INNER JOIN {$wpdb->prefix}posts AS posts
                            ON postmeta.post_id = posts.id
                            WHERE posts.post_type LIKE '%product%'
                            AND postmeta.meta_key = '_product_attributes'";

                        $data = $wpdb->get_results($sql);
                        if(count($data)) {
                            foreach ($data as $k => $meta_value) {
                                $product_attributes = unserialize($meta_value->value);
                                if (!empty($product_attributes)) {
                                    foreach ($product_attributes as $meta_inner_k => $arr_value) {
                                        $value_display = str_replace("_", " ", $arr_value['name']);
                                        $attributes["custom_attributes_" . $meta_inner_k] = ucfirst($value_display);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            wpfm_set_cached_data( 'product_custom_attributes', $attributes );
        }
        return $attributes;
    }
}
