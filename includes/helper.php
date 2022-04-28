<?php
if ( ! function_exists( 'wpfm_hierarchical_product_category_tree' ) ) {
    /**
     * Print hierarchical product categories
     *
     * @param $cat
     * @param array $config
     */
    function wpfm_hierarchical_product_category_tree( $cat, $config = array() ) {
        $args = array(
            'parent' 	=> $cat,
            'hide_empty'    => false,
            'no_found_rows' => true,
        );

        $next = get_terms('product_cat', $args);
        $separator = '';
        if( $next ) :
            foreach( $next as $cat ) :
                if($cat->parent !== 0){
                    $separator = '--';
                }
                $map_value = '';
                if(!empty($config)) {
                    $key = array_search($cat->term_id, array_column($config, 'map-key'));
                    if($key !== false) {
                        $map_value = $config[$key]['map-value'];
                    }
                }


                ob_start();?>
                <div class='single-category'>
                    <span class='label'><?php echo esc_html( $separator.$cat->name ) .' ('. esc_html( $cat->count ) . ')' ?></span>
                    <div class='input-field'><input class='autocomplete category-suggest' type='text' name='category-<?php echo esc_attr($cat->term_id); ?>' value='<?php echo esc_attr($map_value); ?>' placeholder='Google Merchant Category'/></div>
                </div>
                <?php echo ob_get_clean();

                $separator = '';
                wpfm_hierarchical_product_category_tree( $cat->term_id, $config );
            endforeach;
        endif;
    }
}


if ( ! function_exists( 'is_wpfm_logging_enabled' ) ) {
    /**
     * Check if logging is enabled or not
     *
     * @return bool
     */
    function is_wpfm_logging_enabled() {
        $enable_log = get_option('wpfm_enable_log', 'no') == 'yes' ? true : false;
        return $enable_log;
    }
}


if( !function_exists('wpfm_get_feed_list') ) {
    /**
     * Get all feed lists
     *
     * @param $schedule
     * @return int[]|WP_Post[]
     */
    function wpfm_get_feed_list($schedule) {
        $args = array(
            'post_type'      => 'product-feed',
            'post_status'    => array('publish'),
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query'     => array(
                array(
                    'key'      => 'rex_feed_schedule',
                    'value'    => $schedule,
                )
            ),
        );
        $query = new WP_Query( $args );
        return $query->get_posts();
    }
}


