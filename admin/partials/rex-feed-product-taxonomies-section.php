<?php
$prefix = 'rex_feed_';
$icon = '../assets/icon/icon-svg/icon-question.php';
?>

<div id="rex-feed-product-taxonomies-contents">
	<div id="rex-feed-product-cats" style="display: none">
		<label for="<?php echo $prefix . 'cats';?>"><?php _e('Product Categories', 'rex-product-feed')?>
			<span class="rex_feed-tooltip">
                    <?php include plugin_dir_path(__FILE__) . $icon;?>
                    <p><?php _e('Product Categories', 'rex-product-feed')?></p>
                </span>
		</label>
		<ul id="<?php echo $prefix . 'cats';?>">
			<?php
			$terms      = get_terms( array( 'taxonomy' => 'product_cat' ) );
			$terms      = is_array( $terms ) ? $terms : array();
			$post_terms = get_post_meta( $feed_id, 'rex_feed_cats', true );
			$post_terms = is_array( $post_terms ) ? $post_terms : array();
			$index      = 1;

			foreach( $terms as $term ) {
				$checked = in_array( $term->slug, $post_terms) ? ' checked' : '';
				echo '<li>';
				echo '<input type="checkbox" id="'. $prefix . 'cats' . $index . '" name="'. $prefix . 'cats[]' . '" value="'. $term->slug .'" ' .$checked. '>';
				echo '<label for="'. $prefix . 'cats' . $index++ . '">'.__($term->name, 'rex-product-feed').'</label>';
				echo '</li>';
			}
			?>
		</ul>
	</div>
	<div id="rex-feed-product-tags" style="display: none">
		<label for="<?php echo $prefix . 'tags';?>"><?php _e('Product Tags', 'rex-product-feed')?>
			<span class="rex_feed-tooltip">
                    <?php include plugin_dir_path(__FILE__) . $icon;?>
                    <p><?php _e('Product Tags', 'rex-product-feed')?></p>
                </span>
		</label>
		<ul id="<?php echo $prefix . 'tags';?>">
			<?php
			$terms      = get_terms( array( 'taxonomy' => 'product_tag' ) );
			$terms      = is_array( $terms ) ? $terms : array();
			$post_terms = get_post_meta( $feed_id, 'rex_feed_tags', true );
			$post_terms = is_array( $post_terms ) ? $post_terms : array();
			$index      = 1;

			foreach( $terms as $term ) {
				$checked = in_array( $term->slug, $post_terms) ? ' checked' : '';
				echo '<li>';
				echo '<input type="checkbox" id="'. $prefix . 'tags' . $index . '" name="'. $prefix . 'tags[]' . '" value="'. $term->slug .'" ' .$checked. '>';
				echo '<label for="'. $prefix . 'tags' . $index++ . '">'.__($term->name, 'rex-product-feed').'</label>';
				echo '</li>';
			}
			if ( empty( $terms ) ) {
				echo '<li>';
				echo '<label for="'. $prefix . 'tags' . $index++ . '">'.__('No Terms', 'rex-product-feed').'</label>';
				echo '</li>';
			}
			?>
		</ul>
	</div>
</div>