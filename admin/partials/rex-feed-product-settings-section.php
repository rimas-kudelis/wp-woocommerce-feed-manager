<?php $icon_question = 'icon/icon-svg/icon-question.php'; ?>

<div class="rex-contnet-setting-area">

	<div class="rex-contnet-setting__header">
		<div class="rex-contnet-setting__header-text">
			<div class="rex-contnet-setting__icon rex-contnet__header-text">
				<?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/icon-setting.php';?>
				<?php echo '<h2>' . esc_html__( 'Settings', 'rex-product-feed' ) . '</h2>';?>
			</div>
		</div>
		<span class="rex-contnet-setting__close-icon close-btn">
			Close
        </span>
	</div>

	<div class="rex-contnet-setting-content-area">
		<div class="<?php echo esc_attr( $this->prefix ) . 'schedule';?>">
			<label for="<?php echo esc_attr( $this->prefix ) . 'schedule';?>"><?php esc_html_e('Auto-Generate Your Feed', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'Set auto-update to keep your feed in sync with WooCommerce', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_html( $this->prefix ) . 'schedule';?>">
				<?php
				$index = 1;
				$prev_value = get_post_meta( get_the_ID(), 'rex_feed_schedule', true );
				$prev_value = $prev_value !== '' ? $prev_value : 'no';
				foreach( $schedules as $key => $value ) {
					$checked = $key === $prev_value ? ' checked="checked"' : '';
					echo '<li>';
					echo '<input type="radio" id="'. esc_attr( $this->prefix ) . 'schedule' . esc_attr( $index ) . '" name="'. esc_attr( $this->prefix ) . 'schedule' . '" value="'. esc_attr( $key ) .'" ' . esc_html( $checked ) . '>';
					echo '<label for="'. esc_attr( $this->prefix ) . 'schedule' . esc_attr( $index++ ) . '">'.esc_html__( $value, 'rex-product-feed' ).'</label>';
					echo '</li>';
				}
                echo '<li class="'. esc_attr( $this->prefix ) .  'custom_time_fields">';
                $selected_hour = get_post_meta( get_the_ID(), 'rex_feed_custom_time', true );
                echo '<select id="'. esc_attr( $this->prefix ) . 'custom_time " name="'. esc_attr( $this->prefix ) . 'custom_time">';
                for( $i=0; $i<24; $i++ ) {
                    $selected = (int)$selected_hour === $i ? ' selected' : '';
                    echo '<option value="'. esc_attr( $i ) .'" '. esc_html( $selected ) .'>'. esc_attr( $i ) .' h</option>';
                }
                echo '</select>';
                echo '<label for="'. esc_attr( $this->prefix ) . 'custom_time' . '">'.esc_html__('Every Day', 'rex-product-feed').'</label>';
                echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'include_out_of_stock';?> ">
			<label for="<?php echo esc_attr( $this->prefix ) . 'include_out_of_stock';?>">
				<?php esc_html_e('Include Out of Stock Products', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'This option will include/exclude out of stock products from feed', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_attr( $this->prefix ) . 'include_out_of_stock';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_include_out_of_stock', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'yes';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'include_out_of_stock' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'include_out_of_stock2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'include_out_of_stock2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'include_out_of_stock' . '" value="no" id="'. esc_attr( $this->prefix ) . 'include_out_of_stock1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'include_out_of_stock1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'include_zero_price_products';?> ">
			<label for="<?php echo esc_attr( $this->prefix ) . 'include_zero_price_products';?>">
				<?php esc_html_e('Include Product with No Price', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'This option will include/exclude products with no regular price set or with regular price zero (0)', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_attr( $this->prefix ) . 'include_zero_price_products';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_include_zero_price_products', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'yes';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'include_zero_price_products' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'include_zero_price_products2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'include_zero_price_products2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'include_zero_price_products' . '" value="no" id="'. esc_attr( $this->prefix ) . 'include_zero_price_products1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'include_zero_price_products1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'variable_product';?> ">
			<label for="<?php echo esc_attr( $this->prefix ) . 'variable_product';?>">
				<?php esc_html_e('Include Variable Parent Product (Without Variations)', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'Include Variable Parent Product (Without Variations)', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_attr( $this->prefix ) . 'variable_product';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_variable_product', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'variable_product' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'variable_product2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'variable_product2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'variable_product' . '" value="no" id="'. esc_attr( $this->prefix ) . 'variable_product1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'variable_product1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'variations';?>">
			<label for="<?php echo esc_attr( $this->prefix ) . 'variations';?>"><?php esc_html_e('Include All Variable Products Variations', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
					<p>
                        <?php
                        esc_html_e( 'Include all the Variable Products Variations in your feed (these are only the product variations)', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( 'Example:', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( '<g:title>', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( '<![CDATA[ V-Neck T-Shirt]]>', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( '</g:title>', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( '<g:link>', 'rex-product-feed' );
						echo "<br>";
                        esc_html_e( '<![CDATA[ http://URL/]]>', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( '</g:link>' , 'rex-product-feed');
                        echo "<br>";
                        ?>
                    </p>
                </span>
			</label>
			<ul id="<?php echo esc_attr( $this->prefix ) . 'variations';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_variations', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'yes';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'variations' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'variations2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'variations2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'variations' . '" value="no" id="'. esc_attr( $this->prefix ) . 'variations1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'variations1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'variation_product_name';?>">
			<label for="<?php echo esc_attr( $this->prefix ) . 'variation_product_name';?>"><?php esc_html_e('Include Variation Name In The Product Title', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
					<p>
                        <?php
                        esc_html_e( 'Include the variation name in the product title', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( 'Example:', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( '<g:title>', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( '<![CDATA[ V-Neck T-Shirt - Red ]]>', 'rex-product-feed' );
                        echo "<br>";
                        esc_html_e( '</g:title>', 'rex-product-feed' );
                        ?>
                    </p>
                </span>
			</label>
			<ul id="<?php echo esc_attr( $this->prefix ) . 'variation_product_name';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_variation_product_name', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'variation_product_name' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'variation_product_name2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'variation_product_name2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_attr( $this->prefix ) . 'variation_product_name' . '" value="no" id="'. esc_attr( $this->prefix ) . 'variation_product_name1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'variation_product_name1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'parent_product';?>">
			<label for="<?php echo esc_attr( $this->prefix ) . 'parent_product';?>"><?php esc_html_e('Include Grouped Products', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'Enable this option to include grouped products in your feed', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_html( $this->prefix ) . 'parent_product';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_parent_product', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'yes';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'parent_product' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'parent_product2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'parent_product2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'parent_product' . '" value="no" id="'. esc_attr( $this->prefix ) . 'parent_product1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'parent_product1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'hidden_products';?>">
			<label for="<?php echo esc_attr( $this->prefix ) . 'hidden_products';?>"><?php esc_html_e('Exclude Invisible/Hidden Products', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'Enable this option to exclude invisible/hidden products from your feed', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_html( $this->prefix ) . 'hidden_products';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_hidden_products', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'hidden_products' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'hidden_products2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'hidden_products2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'hidden_products' . '" value="no" id="'. esc_attr( $this->prefix ) . 'hidden_products1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'hidden_products1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'skip_product';?>">
			<label for="<?php echo esc_attr( $this->prefix ) . 'skip_product';?>"><?php esc_html_e('Skip products with empty value', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'This option will remove products if there is a single attribute with empty value', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_html( $this->prefix ) . 'skip_product';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_skip_product', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'skip_product' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'skip_product2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'skip_product2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'skip_product' . '" value="no" id="'. esc_attr( $this->prefix ) . 'skip_product1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'skip_product1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'skip_row';?>">
			<label for="<?php echo esc_attr( $this->prefix ) . 'skip_row';?>"><?php esc_html_e('Skip attributes with empty value', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'This option will remove any attribute with empty value (XML feed format only)', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_html( $this->prefix ) . 'skip_row';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_skip_row', true );
				$saved_value = $saved_value !== '' ? $saved_value : 'no';
				$checked_yes = $saved_value === 'yes' ? ' checked="checked"' : '';
				$checked_no  = $saved_value === 'no' ? ' checked="checked"' : '';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'skip_row' . '" value="yes" id="'. esc_attr( $this->prefix ) . 'skip_row2' .'" ' . esc_html( $checked_yes ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'skip_row2' .'">'.esc_html__('Yes', 'rex-product-feed').'</label>';
				echo '</li>';
				echo '<li>';
				echo '<input type="radio" name="' . esc_html( $this->prefix ) . 'skip_row' . '" value="no" id="'. esc_attr( $this->prefix ) . 'skip_row1' .'" ' . esc_html( $checked_no ) .'>';
				echo '<label for="'. esc_attr( $this->prefix ) . 'skip_row1' .'">'.esc_html__('No', 'rex-product-feed').'</label>';
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

        <div class="<?php echo esc_attr( $this->prefix ) . 'wcml_currency';?>">
            <label for="<?php echo esc_attr( $this->prefix ) . 'wcml_currency';?>"><?php esc_html_e('WCML Currency', 'rex-product-feed')?>
                <i class="fa fa-question-circle" aria-hidden="true"></i>
            </label>
            <select name="<?php echo esc_html( $this->prefix ) . 'wcml_currency';?>" id="<?php echo esc_html( $this->prefix ) . 'wcml_currency';?>" class="">
				<?php
				$selected_price = get_post_meta( get_the_ID(), 'rex_feed_wcml_currency', true );
				foreach( $currencies as $key => $value ) {
					$selected = $selected_price === $key ? ' selected' : '';
					echo '<option value="'. esc_attr( $key ) .'" '. esc_html( $selected ) .'>'. esc_attr( $value ) .'</option>';
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
			<div class="<?php echo esc_attr( $this->prefix ) . 'aelia_currency';?>">
				<label for="<?php echo esc_attr( $this->prefix ) . 'aelia_currency';?>"><?php esc_html_e('Aelia Currency', 'rex-product-feed')?>
					<i class="fa fa-question-circle" aria-hidden="true"></i>
				</label>
				<select name="<?php echo esc_html( $this->prefix ) . 'aelia_currency';?>" id="<?php echo esc_html( $this->prefix ) . 'aelia_currency';?>" class="">
					<?php
                    $selected_price = get_post_meta( get_the_ID(), 'rex_feed_aelia_currency', true );
					foreach( $currency_options as $key => $value ) {
					    $selected = $selected_price === $key ? ' selected' : '';
						echo '<option value="'. esc_attr( $key ) .'" '. esc_html( $selected ) .'>'. esc_attr( $value ) .'</option>';
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
            <div class="<?php echo esc_attr( $this->prefix ) . 'wmc_currency';?>">
                <label for="<?php echo esc_attr( $this->prefix ) . 'wmc_currency';?>"><?php esc_html_e('WooCommerce Multi-Currency', 'rex-product-feed')?>
                    <span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'This option will convert all your product prices using WooCommerce Multi-Currency Switcher', 'rex-product-feed' ); ?></p>
                </span>
                </label>
                <select name="<?php echo esc_html( $this->prefix ) . 'wmc_currency';?>" id="<?php echo esc_html( $this->prefix ) . 'wmc_currency';?>" class="">
                    <?php
                    $selected_price = get_post_meta( get_the_ID(), 'rex_feed_wmc_currency', true );
                    $selected_price = $selected_price === '' ? $wmc_default_currency : $selected_price;
                    foreach( $currency_options as $key => $value ) {
                        $selected = $selected_price === $key ? ' selected' : '';
                        echo '<option value="'. esc_attr( $key ) .'" '. esc_html( $selected ) .'>'. esc_attr( $value ) .'</option>';
                    }
                    ?>
                </select>
            </div>

        <?php } ?>

		<div class="<?php echo esc_attr( $this->prefix ) . 'analytics_params_options';?>">
			<label for="<?php echo esc_attr( $this->prefix ) . 'analytics_params_options_content';?>"><?php esc_html_e('Track Your Campaign', 'rex-product-feed')?>
				<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                    <p><?php esc_html_e( 'Analytics Parameters', 'rex-product-feed' ); ?></p>
                </span>
			</label>
			<ul id="<?php echo esc_html( $this->prefix ) . 'analytics_params_options_content';?>">
				<?php
				$saved_value = get_post_meta( get_the_ID(), 'rex_feed_analytics_params_options', true );
				$checked = $saved_value === 'on' ? ' checked="checked"' : '';

				echo '<li>';
				echo '<input type="checkbox" '. esc_html( $checked ) .' name="' . esc_html( $this->prefix ) . 'analytics_params_options' . '" value="on" id="'. esc_attr( $this->prefix ) . 'analytics_params_options' .'">';
				echo '<label for="rex_feed_analytics_params_options">Check to activate UTM Params</label>';
				echo '</li>';
				?>
			</ul>
		</div>

		<div class="<?php echo esc_attr( $this->prefix ) . 'analytics_params';?>" style="display: none">
			<label for="<?php echo esc_attr( $this->prefix ) . 'analytics_params';?>"><?php esc_html_e('UTM Parameters', 'rex-product-feed')?></label>
			<ul id="<?php echo esc_html( $this->prefix ) . 'analytics_params';?>">
				<?php
				$analytics_params = get_post_meta( get_the_ID(), 'rex_feed_analytics_params', true );
				$utm_source       = isset( $analytics_params[ 'utm_source' ] ) ? $analytics_params[ 'utm_source' ] : '';
				$utm_medium       = isset( $analytics_params[ 'utm_medium' ] ) ? $analytics_params[ 'utm_medium' ] : '';
				$utm_campaign     = isset( $analytics_params[ 'utm_campaign' ] ) ? $analytics_params[ 'utm_campaign' ] : '';
				$utm_term         = isset( $analytics_params[ 'utm_term' ] ) ? $analytics_params[ 'utm_term' ] : '';
				$utm_content      = isset( $analytics_params[ 'utm_content' ] ) ? $analytics_params[ 'utm_content' ] : '';

				echo '<li>';
				?>
				<label for="<?php echo esc_attr( $this->prefix ) . 'analytics_params_utm_source';?>"><?php esc_html_e('Referrer', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php esc_html_e('The referrer: (e.g. google, newsletter)', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . esc_html( $this->prefix ) . 'analytics_params[utm_source]' . '" value="' .esc_attr($utm_source). '" id="'. esc_attr( $this->prefix ) . 'analytics_params_utm_source' .'">';
				echo '</li>';

				echo '<li>';
				?>
				<label for="<?php echo esc_attr( $this->prefix ) . 'analytics_params_utm_medium';?>"><?php esc_html_e('Medium', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php esc_html_e('Marketing medium: (e.g. cpc, banner, email)', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . esc_html( $this->prefix ) . 'analytics_params[utm_medium]' . '" value="' .esc_attr($utm_medium). '" id="'. esc_attr( $this->prefix ) . 'analytics_params_utm_medium' .'">';
				echo '</li>';

				echo '<li>';
				?>
				<label for="<?php echo esc_attr( $this->prefix ) . 'analytics_params_utm_campaign';?>"><?php esc_html_e('Campaign', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php esc_html_e('Product, promo code, or slogan (e.g. spring_sale)', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . esc_html( $this->prefix ) . 'analytics_params[utm_campaign]' . '" value="' .$utm_campaign. '" id="'. esc_attr( $this->prefix ) . 'analytics_params_utm_campaign' .'">';
				echo '</li>';


				echo '<li>';
				?>
				<label for="<?php echo esc_attr( $this->prefix ) . 'analytics_params_utm_term';?>"><?php esc_html_e('Campaign Term', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php esc_html_e('Identify the paid keywords', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . esc_html( $this->prefix ) . 'analytics_params[utm_term]' . '" value="' .esc_attr($utm_term). '" id="'. esc_attr( $this->prefix ) . 'analytics_params_utm_term' .'">';
				echo '</li>';

				echo '<li>';
				?>
				<label for="<?php echo esc_attr( $this->prefix ) . 'analytics_params_utm_content';?>"><?php esc_html_e('Campaign Content', 'rex-product-feed')?>
					<span class="rex_feed-tooltip">
                            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $icon_question;?>
                            <p><?php esc_html_e('Use to differentiate ads', 'rex-product-feed') ?></p>
                            </span>
				</label>

				<?php
				echo '<input type="text" name="' . esc_html( $this->prefix ) . 'analytics_params[utm_content]' . '" value="' .$utm_content. '" id="'. esc_attr( $this->prefix ) . 'analytics_params_utm_content' .'">';
				echo '</li>';
				?>
			</ul>
		</div>
	</div>

</div>