if( !function_exists('wpfm_run_schedule_update') ) {
    /**
     * Run schedule update for
     * feeds
     *
     * @param $feeds
     * @param string $schedule
     */
	function wpfm_run_schedule_update( $feeds, $schedule = 'hourly' )
	{
		$count      = 0;
		$batch_size = 20;
		if ( $feeds ) {
			$total_feeds = count( $feeds );
			foreach ( $feeds as $key => $feed_id ) {
				$products_info = Rex_Product_Feed_Ajax::get_product_number( array() );
				$per_batch     = $products_info[ 'per_batch' ];
				$total_batches = $products_info[ 'total_batch' ];
				$offset        = 0;
				$terms_array   = array();

				for ( $i = 1; $i <= $total_batches; $i++ ) {
					if ( $i === 1 ) update_post_meta( $feed_id, 'rex_feed_status', 'processing' );
					if ( $i === $total_batches ) update_post_meta( $feed_id, 'rex_feed_status', 'completed' );

					$merchant                = get_post_meta( $feed_id, 'rex_feed_merchant', true );
					$feed_config             = get_post_meta( $feed_id, 'rex_feed_feed_config', true );
					$feed_filter             = get_post_meta( $feed_id, 'rex_feed_feed_config_filter', true );
					$product_scope           = get_post_meta( $feed_id, 'rex_feed_products', true );
					$include_variations      = get_post_meta( $feed_id, 'rex_feed_variations', true ) === 'yes';
					$variable_product        = get_post_meta( $feed_id, 'rex_feed_variable_product', true ) === 'yes';
					$parent_product          = get_post_meta( $feed_id, 'rex_feed_parent_product', true ) === 'yes';
					$exclude_hidden_products = get_post_meta( $feed_id, 'rex_feed_hidden_products', true ) === 'yes';
					$append_variations       = get_post_meta( $feed_id, 'rex_feed_variation_product_name', true ) === 'yes';
					$wpml                    = get_post_meta( $feed_id, 'rex_feed_wpml_language', true ) ? get_post_meta( $feed_id, 'rex_feed_wpml_language', true ) : '';
					$feed_format             = get_post_meta( $feed_id, 'rex_feed_feed_format', true ) ?
						get_post_meta( $feed_id, 'rex_feed_feed_format', true ) : 'xml';
					$aelia_currency          = get_post_meta( $feed_id, 'rex_feed_aelia_currency', true );
					$wmc_currency            = get_post_meta( $feed_id, 'rex_feed_wmc_currency', true );
					$skip_row                = get_post_meta( $feed_id, 'rex_feed_skip_row', true );
					$feed_separator          = get_post_meta( $feed_id, 'rex_feed_separator', true );

					if ( $product_scope !== 'all' && $product_scope !== 'filter' ) {
						$terms = wp_get_post_terms( $feed_id, $product_scope );
						if ( $terms ) {
							foreach ( $terms as $term ) {
								$terms_array[] = $term->slug;
							}
						}
					}

					$payload = array(
						'merchant'                => $merchant,
						'feed_format'             => $feed_format,
						'feed_config'             => $feed_config,
						'append_variations'       => $append_variations,
						'info'                    => array(
							'post_id'        => $feed_id,
							'title'          => get_the_title( $feed_id ),
							'desc'           => get_the_title( $feed_id ),
							'total_batch'    => $total_batches,
							'batch'          => $i,
							'per_page'       => $per_batch,
							'offset'         => $offset,
							'products_scope' => $product_scope,
							'cats'           => $terms_array,
							'tags'           => $terms_array,
						),
						'feed_filter'             => $feed_filter,
						'include_variations'      => $include_variations,
						'variable_product'        => $variable_product,
						'parent_product'          => $parent_product,
						'exclude_hidden_products' => $exclude_hidden_products,
						'wpml_language'           => $wpml,
						'aelia_currency'          => $aelia_currency,
						'wmc_currency'            => $wmc_currency,
						'skip_row'                => $skip_row,
						'feed_separator'          => $feed_separator,
					);
					try {
						$merchant = Rex_Product_Feed_Factory::build( $payload, true );
						$merchant->make_feed();
						$offset += (int) $per_batch;
					}
					catch ( Exception $e ) {
						$log = wc_get_logger();
						$log->critical( $e->getMessage(), array( 'source' => 'wpfm-error' ) );
					}
				}
				$count++;
			}
		}
	}
}


if(!function_exists('wpfm_get_cached_data')) {
    /**
     * get wpfm transient by key
     *
     * @param $key
     * @return bool
     */
    function wpfm_get_cached_data( $key ) {
        if ( empty( $key ) ) {
            return false;
        }
        return get_transient( '_wpfm_cache_' . $key );
    }
}


if(!function_exists('wpfm_set_cached_data')) {
    /**
     * set wpfm transient by key
     *
     * @param $key
     * @param $value
     * @param int $expiration
     * @return bool
     */
    function wpfm_set_cached_data( $key, $value, $expiration = 0 ) {
        if ( empty( $key ) ) {
            return false;
        }
        if (!$expiration) $expiration = get_option( 'wpfm_cache_ttl', 3 * HOUR_IN_SECONDS );
        return set_transient( '_wpfm_cache_' . $key, $value, $expiration );
    }
}


if ( ! function_exists( 'wpfm_purge_cached_data' ) ) {
    function wpfm_purge_cached_data() {
        global $wpdb;
        $wpdb->query( "DELETE FROM $wpdb->options WHERE ({$wpdb->options}.option_name LIKE '_transient_timeout__wpfm_cache%') OR ({$wpdb->options}.option_name LIKE '_transient__wpfm_cache_%')" ); // phpcs:ignore
    }
}


