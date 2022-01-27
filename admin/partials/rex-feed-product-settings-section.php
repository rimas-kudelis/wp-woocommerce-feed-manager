<?php $icon_question = 'icon/icon-svg/icon-question.php'; ?>

<div class="rex-contnet-setting-area">

	<div class="rex-contnet-setting__header">
		<div class="rex-contnet-setting__header-text">
			<div class="rex-contnet-setting__icon rex-contnet__header-text">
				<?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/icon-setting.php';?>
				<?php echo '<h2>' . __( 'Settings', 'rex-product-feed' ) . '</h2>';?>
			</div>
		</div>
		<span class="rex-contnet-setting__close-icon close-btn">
			Close
        </span>
	</div>

	<div class="rex-contnet-setting-content-area">
		<div class="<?php echo $this->prefix . 'schedule';?>">
			<label for="<?php echo $this->prefix . 'schedule';?>"><?php _e('Auto-Generate Your Feed', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'Set auto-update to keep your feed in sync with WooCommerce', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'schedule';?>">
				<?php
				$index = 1;
				$prev_value = get_post_meta( get_the_ID(), 'rex_feed_schedule', true );
				$prev_value = $prev_value !== '' ? $prev_value : 'no';
				foreach( $schedules as $key => $value ) {
					$checked = $key === $prev_value ? ' checked="checked"' : '';
					echo '<li>';
					echo '<input type="radio" id="'. $this->prefix . 'schedule' . $index . '" name="'. $this->prefix . 'schedule' . '" value="'. $key .'" ' . $checked . '>';
					echo '<label for="'. $this->prefix . 'schedule' . $index++ . '">'.__($value, 'rex-product-feed').'</label>';
					echo '</li>';
				}
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'include_out_of_stock';?> ">
			<label for="<?php echo $this->prefix . 'include_out_of_stock';?>">
				<?php _e('Include Out of Stock Products', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'This option will include/exclude out of stock products from feed', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'include_out_of_stock';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_include_out_of_stock', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'yes';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'include_out_of_stock' . '" value="yes" id="'. $this->prefix . 'include_out_of_stock2' .'" ' . $checked_yes .'>';
				echo '<label for="'. $this->prefix . 'include_out_of_stock2' .'">'.__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'include_out_of_stock' . '" value="no" id="'. $this->prefix . 'include_out_of_stock1' .'" ' . $checked_no .'>';
				echo '<label for="'. $this->prefix . 'include_out_of_stock1' .'">'.__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'variable_product';?> ">
			<label for="<?php echo $this->prefix . 'variable_product';?>">
				<?php _e('Include Variable Parent Product (Without Variations)', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'Include Variable Parent Product (Without Variations)', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'variable_product';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_variable_product', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'variable_product' . '" value="yes" id="'. $this->prefix . 'variable_product2' .'" ' . $checked_yes .'>';
				echo '<label for="'. $this->prefix . 'variable_product2' .'">'.__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'variable_product' . '" value="no" id="'. $this->prefix . 'variable_product1' .'" ' . $checked_no .'>';
				echo '<label for="'. $this->prefix . 'variable_product1' .'">'.__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'variations';?>">
			<label for="<?php echo $this->prefix . 'variations';?>"><?php _e('Include All Variable Products Variations', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
					<p>
                        <?php
                        _e( 'Include all the Variable Products Variations in your feed (these are only the product variations)', 'rex-product-feed' );
                        echo "<br>";
                        _e( 'Example:', 'rex-product-feed' );
                        echo "<br>";
                        echo esc_html( '<g:title>', 'rex-product-feed' );
                        echo "<br>";
                        echo esc_html( '<![CDATA[ V-Neck T-Shirt]]>', 'rex-product-feed' );
                        echo "<br>";
                        echo esc_html( '</g:title>', 'rex-product-feed' );
                        echo "<br>";
                        echo esc_html( '<g:link>', 'rex-product-feed' );
						echo "<br>";
                        echo esc_html( '<![CDATA[ http://URL/]]>', 'rex-product-feed' );
                        echo "<br>";
                        echo esc_html( '</g:link>' , 'rex-product-feed');
                        echo "<br>";
                        ?>
                    </p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'variations';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_variations', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'yes';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'variations' . '" value="yes" id="'. $this->prefix . 'variations2' .'" ' . $checked_yes .'>';
				echo '<label for="'. $this->prefix . 'variations2' .'">'.__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'variations' . '" value="no" id="'. $this->prefix . 'variations1' .'" ' . $checked_no .'>';
				echo '<label for="'. $this->prefix . 'variations1' .'">'.__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'variation_product_name';?>">
			<label for="<?php echo $this->prefix . 'variation_product_name';?>"><?php _e('Include Variation Name In The Product Title', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
					<p>
                        <?php
                        _e( 'Include the variation name in the product title', 'rex-product-feed' );
                        echo "<br>";
                        _e( 'Example:', 'rex-product-feed' );
                        echo "<br>";
                        echo esc_html( '<g:title>', 'rex-product-feed' );
                        echo "<br>";
                        echo esc_html( '<![CDATA[ V-Neck T-Shirt - Red ]]>', 'rex-product-feed' );
                        echo "<br>";
                        echo esc_html( '</g:title>', 'rex-product-feed' );
                        ?>
                    </p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'variation_product_name';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_variation_product_name', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'variation_product_name' . '" value="yes" id="'. $this->prefix . 'variation_product_name2' .'" ' . $checked_yes .'>';
				echo '<label for="'. $this->prefix . 'variation_product_name2' .'">'.__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'variation_product_name' . '" value="no" id="'. $this->prefix . 'variation_product_name1' .'" ' . $checked_no .'>';
				echo '<label for="'. $this->prefix . 'variation_product_name1' .'">'.__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'parent_product';?>">
			<label for="<?php echo $this->prefix . 'parent_product';?>"><?php _e('Include Grouped Products', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'Enable this option to include grouped products in your feed', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'parent_product';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_parent_product', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'yes';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'parent_product' . '" value="yes" id="'. $this->prefix . 'parent_product2' .'" ' . $checked_yes .'>';
				echo '<label for="'. $this->prefix . 'parent_product2' .'">'.__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'parent_product' . '" value="no" id="'. $this->prefix . 'parent_product1' .'" ' . $checked_no .'>';
				echo '<label for="'. $this->prefix . 'parent_product1' .'">'.__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'hidden_products';?>">
			<label for="<?php echo $this->prefix . 'hidden_products';?>"><?php _e('Exclude Invisible/Hidden Products', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'Enable this option to exclude invisible/hidden products from your feed', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'hidden_products';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_hidden_products', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'hidden_products' . '" value="yes" id="'. $this->prefix . 'hidden_products2' .'" ' . $checked_yes .'>';
				echo '<label for="'. $this->prefix . 'hidden_products2' .'">'.__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'hidden_products' . '" value="no" id="'. $this->prefix . 'hidden_products1' .'" ' . $checked_no .'>';
				echo '<label for="'. $this->prefix . 'hidden_products1' .'">'.__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'skip_product';?>">
			<label for="<?php echo $this->prefix . 'skip_product';?>"><?php _e('Skip products with empty value', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'This option will remove products if there is a single attribute with empty value', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'skip_product';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_skip_product', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'skip_product' . '" value="yes" id="'. $this->prefix . 'skip_product2' .'" ' . $checked_yes .'>';
				echo '<label for="'. $this->prefix . 'skip_product2' .'">'.__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'skip_product' . '" value="no" id="'. $this->prefix . 'skip_product1' .'" ' . $checked_no .'>';
				echo '<label for="'. $this->prefix . 'skip_product1' .'">'.__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'skip_row';?>">
			<label for="<?php echo $this->prefix . 'skip_row';?>"><?php _e('Skip attributes with empty value', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'This option will remove any attribute with empty value (XML feed format only)', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'skip_row';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_skip_row', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'skip_row' . '" value="yes" id="'. $this->prefix . 'skip_row2' .'" ' . $checked_yes .'>';
				echo '<label for="'. $this->prefix . 'skip_row2' .'">'.__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . $this->prefix . 'skip_row' . '" value="no" id="'. $this->prefix . 'skip_row1' .'" ' . $checked_no .'>';
				echo '<label for="'. $this->prefix . 'skip_row1' .'">'.__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

        <?php
        if( wpfm_is_wpml_active() ) {
	        global $sitepress, $woocommerce_wpml;
	        $wcml_settings   = get_option( '_wcml_settings' );
	        $wcml_currencies = isset( $wcml_settings[ 'currency_options' ] ) ? $wcml_settings[ 'currency_options' ] : array();
	        $currencies      = array();

	        foreach ($wcml_currencies as $key => $value) {
		        $currencies[$key] = $key;
	        }

	        if( is_array($currencies )) {
		        reset($currencies);
	        }
        ?>

        <div class="<?php echo $this->prefix . 'wcml_currency';?>">
            <label for="<?php echo $this->prefix . 'wcml_currency';?>"><?php _e('WCML Currency', 'rex-product-feed')?>
                <i class="fa fa-question-circle" aria-hidden="true"></i>
            </label>
            <select name="<?php echo $this->prefix . 'wcml_currency';?>" id="<?php echo $this->prefix . 'wcml_currency';?>" class="">
				<?php
				$selected_price = get_post_meta( get_the_ID(), 'rex_feed_wcml_currency', true );
				foreach( $currencies as $key => $value ) {
					$selected = $selected_price === $key ? ' selected' : '';
					echo '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
				}
				?>
            </select>
        </div>
        <?php } ?>

		<?php
		if ( wpfm_is_aelia_active() ) {
			$aelia_settings = get_option( 'wc_aelia_currency_switcher' );
			$enabled_currency = is_array( $aelia_settings ) && isset( $aelia_settings[ 'enabled_currencies' ] )
				? $aelia_settings[ 'enabled_currencies' ] : '';
			$aelia_world_currency = get_woocommerce_currencies();
			$aelia_world_currency = is_array( $aelia_world_currency ) ? $aelia_world_currency : array();
			$currency_options = array();

			if ( is_array( $enabled_currency ) && !empty( $enabled_currency ) ) {
				foreach ( $enabled_currency as $currency ) {
					if( array_key_exists( $currency, $aelia_world_currency) ){
						$currency_options[ $currency ] = $aelia_world_currency[ $currency ];
					}
				}
			}
			else{
				$currency_options = array( 'Please configure Aelia Currency Switcher!' );
			}
			?>
			<div class="<?php echo $this->prefix . 'aelia_currency';?>">
				<label for="<?php echo $this->prefix . 'aelia_currency';?>"><?php _e('Aelia Currency', 'rex-product-feed')?>
					<i class="fa fa-question-circle" aria-hidden="true"></i>
				</label>
				<select name="<?php echo $this->prefix . 'aelia_currency';?>" id="<?php echo $this->prefix . 'aelia_currency';?>" class="">
					<?php
                    $selected_price = get_post_meta( get_the_ID(), 'rex_feed_aelia_currency', true );
					foreach( $currency_options as $key => $value ) {
					    $selected = $selected_price === $key ? ' selected' : '';
						echo '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
					}
					?>
				</select>
			</div>

		<?php } ?>

        <?php
        if ( wpfm_is_wmc_active() ) {
            $wmc_settings = class_exists( 'WOOMULTI_CURRENCY_Data' ) ? WOOMULTI_CURRENCY_Data::get_ins() : array();
            $wmc_default_currency = !empty( $wmc_settings ) ? $wmc_settings->get_default_currency() : 'USD';
            $wmc_currency_list = !empty( $wmc_settings ) ? $wmc_settings->currencies_list : array();
            $wmc_world_currency = get_woocommerce_currencies();
            $wmc_world_currency = is_array( $wmc_world_currency ) ? $wmc_world_currency : array();
            $currency_options = array();

            if ( is_array( $wmc_currency_list ) && !empty( $wmc_currency_list ) ) {
                foreach ( $wmc_currency_list as $key => $value ) {
                    if( array_key_exists( $key, $wmc_world_currency) ){
                        $currency_options[ $key ] = $wmc_world_currency[ $key ];
                    }
                }
            }
            else{
                $currency_options = array( 'Please configure WooCommerce Multi-Currency Switcher!' );
            }
            ?>
            <div class="<?php echo $this->prefix . 'wmc_currency';?>">
                <label for="<?php echo $this->prefix . 'wmc_currency';?>"><?php _e('WooCommerce Multi-Currency', 'rex-product-feed')?>
                    <span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'This option will convert all your product prices using WooCommerce Multi-Currency Switcher', 'rex-product-feed' ); ?></p>
                </span>
                </label>
                <select name="<?php echo $this->prefix . 'wmc_currency';?>" id="<?php echo $this->prefix . 'wmc_currency';?>" class="">
                    <?php
                    $selected_price = get_post_meta( get_the_ID(), 'rex_feed_wmc_currency', true );
                    $selected_price = $selected_price === '' ? $wmc_default_currency : $selected_price;
                    foreach( $currency_options as $key => $value ) {
                        $selected = $selected_price === $key ? ' selected' : '';
                        echo '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
                    }
                    ?>
                </select>
            </div>

        <?php } ?>

		<div class="<?php echo $this->prefix . 'analytics_params_options';?>">
			<label for="<?php echo $this->prefix . 'analytics_params_options_content';?>"><?php _e('Track Your Campaign', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php _e( 'Analytics Parameters', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo $this->prefix . 'analytics_params_options_content';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_analytics_params_options', true );
				$checked = $saved_value === 'on' ? ' checked="checked"' : '';

				echo '<li>';
				echo '<input type="checkbox" ' . $checked .' name="' . $this->prefix . 'analytics_params_options' . '" value="on" id="'. $this->prefix . 'analytics_params_options' .'">';
				echo '<label for="rex_feed_analytics_params_options">Check to activate UTM Params</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo $this->prefix . 'analytics_params';?>" style="display: none">
			<label for="<?php echo $this->prefix . 'analytics_params';?>"><?php _e('UTM Parameters', 'rex-product-feed')?></label>
			<ul id="<?php echo $this->prefix . 'analytics_params';?>">
				<?php
				$analytics_params = get_post_meta( get_the_ID(), 'rex_feed_analytics_params', true );
				$utm_source       = isset( $analytics_params[ 'utm_source' ] ) ? $analytics_params[ 'utm_source' ] : '';
				$utm_medium       = isset( $analytics_params[ 'utm_medium' ] ) ? $analytics_params[ 'utm_medium' ] : '';
				$utm_campaign     = isset( $analytics_params[ 'utm_campaign' ] ) ? $analytics_params[ 'utm_campaign' ] : '';
				$utm_term         = isset( $analytics_params[ 'utm_term' ] ) ? $analytics_params[ 'utm_term' ] : '';
				$utm_content      = isset( $analytics_params[ 'utm_content' ] ) ? $analytics_params[ 'utm_content' ] : '';

				echo '<li>';
				?>
				<label for="<?php echo $this->prefix . 'analytics_params_utm_source';?>"><?php _e('Referrer', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php _e('The referrer: (e.g. google, newsletter)', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . $this->prefix . 'analytics_params[utm_source]' . '" value="' .$utm_source. '" id="'. $this->prefix . 'analytics_params_utm_source' .'">';
				echo '</li>';

				echo '<li>';
				?>
				<label for="<?php echo $this->prefix . 'analytics_params_utm_medium';?>"><?php _e('Medium', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php _e('Marketing medium: (e.g. cpc, banner, email)', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . $this->prefix . 'analytics_params[utm_medium]' . '" value="' .$utm_medium. '" id="'. $this->prefix . 'analytics_params_utm_medium' .'">';
				echo '</li>';

				echo '<li>';
				?>
				<label for="<?php echo $this->prefix . 'analytics_params_utm_campaign';?>"><?php _e('Campaign', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php _e('Product, promo code, or slogan (e.g. spring_sale)', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . $this->prefix . 'analytics_params[utm_campaign]' . '" value="' .$utm_campaign. '" id="'. $this->prefix . 'analytics_params_utm_campaign' .'">';
				echo '</li>';


				echo '<li>';
				?>
				<label for="<?php echo $this->prefix . 'analytics_params_utm_term';?>"><?php _e('Campaign Term', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php _e('Identify the paid keywords', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . $this->prefix . 'analytics_params[utm_term]' . '" value="' .$utm_term. '" id="'. $this->prefix . 'analytics_params_utm_term' .'">';
				echo '</li>';

				echo '<li>';
				?>
				<label for="<?php echo $this->prefix . 'analytics_params_utm_content';?>"><?php _e('Campaign Content', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php _e('Use to differentiate ads', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . $this->prefix . 'analytics_params[utm_content]' . '" value="' .$utm_content. '" id="'. $this->prefix . 'analytics_params_utm_content' .'">';
				echo '</li>';
				?>
			</ul>
		</div>
	</div>

</div>