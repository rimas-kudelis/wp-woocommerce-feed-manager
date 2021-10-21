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
                echo "<div class='single-category'>";
                echo "<span class='label'>{$separator}{$cat->name} ({$cat->count})</span>";
                echo "<div class='input-field'><input class='autocomplete category-suggest' type='text' name='category-{$cat->term_id}' value='{$map_value}' placeholder='Google Merchant Category'></div>";
                echo "</div>";
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
					$skip_row                = get_post_meta( $feed_id, 'rex_feed_skip_row', true );

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
						'skip_row'                => $skip_row,
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


if ( ! function_exists( 'wpfm_is_wpml_active' ) ) {
	/**
	 * @desc check if wpml is active.
	 *
	 * @return bool
	 */
	function wpfm_is_wpml_active(){
		$active_plugings             = get_option( 'active_plugins' );
		$wpml                        = 'woocommerce-multilingual/wpml-woocommerce.php';
		$sitepress                   = 'sitepress-multilingual-cms/sitepress.php';
		$wpml_string_translation     = 'wpml-string-translation/plugin.php';
		$wpml_translation_management = 'wpml-translation-management/plugin.php';

		return in_array( $wpml, $active_plugings )
		       && in_array( $sitepress, $active_plugings )
		       && in_array( $wpml_string_translation, $active_plugings )
		       && in_array( $wpml_translation_management, $active_plugings );
	}
}