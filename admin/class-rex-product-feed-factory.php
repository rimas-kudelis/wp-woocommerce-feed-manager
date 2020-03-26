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

    public static function build( $config, $bypass = false ){
        $log = wc_get_logger();
        $context = array( 'source' => 'WPFM' );
        self::$other_merchants = apply_filters('wpfm_merchant_custom',
            array(
                'custom',
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
            )
        );

        if ( in_array( $config['merchant'], self::$other_merchants ) ) {
            $className = 'Rex_Product_Feed_Other';
        }else{
            $className = 'Rex_Product_Feed_'. ucfirst( str_replace(' ', '', $config['merchant'] ) );
        }

        if( $config == '' || ! class_exists( $className ) ) {
            $log->critical(__( 'Invalid Merchant.', 'rex-product-feed' ), array('source' => 'WPFM-Critical'));
            throw new Exception('Invalid Merchant.');
        } else {
            return new $className( $config, $bypass );
        }

        return false;
    }
}
