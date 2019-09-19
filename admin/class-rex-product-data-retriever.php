<?php

/**
 * Class for retriving product data based on user selected feed configuration.
 *
 * Get the product data based on feed config selected by user.
 *
 * @package    Rex_Product_Data_Retriever
 * @subpackage Rex_Product_Feed/admin
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Product_Data_Retriever {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    protected $feed_rules;

    /**
     * Contains all available meta keys for products.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    protected $product_meta_keys;

    /**
     * The data of product retrived by feed_rules.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $data    The current version of this plugin.
     */
    protected $data;


    /**
     * Metabox instance of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $metabox    The current metabox of this plugin.
     */
    protected $product;

    /**
     * Variant atts for feed.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $metabox    The current metabox of this plugin.
     */
    protected $variant_atts = array( 'color', 'pattern', 'material', 'age_group', 'gender', 'size', 'size_type', 'size_system' );

    /**
     * Additional images of current product.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $metabox    The current metabox of this plugin.
     */
    protected $additional_images = array();


    /**
     * Append variation
     *
     * @since    3.2
     * @access   private
     * @var      object    $append_variation
     */
    protected $append_variation;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $product, $feed_rules, $wpml = null, $append_variation = 'no' ) {

        $this->product           = wc_get_product( $product );

//        $this->allowed = Rex_Product_Filter::allowedProduct($this->product, $feed_filter_rules);

        $this->feed_rules        = $feed_rules;
        $this->product_meta_keys = Rex_Feed_Attributes::get_attributes();
        $this->append_variation = $append_variation;


        // $this->set_test_feed_rules(); // only for testing purpose of all atts values;
        $this->set_all_value();
//        $this->maybe_set_variation_data();
    }


    /**
     * Setup Testing feed rules for every attributes.
     * Just to check if this class return proper values.
     *
     * @since    1.0.2
     */
    public function set_test_feed_rules() {
        $this->feed_rules = array();

        foreach ($this->product_meta_keys as $key_cat => $attrs) {
            foreach ($attrs as $key => $attr) {
                $this->feed_rules[] = array(
                    'attr'     => $key,
                    'type'     => 'meta',
                    'meta_key' => $key,
                    'st_value' => '',
                    'prefix'   => '',
                    'suffix'   => '',
                    'escape'   => 'default',
                    'limit'    => 0,
                );
            }
        }

    }

    /**
     * Retrive and setup all data for every feed rules.
     *
     * @since    1.0.0
     */
    public function set_all_value() {
        $this->data = array();
        foreach ($this->feed_rules as $key => $rule) {
            $this->data[ $rule['attr'] ] = $this->set_val( $rule );
        }
    }


    /**
     * Set value for a single feed rule.
     *
     * @since    1.0.0
     */
    public function set_val( $rule ) {
        $val = '';

        if ( 'static' === $rule['type'] ) {
            $val = $rule['st_value'];

        }elseif ( 'meta' === $rule['type'] && $this->is_primary_attr( $rule['meta_key'] ) ) {
            $val = $this->set_pr_att( $rule['meta_key']  );

        }elseif ( 'meta' === $rule['type'] && $this->is_image_attr( $rule['meta_key'] ) ) {

            $val = $this->set_image_att( $rule['meta_key']  );

        }
        elseif ( 'meta' === $rule['type'] && $this->is_product_attr( $rule['meta_key'] ) ) {

            $val = $this->set_product_att( $rule['meta_key']  );

        }
        elseif ( 'meta' === $rule['type'] && $this->is_product_dynamic_attr( $rule['meta_key'] ) ) {
            $val = $this->set_product_dynamic_att( $rule['meta_key']  );

        }
        elseif ( 'meta' === $rule['type'] && $this->is_product_custom_attr( $rule['meta_key'] ) ) {
            $val = $this->set_product_custom_att( $rule['meta_key']  );
        }
        elseif ( 'meta' === $rule['type'] && $this->is_product_category_mapper_attr( $rule['meta_key'] ) ) {
            $val = $this->set_cat_mapper_att( $rule['meta_key']  );
        }


        // maybe add prefix/suffix
        $val = $this->maybe_add_prefix_suffix($val, $rule);
        // maybe escape
        $val = $this->maybe_escape($val, $rule['escape']);
        // maybe limit
        $val = $this->maybe_limit($val, $rule['limit']);

        return $val;

    }

    /**
     * Return all data.
     *
     * @since    1.0.0
     */
    public function get_all_data() {
        return $this->data;
    }

    /**
     * Set a primary attribute.
     *
     * @since    1.0.0
     */
    protected function set_pr_att( $key ) {

        switch ( $key ) {

            case 'id':
                return $this->product->get_id(); break;

            case 'sku':
                return $this->product->get_sku(); break;

            case 'title':
                if($this->append_variation === 'no') {
                    return $this->product->get_name();
                }else {
                    if ($this->is_children()) {
                        $_product = wc_get_product( $this->product );
                        $_variations = $_product->get_attributes();
                        if(count($_variations) > 2) {
                            $_title = $this->product->get_title() . " - ";
                            foreach($_variations as $key => $value){
                                $_title = $_title . " " . ucfirst($value);
                            }
                            return $_title;
                        }else {
                            return $this->product->get_name();
                        }
                    }else {
                        return $this->product->get_name();
                    }

                }
                break;

            case 'price':
                if ($this->product->is_type( 'grouped' ))
                    return number_format((float)$this->get_grouped_price($this->product, 'regular'), 2, '.', '');
                return number_format((float)$this->product->get_regular_price(), 2, '.', '');
                break;

            case 'sale_price':

                if ($this->product->is_type( 'grouped' ))
                    return number_format((float)$this->get_grouped_price($this->product, 'sale'), 2, '.', '');
                return $this->product->get_sale_price() ? number_format((float)$this->product->get_sale_price(), 2, '.', ''): '';
                break;


            case 'description':
                if(($this->is_children())):
                    $_product = wc_get_product( $this->product->get_parent_id() );
                    $_product_desc =  $this->remove_short_codes($_product->get_description());
                    return $_product_desc;
                else:
                    return $this->remove_short_codes($this->product->get_description());
                endif;
                break;

            case 'short_description':
                if(($this->is_children())):
                    $_product = wc_get_product( $this->product->get_parent_id() );
                    $_product_desc = $this->remove_short_codes($_product->get_short_description());
                    return $_product_desc;
                else:
                    return $this->remove_short_codes($this->product->get_short_description()) ;
                endif;
                break;

            case 'product_cats':
                return $this->get_product_cats(); break;

            case 'product_tags':
                return $this->get_product_tags(); break;

            case 'link':
                return $this->product->get_permalink(); break;

            case 'condition':
                return $this->get_condition(); break;

            case 'availability':
                return $this->get_availability(); break;

            case 'quantity':
                return $this->product->get_stock_quantity(); break;

            case 'weight':
                return $this->product->get_weight(); break;

            case 'width':
                return $this->product->get_width(); break;

            case 'height':
                return $this->product->get_height(); break;

            case 'length':
                return $this->product->get_length(); break;

            case 'shipping_class':
                return $this->product->get_shipping_class(); break;

            case 'type':
                return $this->product->get_type(); break;

            case 'rating_average':
                return $this->product->get_average_rating(); break;

            case 'rating_total':
                return $this->product->get_rating_count(); break;

            case 'sale_price_dates_from':
                return date( get_option( 'date_format' ), $this->product->get_date_on_sale_from() ); break;

            case 'sale_price_dates_to':
                return date( get_option( 'date_format' ), $this->product->get_date_on_sale_to() ); break;

            case 'sale_price_effective_date':


                $sale_price_dates_to        = ( $date = get_post_meta( $this->product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
                $sale_price_dates_from      = ( $date = get_post_meta( $this->product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';

                if ( ! empty( $sale_price_dates_to ) && ! empty( $sale_price_dates_from ) ) {
                    $from   = date( "c", strtotime( $sale_price_dates_from ) );
                    $to     = date( "c", strtotime( $sale_price_dates_to ) );


                    return $from . '/' . $to;
                }else {
                    return '';
                }

            case 'identifier_exists':
                return $this->calculate_identifier_exists($this->data);

            default: return ''; break;
        }
    }


    /**
     * Set a Image attribute.
     *
     * @since    1.0.0
     */
    protected function set_image_att( $key ) {
        switch ( $key ) {
            case 'featured_image':
                return wp_get_attachment_url(  $this->product->get_image_id() ); break;

            default: return $this->get_additional_image( $key ); break;
        }
    }

    /**
     * Set a Product attribute.
     *
     * @since    1.0.0
     */
    protected function set_product_att( $key ) {
        if ( 'WC_Product_Variation' != get_class($this->product) ) {
            return;
        }
        $variant_atts = $this->product->get_variation_attributes();
        $key = str_replace( 'bwf_attr_pa_', 'attribute_pa_', $key);
        if(array_key_exists($key, $variant_atts)){
            return $variant_atts[$key];
        }
        return '';
    }

    /**
     * Set a Product Dynamic attribute.
     *
     * @since    1.0.0
     */
    protected function set_product_dynamic_att( $key ) {

        if ( 'WC_Product_Variation' == get_class($this->product) ) {
            $attr_name = $this->get_product_dynamic_tags($this->product->get_parent_id(), $key);
        } else{

            $attr_name = $this->get_product_dynamic_tags($this->product->get_id(), $key);
        }
        if($attr_name){
            return $attr_name;
        }
        return '';
    }

    /**
     * Set a Product Custom attribute.
     *
     * @since    1.0.0
     */
    protected function set_product_custom_att( $key ) {

        $new_key = str_replace('custom_attributes_', '', $key);

        if ( 'WC_Product_Variation' == get_class($this->product) ) {

            if($new_key === '_wpfm_product_brand') {
                $attr_name = get_post_meta($this->product->get_parent_id(), $new_key, true);
            }else {
                $attr_name = get_post_meta($this->product->get_id(), $new_key, true);
            }

        } else{
            $attr_name = get_post_meta($this->product->get_id(), $new_key, true);
        }

        if($attr_name){
            return $attr_name;
        }
        return '';
    }


    /**
     * Set Product Category Map
     *
     * @since    3.0
     */
    protected function set_cat_mapper_att( $key ) {
        $first_cat = array();
        if ( 'WC_Product_Variation' == get_class($this->product) ) {
            $cat_lists = get_the_terms( $this->product->get_parent_id(), 'product_cat' );
        } else{
            $cat_lists = get_the_terms( $this->product->get_id(), 'product_cat' );
        }
        if($cat_lists){
            $first_cat = reset($cat_lists);
        }

        $wpfm_category_map = get_option('rex-wpfm-category-mapping');
        if($wpfm_category_map) {
            $map = $wpfm_category_map[$key];
            $map_config = $map['map-config'];
            if($first_cat){
                foreach ($map_config as $key => $value){
                    if( $first_cat->term_id == $value['map-key']){
                        $map_value = $value['map-value'];
                        preg_match("~^(\d+)~", $map_value, $m);
                        return utf8_decode(urldecode($m[1]));
                    }
                }
            }
        }
        return '';
    }


    /**
     * Get additional image url by key.
     *
     * @since    1.0.0
     */
    protected function get_additional_image( $key ) {

        if ( empty( $this->additional_images ) ) {
            $this->set_additional_images();
        }

        if ( array_key_exists( $key, $this->additional_images ) ) {
            return $this->additional_images[$key];
        }

        return '';

    }

    /**
     * Retrieve a product's categories as a list with specified format.
     *
     * @param string $before Optional. Before list.
     * @param string $sep Optional. Separate items using this.
     * @param string $after Optional. After list.
     * @return string|false
     */
    protected function get_product_cats( $before = '', $sep = ', ', $after = '' ) {
        if ( 'WC_Product_Variation' == get_class($this->product) ) {
            return $this->get_the_term_list( $this->product->get_parent_id(), 'product_cat', $before, $sep, $after );
        }else {
            return $this->get_the_term_list( $this->product->get_id(), 'product_cat', $before, $sep, $after );
        }

    }

    /**
     * Retrieve a product's tags as a list with specified format.
     *
     *
     * @param string $before Optional. Before list.
     * @param string $sep Optional. Separate items using this.
     * @param string $after Optional. After list.
     * @return string|false
     */
    protected function get_product_tags( $before = '', $sep = ', ', $after = '' ) {

        if ( 'WC_Product_Variation' == get_class($this->product) ) {
            return $this->get_the_term_list( $this->product->get_parent_id(), 'product_tag', $before, $sep, $after );
        }else {
            return $this->get_the_term_list( $this->product->get_id(), 'product_tag', $before, $sep, $after );
        }
    }

    /**
     * Retrieve a product's dynamic attributes as a list with specified format.
     *
     *
     * @param string $before Optional. Before list.
     * @param string $sep Optional. Separate items using this.
     * @param string $after Optional. After list.
     * @return string|false
     */
    protected function get_product_dynamic_tags( $id, $key, $before = '', $sep = ', ', $after = '' ) {
        return $this->get_the_term_list($id, $key, $before, $sep, $after );
    }

    /**
     * Retrieve a product's terms as a list with specified format.
     *
     *
     * @param int $id Product ID.
     * @param string $taxonomy Taxonomy name.
     * @param string $before Optional. Before list.
     * @param string $sep Optional. Separate items using this.
     * @param string $after Optional. After list.
     * @return string|false
     */
    protected function get_the_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {
        $terms = get_the_terms( $id, $taxonomy );

        if ( empty( $terms ) || is_wp_error( $terms ) ){
            return '';
        }

        $term_names = array();

        foreach ( $terms as $term ) {
            $term_names[] = $term->name;
        }

        ksort($term_names);

        return $before . join( $sep, $term_names ) . $after;
    }

    /**
     * Set additional images url.
     *
     * @since    1.0.0
     */
    protected function set_additional_images() {

        $img_ids = $this->product->get_gallery_image_ids();

        $images = array();
        if ( ! empty( $img_ids ) ) {

            foreach ($img_ids as $key => $img_id) {
                $img_key = 'image_' . ($key+1);
                $images[$img_key] = wp_get_attachment_url($img_id);
            }

            // set images to the property
            $this->additional_images = $images;
        }

    }

    /**
     * Helper to check if a attribute is a Primary Attribute.
     *
     * @since    1.0.0
     */
    protected function is_primary_attr( $key ) {
        return array_key_exists( $key, $this->product_meta_keys['Primary Attributes'] );
    }

    /**
     * Helper to check if a attribute is a Image Attribute.
     *
     * @since    1.0.0
     */
    protected function is_image_attr( $key ) {
        return array_key_exists( $key, $this->product_meta_keys['Image Attributes'] );
    }

    /**
     * Helper to check if a attribute is a Product Attribute.
     *
     * @since    1.0.0
     */
    protected function is_product_attr( $key ) {
        return array_key_exists( $key, $this->product_meta_keys['Product Attributes'] );
    }


    /**
     * Helper to check if a attribute is a Product dynamic Attribute.
     *
     * @since    1.0.0
     */
    protected function is_product_dynamic_attr( $key ) {

        return array_key_exists( $key, $this->product_meta_keys['Product Dynamic Attributes'] );
    }


    /**
     * Helper to check if a attribute is a Product Custom Attribute.
     *
     * @since    1.0.0
     */
    protected function is_product_custom_attr( $key ) {
        return array_key_exists( $key, $this->product_meta_keys['Product Custom Attributes'] );
    }

    /**
     * Helper to check if a attribute is a Category Mapper.
     *
     * @since    1.0.0
     */
    protected function is_product_category_mapper_attr( $key ) {
        return array_key_exists( $key, $this->product_meta_keys['Category Map'] );
    }


    /**
     * Helper to get condtion of a product.
     *
     * @since    1.0.0
     */
    protected function get_condition( ) {
        return 'New';
    }


    /**
     * Get grouped price
     *
     * @since    2.0.3
     */
    public function get_grouped_price($product, $type) {
        $groupProductIds = $product->get_children();
        $sum = 0;
        if(!empty($groupProductIds)){

            foreach($groupProductIds as $id){
                $product = wc_get_product($id);
                $regularPrice=$product->get_regular_price();
                $currentPrice=$product->get_price();
                if($type == "regular"){
                    $sum += $regularPrice;
                }else{
                    $sum += $currentPrice;
                }
            }
        }

        return $sum;
    }



    /**
     * Helper to get availability of a product
     *
     * @since    1.0.0
     */
    protected function get_availability( ) {
        if ( $this->product->is_in_stock() == TRUE ) {
            return 'in stock';
        } else {
            return 'out of stock';
        }
    }

    /**
     * Add neccessary prefix/suffix to a value.
     *
     * @since    1.0.0
     */
    protected function maybe_add_prefix_suffix($val, $rule) {
        $prefix =  $rule['prefix'];
        $suffix =  $rule['suffix'];

        if ( !empty( $prefix ) ) {
            $val = $val ? $prefix . $val : '';
        }

        if ( !empty( $suffix ) ) {
            $val = $val ? $val . $suffix : '';
        }

        return $val;
    }

    /**
     * Escape a value with specific escape method.
     *
     * @since    1.0.0
     */
    protected function maybe_escape($val, $escape) {
        switch ($escape){
            case 'strip_tags':
                return strip_tags($val);
            case 'utf_8_encode':
                return utf8_encode($val);
            case 'htmlentities':
                return htmlentities($val);
            case 'integer':
                return intval($val);
            case 'price':
                return intval($val);
            case 'remove_space':
                return preg_replace('/\s+/', '', $val);;
            case 'remove_shortcodes':
                return strip_shortcodes( $val );
            case 'remove_special':
                return filter_var($val, FILTER_SANITIZE_STRING);;
            case 'cdata':
                return $val ? "<![CDATA [$val]]>" : $val;
            default: return $val; break;
        }
    }


    /**
     * Limit the output chars to specified length.
     *
     * @since    1.0.0
     */
    protected function maybe_limit($val, $limit) {
        $limit = (int) $limit;
        if ( $limit > 0) {
            return substr($val, 0, $limit);
        }
        return $val;
    }

    /**
     * Setup variation data if current product is a variable product.
     *
     * @since    1.0.0
     */
    protected function maybe_set_variation_data() {

        if ( 'WC_Product_Variation' != get_class($this->product) ) {
            return;
        }

        $variant_atts = $this->product->get_variation_attributes();

        foreach ($variant_atts as $key => $value) {
            $key = str_replace( 'attribute_pa_', '', $key);

            if( in_array($key, $this->variant_atts) ){
                $this->data[$key] = $value;
            }
        }

    }


    /**
     * Remove shortcode
     * from content
     *
     * @param $content
     * @return string
     * @since    2.0.3
     */

    public function remove_short_codes($content) {
        if(empty($content)){
            return "";
        }
        $content = $this->remove_invalid_xml($content);
        return strip_shortcodes($content);
    }



    /**
     * Removes invalid XML
     *
     * @param string $value
     * @return string
     */
    public function remove_invalid_xml($value) {

        $ret = "";
        $current = "";
        if (empty($value)) {
            return $ret;
        }

        $length = strlen($value);
        for ($i=0; $i < $length; $i++) {
            $current = ord($value{$i});
            if (($current == 0x9) ||
                ($current == 0xA) ||
                ($current == 0xD) ||
                (($current >= 0x20) && ($current <= 0xD7FF)) ||
                (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                (($current >= 0x10000) && ($current <= 0x10FFFF)))
            {
                $ret .= chr($current);
            }
            else
            {
                $ret .= " ";
            }
        }
        return $ret;

    }





    /**
     * calculate the value of identifier_exists
     *
     * @return string
     * @since    1.2.5
     */

    public function calculate_identifier_exists ($data) {

        $identifier_exists = "no";

        if (array_key_exists("brand", $data) AND ($data['brand'] != "")){
            if ((array_key_exists("gtin", $data)) AND ($data['gtin'] != "")){
                $identifier_exists = "yes";
            } elseif ((array_key_exists("mpn", $data)) AND ($data['mpn'] != "")){
                $identifier_exists = "yes";
            } else {
                $identifier_exists = "no";
            }
        } else {
            if ((array_key_exists("gtin", $data)) AND ($data['gtin'] != "")){
                $identifier_exists = "no";
            } elseif ((array_key_exists("mpn", $data)) AND ($data['mpn'] != "")){
                $identifier_exists = "no";
            } else {
                $identifier_exists = "no";
            }
        }

        return $identifier_exists;
    }


    /**
     * Check if this product is child product or not
     *
     * @return bool
     * @since    1.0.0
     */
    protected function is_children(){
        return $this->product->get_parent_id() ? true: false;
    }



    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }



}