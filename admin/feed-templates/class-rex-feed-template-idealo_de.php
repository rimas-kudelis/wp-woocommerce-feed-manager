<?php
/**
 * The Become Feed Template class.
 *
 * @link       https://rextheme.com
 * @since      1.1.7
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/
 */

/**
 *
 * Defines the attributes and template for idealo feed.
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/feed-templates/Rex_Feed_Template_Idealo
 * @author     RexTheme <info@rextheme.com>
 */
class Rex_Feed_Template_Idealo_de extends Rex_Feed_Abstract_Template
{

    protected function init_atts()
    {
        $this->attributes = array(
            'Required Information' => array(
                'identifieant_unique'    => 'Unique id',
                'titre'                  => 'Product name',
                'prix_ttc'               => 'Price Including Tax',
                'description'            => 'Description',
                'categorie'              => 'Category',
                'URL_produit'            => 'Product URL',
                'URL_image'              => 'Image URL',
                'EAN'                    => 'EAN',
                'marque'                 => 'Brand',
                'taille'                 => 'Size',
                'couleur'                => 'Color',
                'matiere'                => 'Material',
                'genre'                  => 'Genre',

            ) ,

            'Optional Information' => array(
                'prix_barre'            => 'Pricenorebate',
                'prix_solde'             => 'Sale Price',
                'sous_categorie1'        => 'Sub Category1',
                'sous_categorie2'        => 'Sub Category2',
                'sous_categorie3'        => 'Sub Category3',
                 'MPN'                   => 'MPN',
                'frais_de_livraison'     => 'Delivery Cost',
                'delais_de_livraison'    => 'Delivery time',
                'quantite_en_stock'      => 'Quantity in stock',
                'Disponibilite'          => 'Avaiablity',
                'Garantie'               => 'Guarantee',
                'poids'                  => 'Weight',
                'condition'              => 'Condition',
                'solde'                  => 'Sales',
                'promo-texte'            => 'Promo Text',
                'pourcentage_promo'      => 'Promo percentage',
                'date_de_debut_promo'    => 'Start Date Promo',
                'date_de_fin_promo'      => 'End date promo',
                'ecotaxe'                => 'Ecotax',
                'Devise'                 => 'Currency',

            ) ,

        );
    }

    protected function init_default_template_mappings()
    {
        $this->template_mappings = array(
            array(
                'attr'     => 'identifieant_unique',
                'type'     => 'meta',
                'meta_key' => 'id',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr' => 'titre',
                'type' => 'meta',
                'meta_key' => 'title',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
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
                'attr'     => 'categorie',
                'type'     => 'meta',
                'meta_key' => 'product_cats',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr' => 'URL_produit',
                'type' => 'meta',
                'meta_key' => 'link',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr' => 'URL_image',
                'type' => 'meta',
                'meta_key' => 'featured_image',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr' => 'EAN',
                'type' => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix' => '',
                'suffix' => '',
                'escape' => 'default',
                'limit' => 0,
            ) ,
            array(
                'attr'     => 'marque',
                'type'     => 'meta',
                'meta_key' => 'brand',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'taille',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'couleur',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'matiere',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'genre',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'prix_ttc',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            )

        );
    }

}
