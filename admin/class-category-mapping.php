<?php



/**
 * Helper Class to retrieve Woo Product Category
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/partials/
 */



class CategoryMapping{

    public function get_category(){
        $args = array(
            'taxonomy'   => "product_cat",
            'hide_empty' => false,
        );
        $product_categories = get_terms($args);
        return $product_categories;
    }
}