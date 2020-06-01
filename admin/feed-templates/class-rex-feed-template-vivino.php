<?php
/**
 * The vivino marketplace Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      1.1.4
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */
/**
 *
 * Defines the attributes and template for vivino marketplace feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_vivino
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Vivino extends Rex_Feed_Abstract_Template
{

    protected function init_atts()
    {
        $this->attributes = array(
            'Required Information' => array(
                'bottles-quantity' => 'Bottles quantity',
                'bottles-size' => 'Bottles size',
                'inventory-count' => 'Inventory count',
                'link' => 'Link',
                'price' => 'Price',
                'product-name' => 'Product name',
                'wine-name' => 'Wine name',
            ) ,
            'Optional Information' => array(
                'acidity' => 'acidity',
                'ageing' => 'ageing',
                'alcohol' => 'alcohol',
                'appellation' => 'appellation',
                'certified-biodynamic' => 'certified-biodynamic',
                'certified-organic' => 'certified-organic',
                'closure' => 'closure',
                'color' => 'color',
                'contains-added-sulfites' => 'contains-added-sulfites',
                'contains-egg-allergens' => 'contains-egg-allergens',
                'contains-milk-allergens' => 'contains-milk-allergens',
                'country' => 'country',
                'decant-for' => 'decant-for',
                'description' => 'description',
                'drinking-temperature' => 'drinking-temperature',
                'drinking-years-from' => 'drinking-years-from',
                'drinking-years-to' => 'drinking-years-to',
                'importer-address' => 'importer-address',
                'kosher' => 'kosher',
                'meshuval' => 'meshuval',
                'non-alcoholic' => 'non-alcoholic',
                'ph' => 'ph',
                'price-discounted-from' => 'price-discounted-from',
                'price-discounted-until' => 'price-discounted-until',
                'producer' => 'producer',
                'producer-address' => 'producer-address',
                'product-id' => 'product-id',
                'production-size' => 'production-size',
                'residual-sugar' => 'residual-sugar',
                'sweetness' => 'sweetness',
                'varietal' => 'varietal',
                'vegan-friendly' => 'vegan-friendly',
                'vintage' => 'vintage',
                'winemaker' => 'winemaker',
            ) ,
        );
    }

    protected function init_default_template_mappings()
    {
        $this->template_mappings = array(
            array(
                'attr' => 'bottles-quantity',
                'type' => 'meta',
                'meta_key' => 'quantity',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr' => 'bottles-size',
                'type' => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr' => 'inventory-count',
                'type' => 'meta',
                'meta_key' => 'quantity',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr' => 'link',
                'type' => 'meta',
                'meta_key' => 'link',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr' => 'price',
                'type' => 'meta',
                'meta_key' => 'price',
                'st_value' => '',
                'prefix' => '',
                'suffix' => ' ' . get_option('woocommerce_currency') ,
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr' => 'product-name',
                'type' => 'meta',
                'meta_key' => 'title',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr' => 'wine-name',
                'type' => 'meta',
                'meta_key' => 'title',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            )
        );
    }

}