if ( ! function_exists( 'wpfm_replace_special_char' ) ) {
    function wpfm_replace_special_char( $feed ) {
    	return str_replace(
		    array('&#8226;', '&#8221;', '&#8220;', '&#8217;', '&#8216;', '&trade;', '&amp;trade;', '&reg;', '&amp;reg;', '&deg;', '&amp;deg;', '&#xA9;', ''),
		    array('•', '”', '“', '’', '‘', '™', '™', '®', '®', '°', '°', '©', "\n"),
		    $feed
	    );
    }
}


if ( ! function_exists( 'wpfm_is_aelia_active' ) ) {
	/**
	 * @desc check if aelia is active.
	 *
	 * @return bool
     * @since 7.0.0
	 */
	function wpfm_is_aelia_active(){
		$active_plugings         = get_option( 'active_plugins' );
		$aelia_plugin            = 'woocommerce-aelia-currencyswitcher/woocommerce-aelia-currencyswitcher.php';
		$aelia_foundation_plugin = 'wc-aelia-foundation-classes/wc-aelia-foundation-classes.php';

		return in_array( $aelia_plugin, $active_plugings ) && in_array( $aelia_foundation_plugin, $active_plugings );
	}
}


if ( ! function_exists( 'wpfm_is_wpml_active' ) ) {
	/**
	 * @desc check if wpml is active.
	 *
	 * @return bool
     * @since 7.0.0
	 */
	function wpfm_is_wpml_active(){
		$active_plugings             = get_option( 'active_plugins' );
		$wpml                        = 'woocommerce-multilingual/wpml-woocommerce.php';
		$sitepress                   = 'sitepress-multilingual-cms/sitepress.php';
		$wpml_string_translation     = 'wpml-string-translation/plugin.php';

		return in_array( $wpml, $active_plugings )
		       && in_array( $sitepress, $active_plugings )
		       && in_array( $wpml_string_translation, $active_plugings );
	}
}


if ( ! function_exists( 'wpfm_is_yoast_active' ) ) {
	/**
	 * @desc check if YOAST is active.
	 *
	 * @return bool
     * @since 7.0.0
	 */
	function wpfm_is_yoast_active(){
		$active_plugings = get_option( 'active_plugins' );
		$yoast           = 'wordpress-seo/wp-seo.php';

		return in_array( $yoast, $active_plugings );
	}
}


if ( ! function_exists( 'wpfm_is_wmc_active' ) ) {
	/**
	 * @desc check if WooCommerce Multicurrency plugin is active.
	 *
	 * @return bool
     * @since 7.0.0
	 */
	function wpfm_is_wmc_active(){
		$active_plugings = get_option( 'active_plugins' );
		$wmc           = 'woocommerce-multi-currency/woocommerce-multi-currency.php';

		return in_array( $wmc, $active_plugings );
	}
}


if ( ! function_exists( 'wpfm_generate_csv_feed' ) ) {
	/**
	 * Generates CSV format
	 *
	 * @param $feed
	 * @param $file
	 * @param $separator
	 * @param $batch
	 * @return string
     * @since 7.0.0
	 */
	function wpfm_generate_csv_feed( $feed, $file, $separator, $batch ){
        $list = $feed;
        $list = is_array( $list ) ? $list : array();

        if ( $batch == 1 ) {
            if ( file_exists( $file ) ) {
                unlink( $file );
            }
        }
        else {
            array_shift( $list );
        }

        $file = fopen( $file, "a+" );

        foreach ( $list as $line ) {
            $lines = array();
            foreach ( $line as $l ) {
                $lines[] = wpfm_replace_special_char( $l );
            }

            if ( $separator === 'semi_colon' ) {
                fputcsv( $file, $lines, ';' );
            }
            elseif ( $separator === 'pipe' ) {
                fputcsv( $file, $lines, '|' );
            }
            else {
                fputcsv( $file, $lines );
            }
        }
        fclose( $file );

        return 'true';
	}
}


