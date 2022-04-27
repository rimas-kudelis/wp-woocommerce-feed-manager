<?php $icon = '../assets/icon/icon-svg/icon-question.php'; ?>

<div id="<?php echo esc_attr( $this->prefix ) . 'google_merchant_sidebar__content'; ?>" class="<?php echo esc_attr( $this->prefix ) . 'google_merchant_sidebar__content'; ?>">
	<label for="<?php echo esc_attr( $this->prefix ) . 'google_destination';?>"><?php esc_html_e('Select Destination', 'rex-product-feed')?>
		<span class="rex_feed-tooltip">
            <?php include plugin_dir_path(__FILE__) . $icon;?>
            <p><?php esc_html_e('Select Destination', 'rex-product-feed')?></p>
        </span>
	</label>

	<?php
	$index = 1;
	$prev_value = get_post_meta( get_the_ID(), 'rex_feed_google_destination', true );

	foreach( $destinations as $key => $value ) {
		$checked = is_array( $prev_value ) && in_array( $key, $prev_value )  ? ' checked="checked"' : '';
		echo '<li>';
		echo '<input type="checkbox" id="'. esc_attr($this->prefix) . 'google_destination' . esc_attr($index) . '" value="'. esc_attr($value) .'" name="' .esc_attr($this->prefix). 'google_destination[]" ' .esc_attr($checked). '>';
		echo '<label for="'. esc_attr($this->prefix) . 'google_destination' . esc_attr($index++) . '">'.esc_html__($key, 'rex-product-feed').'</label>';
		echo '</li>';
	}
	?>
</div>
<?php
$value = get_post_meta( get_the_ID(), 'rex_feed_google_target_country', true );
$value = $value == '' ? 'US' : $value;
?>
<div id="<?php echo esc_attr( $this->prefix ) . 'google_target_country__content'; ?>" class="<?php echo esc_attr( $this->prefix ) . 'google_target_country__content'; ?>">

	<label for="<?php echo esc_attr( $this->prefix ) . 'google_target_country';?>"><?php esc_html_e('Target Country', 'rex-product-feed')?>
		<span class="rex_feed-tooltip">
                    <?php include plugin_dir_path(__FILE__) . $icon;?>
                    <p><?php esc_html_e('Target Country', 'rex-product-feed')?></p>
                </span>
	</label>

	<input type="text" id="<?php echo esc_attr( $this->prefix ) . 'google_target_country';?>" value="<?php echo esc_attr($value)?>" name="<?php echo esc_attr( $this->prefix ) . 'google_target_country'?>" required>
</div>

<?php
$value = get_post_meta( get_the_ID(), 'rex_feed_google_target_language', true );
$value = $value == '' ? 'en' : $value;
?>
<div id="<?php echo esc_attr( $this->prefix ) . 'google_target_language__content'; ?>" class="<?php echo esc_attr( $this->prefix ) . 'google_target_language__content'; ?>">
	<label for="<?php echo esc_attr( $this->prefix ) . 'google_target_language';?>"><?php esc_html_e('Target Language', 'rex-product-feed')?>
		<span class="rex_feed-tooltip">
                    <?php include plugin_dir_path(__FILE__) . $icon;?>
                    <p><?php esc_html_e('Target Language', 'rex-product-feed')?></p>
                </span>
	</label>
	<input type="text" id="<?php echo esc_attr( $this->prefix ) . 'google_target_language';?>" value="<?php echo esc_attr($value)?>" name="<?php echo esc_attr( $this->prefix ) . 'google_target_language'?>" required>
</div>

<div id="<?php echo esc_attr( $this->prefix ) . 'google_schedule__content'; ?>" class="<?php echo esc_attr( $this->prefix ) . 'google_schedule__content'; ?>">
	<label for="<?php echo esc_attr( $this->prefix ) . 'google_schedule';?>"><?php esc_html_e('Schedule', 'rex-product-feed')?>
		<span class="rex_feed-tooltip">
                    <?php include plugin_dir_path(__FILE__) . $icon;?>
                    <p><?php esc_html_e('Schedule', 'rex-product-feed')?></p>
                </span>
	</label>
	<select name="<?php echo esc_attr( $this->prefix ) . 'google_schedule'; ?>" id="<?php echo esc_attr( $this->prefix ) . 'google_schedule'; ?>">
		<?php
		$prev_value = get_post_meta( get_the_ID(), 'rex_feed_google_schedule', true );
		$prev_value = $prev_value !== '' ? $prev_value : 'monthly';
		foreach ( $schedules as $key => $value ) {
			$selected = $key == $prev_value ? ' selected' : '';
			echo '<option value="'.esc_attr($key).'" ' .esc_attr($selected). '>'.esc_attr($value).'</option>';
		}
		?>
	</select>
