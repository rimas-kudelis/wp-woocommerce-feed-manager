<?php

/**
 * Class for retriving product data based on user selected feed configuration.
 *
 * Get the product data based on feed config selected by user.
 *
 * @package    Rex_Product_Marktplaats_Data_Retriever
 * @subpackage Rex_Product_Feed/admin
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Product_Glami_Data_Retriever extends Rex_Product_Data_Retriever {


    /**
     * Retrive and setup all data for every feed rules.
     *
     * @since    2.5
     */
    public function set_all_value() {
        $this->data = array();
        foreach ($this->feed_rules as $key => $rule) {
            if($rule['attr'] === 'PARAM') {
                $value = $this->set_val( $rule );
                if($value) {
                    $searchVal = array("bwf_attr_pa_", "pa_", "custom_attributes_");
                    $name = str_replace($searchVal, '', $rule['meta_key']);
                    $this->data['PARAM'][] = array(
                        'name' => $name,
                        'value' => $value,
                    );
                }
            }
            elseif ($rule['attr'] === 'IMGURL_ALTERNATIVE') {
                $value = $this->set_val( $rule );
                if($value) {
                    $this->data[$rule['attr']][] = $value;
                }
            }
            else {
                $this->data[ $rule['attr'] ] = $this->set_val( $rule );
            }
        }
    }


    public function get_random_key() {
        return md5(uniqid(rand(), true));
    }
}