if ( ! function_exists( 'wpfm_get_merchant_lits' ) ) {
	/**
	 * Gets all the merchant lists [free and pro]
	 * @return array[][]
     * @since 7.0.0
	 */
	function wpfm_get_merchant_lits(){
        $popular = array(
            'custom'    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Custom'
            ),
            'google'    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Shopping'
            ),
            'facebook'  => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Facebook'
            ),
            'instagram' => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Instagram (by Facebook)'
            ),
            'pinterest' => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pinterest'
            ),
            'snapchat'  => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Snapchat'
            ),
            'bing'      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Bing'
            ),
            'yandex'    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Yandex'
            ),
            'rakuten'   => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Rakuten'
            ),
            'vivino'    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Vivino'
            ),
        );
        $pro     = array(
            'google_review' => array(
                'free'   => false,
                'status' => 1,
                'name'   => 'Google Review'
            ),
            'ebay_mip'      => array(
                'free'   => false,
                'status' => 1,
                'name'   => 'eBay (MIP)'
            ),
            'drm'           => array(
                'free'   => false,
                'status' => 1,
                'name'   => 'Google Remarketing (DRM)'
            ),
            'leguide'       => array(
                'free'   => false,
                'status' => 1,
                'name'   => 'Leguide'
            ),
        );
        $free    = array(
            'google_custom_search_ads'        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Custom Search Ads'
            ),
            'google_Ad'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Dynamic Display Ads'
            ),
            'google_local_products'           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Local Products'
            ),
            'google_local_products_inventory' => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Local Products Inventory'
            ),
            'google_merchant_promotion'       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Merchant Promotion Feed'
            ),
            'google_dsa'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Dynamic Search Ads'
            ),
            'google_shopping_actions'         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Shopping Actions'
            ),
            'adroll'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'AdRoll'
            ),
            'nextag'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Nextag'
            ),
            'pricegrabber'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pricegrabber'
            ),
            'cercavino'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Cercavino'
            ),
            'trovino'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Trovino'
            ),
            'bing_image'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Bing Image'
            ),
            'kelkoo'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kelkoo'
            ),
            'become'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Become'
            ),
            'shopzilla'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'ShopZilla'
            ),
            'shopping'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Shopping'
            ),
            'pricerunner'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'PriceRunner'
            ),
            'billiger'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Billiger'
            ),
            'vergelijk'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Vergelijk'
            ),
            'marktplaats'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Marktplaats'
            ),
            'beslist'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Beslist'
            ),
            'daisycon'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Daisycon'
            ),
            'twenga'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Twenga'
            ),
            'kieskeurig'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kieskeurig.nl'
            ),
            'spartoo'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Spartoo.nl'
            ),
            'spartooFr'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'SpartooFr'
            ),
            'tweakers'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Tweakers.nl'
            ),
            'sooqr'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Sooqr'
            ),
            'koopkeus'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Koopkeus'
            ),
            'scoupz'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Scoupz'
            ),
            'cdiscount'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Cdiscount'
            ),
            'kelkoonl'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kelkoo.nl'
            ),
            'uvinum'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Uvinum / DrinsksAndCo'
            ),
            'idealo'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Idealo'
            ),
            'pricesearcher'                   => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pricesearcher'
            ),
            'pricemasher'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pricemasher'
            ),
            'fashionchick'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Fashionchick'
            ),
            'ceneo'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Ceneo'
            ),
            'choozen'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Choozen'
            ),
            'rss'                             => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'RSS'
            ),
            'ciao'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Ciao'
            ),
            'prisjkat'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pricespy/Prisjkat'
            ),
            'crowdfox'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Crowdfox'
            ),
            'powerreviews'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'PowerReviews'
            ),
            'trovaprezzi'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Trovaprezzi'
            ),
            'zbozi'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Zbozi'
            ),
            'liveintent'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'LiveIntent'
            ),
            'skroutz'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Skroutz'
            ),
            'otto'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Otto'
            ),
            'sears'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Sears'
            ),
            'ammoseek'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'AmmoSeek'
            ),
            'fnac'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Fnac'
            ),
            'zalando'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Zalando'
            ),
            'zalando_stock_update'            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Zalando Stock Update'
            ),
            'pixmania'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pixmania'
            ),
            'coolblue'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Coolblue'
            ),
            'shopmania'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'ShopMania'
            ),
            'kleding'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kleding'
            ),
            'ladenzeile'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Ladenzeile'
            ),
            'preis'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Preis'
            ),
            'winesearcher'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Winesearcher'
            ),
            'walmart'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Walmart'
            ),
            'verizon'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Yahoo/Verizon Dynamic Product Ads'
            ),
            'kelkoo_group'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kelkoo Group'
            ),
            'target'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Target'
            ),
            'pepperjam'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pepperjam'
            ),
            'cj_affiliate'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'CJ Affiliate'
            ),
            'guenstiger'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Guenstiger'
            ),
            'hood'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Hood'
            ),
            'livingo'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Livingo'
            ),
            'jet'                             => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Jet'
            ),
            'bonanza'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Bonanza'
            ),
            'adcell'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Adcell'
            ),
            'adform'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Adform'
            ),
            'stylefruits'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Stylefruits'
            ),
            'medizinfuchs'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Medizinfuchs'
            ),
            'moebel'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Moebel'
            ),
            'restposten'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Restposten'
            ),
            'sparmedo'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Sparmedo'
            ),
            'whiskymarketplace'               => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Whiskymarketplace'
            ),
            'newegg'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'NewEgg'
            ),
            '123i'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => '123I'
            ),
            'adcrowd'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Adcrowd'
            ),
            'bikeexchange'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Bike Exchange'
            ),
            'cenowarka'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Cenowarka'
            ),
            'cezigue'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Cezigue'
            ),
            'check24'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Check24'
            ),
            'clang'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Clang'
            ),
            'cherchons'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Cherchons'
            ),
            'boetiek'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Boetiek B.V'
            ),
            'comparer'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Comparer'
            ),
            'converto'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Converto'
            ),
            'coolshop'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Coolshop'
            ),
            'commerce_connector'              => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Commerce Connector'
            ),
            'everysize'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Everysize'
            ),
            'encuentraprecios'                => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Encuentraprecios'
            ),
            'geizhals'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Geizhals'
            ),
            'geizkragen'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Geizkragen'
            ),
            'giftboxx'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Giftboxx'
            ),
            'go_banana'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Go Banana'
            ),
            'goed_geplaatst'                  => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Goed Geplaatst'
            ),
            'grosshandel'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Grosshandel'
            ),
            'hardware'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Hardware.info'
            ),
            'hatch'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Hatch'
            ),
            'hintaopas'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Hintaopas'
            ),
            'fyndiq'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Fyndiq.se'
            ),
            'fasha'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Fasha'
            ),
            'realde'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Real.de'
            ),
            'hintaseuranta'                   => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Hintaseuranta'
            ),
            'family_blend'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Family Blend'
            ),
            'hitmeister'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Hitmeister'
            ),
            'lazada'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Lazada'
            ),
            'get_price'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'GetPrice.com.au'
            ),
            'home_tiger'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'HomeTiger'
            ),
            'jurkjes'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Jurkjes.nl'
            ),
            'kiesproduct'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kiesproduct'
            ),
            'kiyoh'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kiyoh'
            ),
            'kompario'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kompario'
            ),
            'kwanko'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kwanko'
            ),
            'ledenicheur'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Le Dénicheur'
            ),
            'les_bonnes_bouilles'             => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Les Bonnes Bouilles'
            ),
            'lions_home'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Lions Home'
            ),
            'locamo'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Locamo'
            ),
            'logicsale'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Logicsale'
            ),
            'google_manufacturer_center'      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Manufacturer Center'
            ),
            'pronto'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pronto'
            ),
            'awin'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Awin'
            ),
            'indeed'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Indeed'
            ),
            'incurvy'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Incurvy'
            ),
            'jobbird'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Jobbird'
            ),
            'job_board_io'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'JobBoard.io'
            ),
            'joblift'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Joblift'
            ),
            'kuantokusta'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'KuantoKusta'
            ),
            'kauftipp'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Kauftipp'
            ),
            'rakuten_advertising'             => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Rakuten Advertising'
            ),
            'pricefalls'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Pricefalls Feed'
            ),
            'clubic'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Clubic'
            ),
            'criteo'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Criteo'
            ),
            'shopalike'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Shopalike'
            ),
            'compartner'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Compartner'
            ),
            'adtraction'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Adtraction'
            ),
            'admitad'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Admitad'
            ),
            'bloomville'                      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Bloomville'
            ),
            'datatrics'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Datatrics'
            ),
            'deltaprojects'                   => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Delta Projects'
            ),
            'drezzy'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Drezzy'
            ),
            'domodi'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Domodi'
            ),
            'doofinder'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Doofinder'
            ),
            'homebook'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Homebook.pl'
            ),
            'homedeco'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Home Deco'
            ),
            'glami'                           => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Glami'
            ),
            'fashiola'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Fashiola'
            ),
            'emarts'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Emarts'
            ),
            'epoq'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Epoq'
            ),
            'grupo_zap'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Grupo Zap'
            ),
            'emag'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Emag'
            ),
            'lyst'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Lyst'
            ),
            'listupp'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Listupp'
            ),
            'hertie'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Hertie'
            ),
            'webgains'                        => array(
                'free'   => true,
                'status' => 1,
                'name'   => ' Webgains'
            ),
            'vidaXL'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'VidaXL'
            ),
            'mydeal'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'My Deal'
            ),
            'idealo_de'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Idealo.de'
            ),
            'favi'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Favi - Compari & Árukereső'
            ),
            'ibud'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Ibud'
            ),
            'google_local_inventory_ads'      => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Google Local Inventory Ads'
            ),
            'DealsForU'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Deals4u.gr'
            ),
            'Bestprice'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Bestprice'
            ),
            'mirakl'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Mirakl'
            ),
            'lesitedumif'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Lesitedumif'
            ),
            'shopee'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Shopee'
            ),
            'gulog_gratis'                    => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'GulogGratis.dk'
            ),
            'ebay_seller'                     => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'eBay Seller Center'
            ),
            'ebay_seller_tickets'             => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'eBay Seller Center (Event tickets)'
            ),
            'fruugo'                          => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Fruugo'
            ),
            'bol'                             => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Bol.com'
            ),
            'connexity'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Connexity'
            ),
            'heureka'                         => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Heureka'
            ),
            'wish'                            => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Wish.com'
            ),
            'zap_co_il'                       => array(
                'free'   => true,
                'status' => 1,
                'name'   => 'Zap.co.il'
            ),
        );

		$merchants[ 'popular' ]        = $popular;
		$merchants[ 'pro_merchants' ]  = $pro;
		$merchants[ 'free_merchants' ] = $free;

		return apply_filters('rex_wpfm_all_merchant',$merchants);
	}
}


