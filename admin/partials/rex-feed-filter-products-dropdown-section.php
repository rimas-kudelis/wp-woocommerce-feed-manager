<div class="rex-content-filter__area">
	<label for="<?php echo $this->prefix . 'products';?>"><?php _e('Products', 'rex-product-feed')?>
		<span class="rex_feed-tooltip">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/icon-question.php';?>
                    <p><?php _e( 'Filter Products', 'rex-product-feed' );?></p>
                </span>
	</label>
	<select name="<?php echo $this->prefix . 'products'; ?>" id="<?php echo $this->prefix . 'products'; ?>">
		<?php
		$prev_value = get_post_meta( get_the_ID(), 'rex_feed_products', true );
		$prev_value = $prev_value !== '' ? $prev_value : 'all';
		foreach ( $options as $key => $value ) {
			$selected = $key === $prev_value ? ' selected' : '';
			echo '<option value="'.$key.'" '. $selected .'>'.$value.'</option>';
		}
		?>
	</select>
</div>