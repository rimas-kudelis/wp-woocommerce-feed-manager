<?php

/**
 * The file that generates xml feed for any merchant with custom configuration.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Google
 * @subpackage Rex_Product_Feed_Google/includes
 * @author     RexTheme <info@rextheme.com>
 */

use RexTheme\RexShoppingFeedCustom\idealo\Containers\Idealo;

class Rex_Product_Feed_Idealo extends Rex_Product_Feed_Abstract_Generator {

    /**
     * Create Feed
     *
     * @return boolean
     * @author
     **/
    public function make_feed() {

        // Generate feed for both simple and variable products.
        $this->generate_product_feed();
        $this->feed = $this->returnFinalProduct();
        if ($this->batch >= $this->tbatch ) {
            $this->save_feed($this->feed_format);
            return array(
                'msg' => 'finish'
            );
        }else {
            return $this->save_feed($this->feed_format);
        }
    }


    protected function generate_product_feed(){
        $product_meta_keys = Rex_Feed_Attributes::get_attributes();
        $simple_products = [];
        $variation_products = [];
        $variable_parent = [];
        $group_products = [];
        //new
        $all_variation = [];
        $all_variation_distinct= [];
        $name='_name';

        $total_products = get_post_meta($this->id, 'rex_feed_total_products', true) ? get_post_meta($this->id, 'rex_feed_total_products', true) : array(
            'total' => 0,
            'simple' => 0,
            'variable' => 0,
            'variable_parent' => 0,
            'group' => 0,
        );

        if($this->batch == 1) {
            $total_products = array(
                'total' => 0,
                'simple' => 0,
                'variable' => 0,
                'variable_parent' => 0,
                'group' => 0,
            );
        }
        foreach( $this->products as $productId ) {
            $product = wc_get_product( $productId );
            if ( ! is_object( $product ) ) {
                continue;
            }
            if ( $this->exclude_hidden_products ) {
                if ( !$product->is_visible() ) {
                    continue;
                }
            }

            if ( $product->is_type( 'variable' ) && $product->has_child() ) {
                //get attribute name of variable product
                $parent_variation = [];
                $attr_label_name = [];
                $attr_val_name = [];

                $attribute = $product->get_variation_attributes();
                foreach ($attribute as $key=>$val){
                    $parent_variation[] = $key;
                    $attr_label_name[] = wc_attribute_label( $key );

                    $attr_vall = $product->get_attribute($key);
                    $attr_val_name[lcfirst($key)] = wc_attribute_label( $attr_vall );
                }

                $string_variation = implode("",  $attr_label_name );

                $variable_parent[] = $productId;
                $variable_product = new WC_Product_Variable($productId);
                $atts = $this->get_product_data( $variable_product, $product_meta_keys );

                $atts['parent_child'] = 'Parent';
                $atts['relationship_type'] = '';
                $atts['variation_theme'] =$string_variation;

                foreach ($parent_variation as $pv){
                    $all_variation[] = $pv;
                }
                $all_variation_distinct=array_unique($all_variation);

                foreach ($all_variation_distinct as $dv){
                    $attr_label = wc_attribute_label( $dv );
                    $label_final= $attr_label.$name;
                    $atts[lcfirst($label_final)] ='';
                }
                $intersect_array = array('product_id','title','category','product_URL','image_URL','brand_name',
                    'manufacturer', 'feed_product_type', 'variation_theme','parent_child');
                $item = Idealo::createItem();
                foreach ($atts as $key => $value) {
                    if(in_array($key, $intersect_array)) {
                        $item->$key($value); // invoke $key as method of $item object.
                    }else {
                        $item->$key(''); // invoke $key as method of $item object.
                    }
                }
                if ( $this->exclude_hidden_products ) {
                    $variations = $product->get_visible_children();
                }else {
                    $variations = $product->get_children();
                }
                if($variations) {
                    foreach ($variations as $variation) {
                        $product = wc_get_product($variation);

                        if($this->variations) {
                            $variation_products[] = $variation;
                            $item = Idealo::createItem();
                            $variation_product = wc_get_product( $variation );
                            $atts = $this->get_product_data( $variation_product, $product_meta_keys );
                            $atts['parent_child'] = 'Enfant';
                            $atts['relationship_type'] = 'Variation';
                            $atts['variation_theme'] =$string_variation;

                            foreach ($all_variation_distinct as $dv){
                                $attr_label = wc_attribute_label( $dv );//new
                                $label_final= $attr_label.$name;
                                $atts[lcfirst($label_final)] ='';
                                $dv= lcfirst($dv);
                                    $attr_val = $product->get_attribute($dv);
                                    if(!empty($attr_val)){
                                        $attr_label = wc_attribute_label( $dv );
                                        $label_final= $attr_label.$name;
                                        $atts[lcfirst($label_final)] =$attr_val;
                                    }else{
                                        $attr_label = wc_attribute_label( $dv );
                                        $label_final= $attr_label.$name;
                                        if(array_key_exists($dv,$attr_val_name)){
                                            $atts[lcfirst($label_final)] =str_replace('|', ',', $attr_val_name[$dv]);
                                        }
                                    }
                            }
                            foreach ($atts as $key => $value) {
                                $item->$key($value); // invoke $key as method of $item object.
                            }
                        }
                    }
                }

            }

            if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'composite' ) || $product->is_type( 'bundle' ) || $product->is_type( 'woosb' )) {
                $simple_products[] = $productId;
                $atts = $this->get_product_data( $product, $product_meta_keys );
                $atts['parent_child'] = '';
                $atts['relationship_type'] = '';
                $atts['variation_theme'] = '';
                foreach ($all_variation_distinct as $dv){
                    $attr_label = wc_attribute_label($dv);
                    $label_final= $attr_label.$name;
                    $atts[lcfirst($label_final)] ='';
                }
                $item = Idealo::createItem();
                foreach ($atts as $key => $value) {
                    $item->$key($value); // invoke $key as method of $item object.
                }
            }

        }

        $total_products = array(
            'total' => (int) $total_products['total'] + (int) count($simple_products) + (int) count($variation_products) + (int) count($group_products) + (int) count($variable_parent),
            'simple' => (int) $total_products['simple'] + (int) count($simple_products),
            'variable' => (int) $total_products['variable'] + (int) count($variation_products),
            'variable_parent' => (int) $total_products['variable_parent'] + (int) count($variable_parent),
            'group' => (int) $total_products['group'] + (int) count($group_products),
        );
        update_post_meta( $this->id, 'rex_feed_total_products', $total_products );
    }

    /**
     * Return Feed
     * @return array|bool|string
     */
    public function returnFinalProduct(){
        if($this->feed_format==='csv'){
            return Idealo::asCSVFeeds($this->batch);
        }elseif ($this->feed_format==='tsv'){
            return Idealo::asTSVFeeds($this->batch);
        }elseif ($this->feed_format==='text'){
            return Idealo::asTextFeeds($this->batch);
        }
    }

    public function footer_replace() {

    }
}