if ( ! function_exists( 'wpfm_get_merchant_dropdown' ) ) {
	/**
	 * Prints merchant dropdown
	 * @param $class
	 * @param $id
	 * @param $name
	 * @param $selected
     * @since 7.0.0
	 */
	function wpfm_print_merchant_dropdown( $class, $id, $name, $selected ){
		$all_merchants[''] = array(
			'-1'    => array(
				'free'   => true,
				'status' => 1,
				'name'   => 'Please select a merchant'
			),
		);
		$all_merchants = array_merge( $all_merchants, wpfm_get_merchant_lits());
		$is_premium    = apply_filters( 'wpfm_is_premium', false );

		echo '<select class="' .esc_attr( $class ). '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '">';
		foreach ($all_merchants as $groupLabel => $group) {
			if ( !empty($groupLabel)) {
				if ( $groupLabel === 'popular' ) {
					$groupLabel = 'Popular Merchants';
				}
				elseif ( $groupLabel === 'pro_merchants' ) {
					$groupLabel = 'Pro Merchants';
				}
				elseif ( $groupLabel === 'free_merchants' ) {
					$groupLabel = 'Others';
				}
				$disabled = ( $groupLabel === 'Pro Merchants' && !$is_premium ) ? 'disabled' : '';
                ob_start();?>
                <optgroup label='<?php echo esc_html($groupLabel); ?>' <?php echo esc_html($disabled); ?>>
                <?php echo ob_get_clean();
			}

			foreach ($group as $key => $item) {
				$value = $item['name'];

				if ( $selected == $key ) {
                    ob_start();?>
                        <option value='<?php echo esc_attr($key); ?>' selected='selected'><?php echo esc_html($value); ?></option>
                    <?php echo ob_get_clean();
				}else{
				    ob_start();?>
                        <option value='<?php echo esc_attr($key); ?>'><?php echo esc_html($value); ?></option>
                    <?php echo ob_get_clean();
				}
			}

			if ( !empty($groupLabel)) {
				echo "</optgroup>";
			}
		}

		echo "</select>";
	}
}