</div>

<div id="<?php echo esc_attr( $this->prefix ) . 'google_schedule_month__content'; ?>" class="<?php echo esc_attr( $this->prefix ) . 'google_schedule_month__content'; ?>" style="display: none">
	<label for="<?php echo esc_attr( $this->prefix ) . 'google_schedule_month';?>"><?php esc_html_e('Select Day of Month', 'rex-product-feed')?>
		<span class="rex_feed-tooltip">
                    <?php include plugin_dir_path(__FILE__) . $icon;?>
                    <p><?php esc_html_e('Select Day of Month', 'rex-product-feed')?></p>
                </span>
	</label>
	<select name="<?php echo esc_attr( $this->prefix ) . 'google_schedule_month'; ?>"
	        id="<?php echo esc_attr( $this->prefix ) . 'google_schedule_month'; ?>"
	        data-conditional-value="monthly"
	        data-conditional-id="<?php echo esc_attr( $this->prefix ) . 'google_schedule'; ?>">
		<?php
		$prev_value = get_post_meta( get_the_ID(), 'rex_feed_google_schedule_month', true );
		$prev_value = $prev_value !== '' ? $prev_value : '1';
		foreach ( $month_array as $key => $value ) {
			$selected = $key == $prev_value ? ' selected' : '';
			echo '<option value="'.esc_attr($key).'" ' .esc_attr($selected). '>'.esc_attr($value).'</option>';
		}
		?>
	</select>
</div>

<div id="<?php echo esc_attr( $this->prefix ) . 'google_schedule_week_day__content'; ?>" class="<?php echo esc_attr( $this->prefix ) . 'google_schedule_week_day__content'; ?>" style="display: none">
	<label for="<?php echo esc_attr( $this->prefix ) . 'google_schedule_month';?>"><?php esc_html_e('Select Day of Week', 'rex-product-feed')?>
		<span class="rex_feed-tooltip">
                    <?php include plugin_dir_path(__FILE__) . $icon;?>
                    <p><?php esc_html_e('Select Day of Week', 'rex-product-feed')?></p>
                </span>
	</label>
	<select name="<?php echo esc_attr( $this->prefix ) . 'google_schedule_week_day'; ?>"
	        id="<?php echo esc_attr( $this->prefix ) . 'google_schedule_week_day'; ?>"
	        data-conditional-value="weekly"
	        data-conditional-id="<?php echo esc_attr( $this->prefix ) . 'google_schedule'; ?>">
		<?php
		$prev_value = get_post_meta( get_the_ID(), 'rex_feed_google_schedule_week_day', true );
		$prev_value = $prev_value !== '' ? $prev_value : 'monday';
		foreach ( $weeks as $key => $value ) {
			$selected = $key == $prev_value ? ' selected' : '';
			echo '<option value="'.esc_attr($key).'" ' .esc_attr($selected). '>'.esc_attr($value).'</option>';
		}
		?>
	</select>
</div style="display: none">

<div id="<?php echo esc_attr( $this->prefix ) . 'google_schedule_time__content'; ?>" class="<?php echo esc_attr( $this->prefix ) . 'google_schedule_time__content'; ?>" style="display: none">
	<label for="<?php echo esc_attr( $this->prefix ) . 'google_schedule_time';?>"><?php esc_html_e('Select Hour', 'rex-product-feed')?>
		<span class="rex_feed-tooltip">
                    <?php include plugin_dir_path(__FILE__) . $icon;?>
                    <p><?php esc_html_e('Select Hour', 'rex-product-feed')?></p>
                </span>
	</label>
	<select name="<?php echo esc_attr( $this->prefix ) . 'google_schedule_time'; ?>"
	        id="<?php echo esc_attr( $this->prefix ) . 'google_schedule_time'; ?>">
		<?php
		$prev_value = get_post_meta( get_the_ID(), 'rex_feed_google_schedule_time', true );
		$prev_value = $prev_value !== '' ? $prev_value : '1';
		foreach ( range( 0, 23 ) as $key => $value ) {
			$selected = $key == $prev_value ? ' selected' : '';
			echo '<option value="'.esc_attr($value).'" ' .esc_attr($selected). '>'.esc_attr($value).'</option>';
		}
		?>
	</select>
</div>