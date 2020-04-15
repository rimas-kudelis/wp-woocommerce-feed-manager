<?php
/**
 * The Rex_Product_Feed_Factory class file that
 * returns a feed generator object based on selected merchant.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Factory
 * @subpackage Rex_Product_Feed_Factory/includes
 */
class Rex_Product_Feed_Factory {

    private static $other_merchants;

    private static $google_format;

    private static $facebook_format;

    public static function build( $config, $bypass = false , $product_ids = array()){
        $log = wc_get_logger();
        $context = array( 'source' => 'WPFM' );
        self::$other_merchants = apply_filters('wpfm_merchant_custom',
            array(
                'adform',
                'adcrowd',
                'beslist',
                'cdiscount',
                'custom',
                'heureka',
                'kieskeurig',
                'kleding',
                'ladenzeile',
                'skroutz',
                'winesearcher',
                'whiskymarketplace',
                'trovaprezzi',
                'rss',
                'nextag',
                'pricegrabber',
                'bing',
                'kelkoo',
                'amazon',
                'ebay',
                'become' ,
                'shopzilla',
                'shopping',
                'google_Ad',
                'adroll',
                'admarkt',
                'pricerunner',
                'billiger',
                'vergelijk',
                'twenga',
                'tweakers',
                'koopkeus',
                'scoupz',
                'kelkoonl',
                'uvinum',
                'idealo',
                'rakuten',
                'pricesearcher',
                'pricemasher',
                'google_dsa',
                'fashionchick',
                'choozen',
                'prisjkat',
                'crowdfox',
                'powerreviews',
                'otto',
                'sears',
                'ammoseek',
                'fnac',
                'pixmania',
                'coolblue',
                'shopmania',
                'preis',
                'walmart',
                'snapchat',
                'verizon',
                'kelkoo_group',
                'target',
                'pepperjam',
                'cj_affiliate',
                'guenstiger',
                'hood',
                'livingo',
                'jet',
                'bonanza',
                'adcell',
                'zbozi',
                'stylefruits',
                'medizinfuchs',
                'moebel',
                'restposten',
                'sparmedo',
                'newegg',
                '123i',
                'bikeexchange',
                'cenowarka',
                'cezigue',
                'check24',
                'zalando',
                'clang',
                'cherchons',
                'boetiek',
                'comparer',
                'converto',
                'coolshop',
                'commerce_connector',
                'everysize',
                'encuentraprecios',
                'geizhals',
                'geizkragen',
                'giftboxx',
                'go_banana',
                'goed_geplaatst',
                'grosshandel',
                'hardware',
                'hatch',
                'hintaopas',
                'fyndiq',
                'fasha',
                'realde',
                'hintaseuranta',
                'family_blend',
                'hitmeister',
                'lazada',
                'get_price',
                'home_tiger',
                'jurkjes',
                'kiesproduct',
                'kiyoh',
                'kompario',
                'kwanko',
                'ledenicheur',
                'les_bonnes_bouilles',
                'lions_home',
                'locamo',
                'logicsale',
                'google_manufacturer_center',
                'pronto',
                'awin',
                'google_dynamic_display_ads',
                'indeed',
                'incurvy',
                'jobbird',
                'job_board_io',
                'joblift',
                'kuantokusta',
                'kauftipp',
                'vivino',
            )
        );

        self::$google_format = array(
            'google',
            'ciao',
            'liveintent',
            'pinterest',
        );

        self::$facebook_format = array(
            'instagram',
        );

        if ( in_array( $config['merchant'], self::$other_merchants ) ) {
            $className = 'Rex_Product_Feed_Other';
        }
        elseif (in_array( $config['merchant'], self::$google_format )) {
            $className = 'Rex_Product_Feed_Google';
        }
        elseif (in_array( $config['merchant'], self::$facebook_format )) {
            $className = 'Rex_Product_Feed_Facebook';
        }
        else{
            $className = 'Rex_Product_Feed_'. ucfirst( str_replace(' ', '', $config['merchant'] ) );
        }

        if( $config == '' || ! class_exists( $className ) ) {
            if(is_wpfm_logging_enabled()) {
                $log->critical(__( 'Invalid Merchant.', 'rex-product-feed' ), array('source' => 'WPFM-Critical'));
            }
            throw new Exception('Invalid Merchant.');
        } else {
            return new $className( $config, $bypass, $product_ids );
        }

        return false;
    }
}