if ( ! function_exists( 'wpfm_purge_browser_cache' ) ) {
	/**
	 * Clear browser cache
     * @since 7.0.0
	 */
	function wpfm_purge_browser_cache(){
        header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
	}
}


if ( ! function_exists( 'wpfm_switch_site_lang' ) ) {
	/**
	 * Switches site language to the given language
	 */
	function wpfm_switch_site_lang( $language ){
        if ( wpfm_is_wpml_active() ) {
            global $sitepress;
            $sitepress->switch_lang( $language );
        }
	}
}


if ( ! function_exists( 'rex_feed_get_roll_back_versions' ) ) {
    /**
     * get rollback version of WPFM
     *
     * @return array|mixed
     *
     * @src Inspired from Elementor roll back options
     */
    function rex_feed_get_roll_back_versions() {
        $rollback_versions = get_transient( 'rex_feed_rollback_versions_' . WPFM_VERSION );
        if ( false === $rollback_versions ) {
            $max_versions = 5;
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            $plugin_information = plugins_api(
                'plugin_information', [
                    'slug' => WPFM_SLUG,
                ]
            );
            if ( empty( $plugin_information->versions ) || ! is_array( $plugin_information->versions ) ) {
                return [];
            }

            krsort( $plugin_information->versions );

            $rollback_versions = [];

            $current_index = 0;
            foreach ( $plugin_information->versions as $version => $download_link ) {
                if ( $max_versions <= $current_index ) {
                    break;
                }

                $lowercase_version = strtolower( $version );
                $is_valid_rollback_version = ! preg_match( '/(trunk|beta|rc|dev)/i', $lowercase_version );

                /**
                 * Is rollback version is valid.
                 *
                 * Filters the check whether the rollback version is valid.
                 *
                 * @param bool $is_valid_rollback_version Whether the rollback version is valid.
                 */
                $is_valid_rollback_version = apply_filters(
                    'rex_feed_is_valid_rollback_version',
                    $is_valid_rollback_version,
                    $lowercase_version
                );

                if ( ! $is_valid_rollback_version ) {
                    continue;
                }

                if ( version_compare( $version, WPFM_VERSION, '>=' ) ) {
                    continue;
                }

                $current_index++;
                $rollback_versions[] = $version;
            }

            set_transient( 'rex_feed_rollback_versions_' . WPFM_VERSION, $rollback_versions, WEEK_IN_SECONDS );
        }

        return $rollback_versions;
    }
}


