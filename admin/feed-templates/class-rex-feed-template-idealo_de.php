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
            'Required Information'          => array(
                'sku'                    => 'SKU',
                'brand'                  => 'Brand',
                'title'                  => 'Title',
                'description'            => 'Description',
                'imageUrls'              => 'Image URL',
                'categoryPath'           => 'Category',
                'url'                    => 'Product URL',
                'price'                  => 'Price',
                'deliveryTime'           => 'Delivery Time',
                'checkout'               => 'Checkout',
                'fulfillmentType'        => 'Fulfillment Type',
                'checkoutLimitPerPeriod' => 'Checkout Limit',
                'eans'                   => 'EANS',
                'paymentCosts_paypal'    => 'Payment Costs [Paypal]',
                'deliveryCosts_dhl'      => 'Delivery Costs [DHL]',
            ),
            'Optional Information'          => array(
                'eec'                    => 'EEC',
                'merchantName'           => 'Merchant Name',
                'merchantId'             => 'Merchant ID',
                'basePrice'              => 'Base Price',
                'formerPrice'            => 'Former Price',
                'voucherCode'            => 'Voucher Code',
                'deposit'                => 'Deposit',
                'deliveryComment'        => 'Delivery Comment',
                'maxOrderProcessingTime' => 'Processing Time [Max]',
                'freeReturnDays'         => 'Free Return Days',
                'minimumPrice'           => 'Minimum Price',
                'quantityPerOrder'       => 'Quantity [Per Order]',
                'twoManHandlingFee'      => 'Handling Fee [Two Man]',
                'disposalFee'            => 'Disposal Fee',
                'packagingUnit'          => 'Packaging Unit',
                'used'                   => 'Used',
                'download'               => 'Download',
                'replica'                => 'Replica',
                'gender'                 => 'Gender',
                'material'               => 'Material',
            ),
            'Energy Label Information'      => array(
                'EEC_efficiencyClass' => 'EEC Efficiency Class',
                'EEC_spectrum'        => 'EEC Spectrum',
                'EEC_labelUrl'        => 'EEC Label URL',
                'EEC_dataSheetUrl'    => 'EEC Datasheet URL',
                'EEC_version'         => 'EEC Version',
            ),
            'Car Part Specific Information' => array(
                'oens' => 'OENS',
                'kbas' => 'KBAS',
            ),
            'Lenses Specific Information'   => array(
                'diopter'   => 'Diopter',
                'baseCurve' => 'Base Curve',
                'diameter'  => 'Diameter',
                'axis'      => 'Axis',
                'addition'  => 'Addition',
            ),
            'Speaker Specific Information'  => array(
                'quantity' => 'Quantity',
            ),
            'Wine Specific Information'     => array(
                'alcoholicContent'    => 'Alcoholic Content',
                'allergenInformation' => 'Allergen Information',
                'countryOfOrigin'     => 'Country of Origin',
                'quantity'            => 'Quantity',
                'bottler'             => 'Bottler',
                'importer'            => 'Importer',
            ),
            'Tyre Specific Information'     => array(
                'fuelEfficiencyClass'       => 'Fuel Efficiency Class',
                'wetGripClass'              => 'Wet Grip Class',
                'externalRollingNoise'      => 'External Rolling Noise',
                'externalRollingNoiseClass' => 'External Rolling Noise Class',
                'iceGrip'                   => 'Ice Grip',
                'EEC_labelUrl'              => 'EEC Label URL',
                'EEC_dataSheetUrl'          => 'EEC Datasheet URL',
            ),
            'Medical Specific Information'  => array(
                'pzns' => 'PZNS',
            ),
            'Payment Costs Information'     => array(
                'paymentCosts_credit_card'                   => 'Payment Costs [Credit Card]',
                'paymentCosts_cash_in_advance'               => 'Payment Costs [Cash in Advance]',
                'paymentCosts_cash_on_delivery'              => 'Payment Costs [Cash on Delivery]',
                'paymentCosts_cash_direct_debit'             => 'Payment Costs [Direct Debit]',
                'paymentCosts_giropay'                       => 'Payment Costs [Giropay]',
                'paymentCosts_google_checkout'               => 'Payment Costs [Google Checkout]',
                'paymentCosts_google_invoice'                => 'Payment Costs [Invoice]',
                'paymentCosts_google_postal_order'           => 'Payment Costs [Postal Order]',
                'paymentCosts_google_paysafecard'            => 'Payment Costs [Paysafecard]',
                'paymentCosts_google_sofortueberweisung'     => 'Payment Costs [Sofortueberweisung]',
                'paymentCosts_google_amazon_payment'         => 'Payment Costs [Amazon payment]',
                'paymentCosts_electronical_payment_standard' => 'Payment Costs [Electronical Payment Standard]',
                'paymentCosts_ecotax'                        => 'Payment Costs [Ecotax]',
            ),
            'Delivery Costs Information'    => array(
                'deliveryCosts_dhl_go_green'             => 'Delivery Costs [DHL Go Green]',
                'deliveryCosts_fedex'                    => 'Delivery Costs [FedEx]',
                'deliveryCosts_deutsche_post'            => 'Delivery Costs [Deutsche Post]',
                'deliveryCosts_download'                 => 'Delivery Costs [download]',
                'deliveryCosts_dpd'                      => 'Delivery Costs [DPD]',
                'deliveryCosts_german_express_logistics' => 'Delivery Costs [German Express Logistics]',
                'deliveryCosts_gls'                      => 'Delivery Costs [GLS]',
                'deliveryCosts_gls_think_green'          => 'Delivery Costs [GLS Think Green]',
                'deliveryCosts_hermes'                   => 'Delivery Costs [Hermes]',
                'deliveryCosts_pick_point'               => 'Delivery Costs [Pick Point]',
                'deliveryCosts_spedition'                => 'Delivery Costs [Spedition]',
                'deliveryCosts_tnt'                      => 'Delivery Costs [TNT]',
                'deliveryCosts_trans_o_flex'             => 'Delivery Costs [Trans O Flex]',
                'deliveryCosts_ups'                      => 'Delivery Costs [UPS]',
            ),
        );
    }

    protected function init_default_template_mappings()
    {
        $this->template_mappings = array(
            array(
                'attr'     => 'sku',
                'type'     => 'meta',
                'meta_key' => 'sku',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'brand',
                'type'     => 'static',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
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
                'attr'     => 'imageUrls',
                'type'     => 'meta',
                'meta_key' => 'main_image',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'categoryPath',
                'type'     => 'meta',
                'meta_key' => 'product_cats',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'url',
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
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'deliveryTime',
                'type'     => '',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'checkout',
                'type'     => '',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'checkoutLimitPerPeriod',
                'type'     => '',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'eans',
                'type'     => '',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'deliveryCosts_dhl',
                'type'     => '',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
            array(
                'attr'     => 'paymentCosts_paypal',
                'type'     => '',
                'meta_key' => '',
                'st_value' => '',
                'prefix'   => '',
                'suffix'   => '',
                'escape'   => 'default',
                'limit'    => 0,
            ),
        );
    }
}