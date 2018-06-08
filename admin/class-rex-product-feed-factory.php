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

    private static $other_merchants = array( 'custom', 'nextag', 'pricegrabber', 'bing', 'kelkoo', 'amazon', 'ebay', 'become' , 'shopzilla', 'shopping');

    public static function build( $config ){

        if ( in_array( $config['merchant'], self::$other_merchants ) ) {
            $className = 'Rex_Product_Feed_Other';
        }else{
            $className = 'Rex_Product_Feed_'. ucfirst( str_replace(' ', '', $config['merchant'] ) );
        }

        if( $config == '' || ! class_exists( $className ) ) {
            throw new Exception('Invalid Merchant.');
        } else {
            return new $className( $config );
        }

        return false;
    }
}