if ( ! function_exists( 'rex_feed_get_default_variable_attributes' ) ) {
    /**
     * Get variable product default attributes
     * @param $product
     * @return mixed
     */
    function rex_feed_get_default_variable_attributes( $product )
    {
        if( method_exists( $product, 'get_default_attributes' ) ) {
            return $product->get_default_attributes();
        }
        else {
            return $product->get_variation_default_attributes();
        }
    }
}


if ( ! function_exists( 'rex_feed_find_matching_product_variation' ) ) {
    /**
     * Get matching variation
     *
     * @param $product
     * @param $attributes
     * @return mixed
     * @throws Exception
     */
    function rex_feed_find_matching_product_variation( $product, $attributes )
    {
        foreach( $attributes as $key => $value ) {
            if( strpos( $key, 'attribute_' ) === 0 ) {
                continue;
            }
            unset( $attributes[ $key ] );
            $attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
        }
        if( class_exists( 'WC_Data_Store' ) ) {
            $data_store = WC_Data_Store::load( 'product' );
            return $data_store->find_matching_product_variation( $product, $attributes );
        }
        else {
            return $product->get_matching_variation( $attributes );
        }
    }
}


if ( ! function_exists( 'rex_feed_get_product_price' ) ) {
    /**
     * Gets product price
     *
     * @param $product
     * @return int|mixed|string
     * @throws Exception
     */
    function rex_feed_get_product_price( $product )
    {
        if( $product->is_type( 'variable' ) ) {
            $default_variations = rex_feed_get_default_variable_attributes( $product );
            if( $default_variations ) {
                $variation_id = rex_feed_find_matching_product_variation( $product, $default_variations );
                if( $variation_id ) {
                    $_variation_product = wc_get_product( $variation_id );
                    return $_variation_product->get_regular_price();
                }
            }
            else {
                return $product->get_variation_regular_price();
            }
        }
        elseif( $product->is_type( 'grouped' ) ) {
            return rex_feed_get_grouped_price( $product, '_regular_price' );
        }
        elseif( $product->is_type( 'composite' ) ) {
            return $product->get_composite_regular_price();
        }
        elseif( $product->is_type( 'bundle' ) ) {
            return $product->get_bundle_price();
        }

        return $product->get_regular_price();
    }
}


