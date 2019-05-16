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
                'description'               => 'Product Description',
                'short_description'         => 'Product Short Description',
                'product_cats'              => 'Product Categories',
                'link'                      => 'Product URL',
                'condition'                 => 'Condition',
                'sku'                       => 'SKU',
                'availability'              => 'Availability',
                'quantity'                  => 'Quantity',
                'price'                     => 'Regular Price',
                'sale_price'                => 'Sale Price',
                'weight'                    => 'Weight',
                'width'                     => 'Width',
                'height'                    => 'Height',
                'length'                    => 'Length',
                'rating_total'              => 'Total Rating',
                'rating_average'            => 'Average Rating',
                'product_tags'              => 'Tags',
                'sale_price_dates_from'     => 'Sale Start Date',
                'sale_price_dates_to'       => 'Sale End Date',
                'sale_price_effective_date' => 'Sale Price Effective Date',
                'identifier_exists'         => 'Identifier Exists Calculator',

            ),

            'Image Attributes' => array(
                'featured_image' => 'Featured Image',
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

        //Get the Product Attributes
        global $wpdb;
        $sql = 'SELECT attribute_name as name, attribute_type as type FROM ' . $wpdb->prefix . 'woocommerce_attribute_taxonomies';
        $data = $wpdb->get_results($sql);
        if (count($data)) {
            foreach ($data as $key => $value) {
                $attr["bwf_attr_pa_" . $value->name] = $value->name;
            }
            $attributes['Product Attributes'] = $attr;
        }


        //Product Dynamic Attributes
        $list = array();
        $no_taxonomies = array("category","post_tag","nav_menu","link_category","post_format","product_type","product_visibility","product_cat","product_shipping_class","product_tag");
        $taxonomies = get_taxonomies();
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
//                            $attr_name = strtolower(str_replace(" ", "_",$kv));
                            $attr_name_clean = ucfirst($kv);
                        }
                    }
                }
            }

            $list["$attr_name"] = $attr_name_clean;
        }
        $attributes['Product Dynamic Attributes'] = $list;

        //custom attributes
        $list = array();
        $sql = "SELECT meta_key as name, meta_value as type FROM " . $wpdb->prefix . "postmeta" . "  group by meta_key";
        $data = $wpdb->get_results($sql);


        if (count($data)) {
            foreach ($data as $key => $value) {
//                if (substr($value->name, 0, 1) !== "_") {
                    if (!preg_match("/pyre|sbg|fusion|rex|woosea/i",$value->name)){
                        $value_display = str_replace("_", " ",$value->name);
                        $list["custom_attributes_" . $value->name] = ucfirst($value_display);
//                    }
                }
            }
        }
        $attributes['Product Custom Attributes'] = $list;




        //Category Mapping
        $cat_maps_array = array();
        $pattern = '/^rex_cat_map_[a-z0-9]+$/';
        $cat_maps = preg_grep($pattern, array_keys(wp_load_alloptions()));
        if($cat_maps){
            foreach ($cat_maps as $cat_map){
                $option = get_option($cat_map);
                $cat_maps_array["$cat_map"] = $option['map-name'];
            }
        }
        $attributes['Category Map'] = $cat_maps_array;
        return $attributes;
    }

}