if ( ! function_exists( 'rex_feed_get_grouped_price' ) ) {
    /**
     * Get grouped price
     *
     * @since    2.0.3
     */
    function rex_feed_get_grouped_price( $product, $type )
    {
        $groupProductIds = $product->get_children();
        $price           = 99999999;

        if( !empty( $groupProductIds ) ) {
            foreach( $groupProductIds as $id ) {
                if( get_post_meta( $id, $type, true ) !== '' ) {
                    $price = $price > get_post_meta( $id, $type, true ) ? get_post_meta( $id, $type, true ) : $price;
                }
            }
            if( $price === 99999999 ) {
                $price = '';
            }
        }
        return $price;
    }
}


if ( !function_exists( 'rex_feed_get_sanitized_get_post' ) ) {
    /**
     * Gets sanitized $_GET and $_POST data or given data
     * @return array
     */
    function rex_feed_get_sanitized_get_post( $data = [] )
    {
        if ( is_array( $data ) && !empty( $data ) ) {
            return filter_var_array( $data, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        }
        return array(
            'get' => filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'post' => filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'request' => filter_var_array( $_REQUEST, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        );
    }
}


/**
 * Clears cache after updating plugin to a newer version.
 */
if ( get_option( 'wpfm_major_update' ) !== wpfm_get_plugin_version( '/best-woocommerce-feed/rex-product-feed.php' ) ) {
	add_action( 'plugin_loaded', 'wpfm_purge_cached_data' );
	add_action( 'plugin_loaded', 'wpfm_purge_browser_cache' );
    update_option( 'wpfm_major_update', wpfm_get_plugin_version( '/best-woocommerce-feed/rex-product-feed.php' ) );
}