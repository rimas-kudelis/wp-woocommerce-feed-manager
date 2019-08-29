<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is display the google category mapping feature
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/partials
 */


$product_category = new CategoryMapping();
$categories = $product_category->get_category();


function bwfm_hierarchical_product_category_tree( $cat ) {
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
            echo "<tr class='no-padding-margin'>";
                echo "<td><strong>{$separator}{$cat->name} ({$cat->count})</strong></td>";
                echo "<td><div class='input-field'><input class='autocomplete category-suggest' type='text' name='category-{$cat->term_id}'></div></td>";
            echo "</tr>";
            $separator = '';
            bwfm_hierarchical_product_category_tree( $cat->term_id );
        endforeach;
    endif;
}



$category_map = get_option('rex-wpfm-category-mapping');
require plugin_dir_path( __FILE__ ) . 'loading-spinner.php';
$db_version = get_option('rex_wpfm_db_version');
?>


<div class="row">
    <div class="col s12 m12">
        <div class="rex-accordion">
            <?php if ($category_map) {  ?>
                <?php foreach ($category_map as $key => $value) {
                    ?>
                    <div class="acordion-item">
                        <h6><a href="#" class="mapper_name_update" data-id="<?php echo $key; ?>"><?php echo $value['map-name'] ?></a></h6>
                        <div class="inner" style="display: none;">
                            <form action="" method="post" class="update_cat_map">
                                <table class="widefat fixed cat-map highlight" id="cat-map">
                                    <thead>
                                        <tr>
                                            <th><?php echo __('Product Category', 'rex-product-feed'); ?></th>
                                            <th><?php echo __('Google Merchant Category', 'rex-product-feed'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $separator = '';
                                            $sub_cat = [];
                                            foreach ($value['map-config'] as $index => $cat_value){
                                                $args = array(
                                                    'parent' 	=> $cat_value['map-key'],
                                                    'hide_empty'    => false,
                                                    'no_found_rows' => true,
                                                );
                                                $next = get_terms('product_cat', $args);
                                                echo "<tr class='no-padding-margin'>";
                                                echo "<td>{$cat_value['cat-name']}</td>";
                                                echo "<td><div class='input-field'><input class='category-suggest' type='text' name='category-{$cat_value['map-key']}' value='{$cat_value['map-value']}'></div></td>";
                                                echo "</tr>";

                                            }

                                        ?>

                                    </tbody>
                                </table>
                                <div class="cat-map-actions">
                                    <button type="submit" class="waves-effect waves-light btn-large green" id="update_mapping_cat">Update</button>
                                    <button type="submit" class="waves-effect waves-light btn-large red" id="delete_mapping_cat">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php }
            else { if($db_version >= 3) {?>
                    <div class="info-msg">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('Please update WPFM database', 'rex-product-feed'); ?>
                    </div>
                <?php } }?>
        </div>
    </div>
</div>



<div class="row">
    <div class="col s12 m12">
        <div class="category-mapper-wrapper card ">
            <div class="section">
                <h5><strong>Add New Category Map</strong></h5>
            </div>

            <table class="widefat fixed highlight">
                <tbody>
                <tr>
                    <td><p>Mapper Name</p></td>
                    <td><input id="map_name" type="text" name="mapper_name"></td>
                </tr>
                </tbody>
            </table>


            <form action="#" method="post" class="add_cat_map">

                <table class="widefat fixed cat-map highlight" id="cat-map">
                    <thead>
                    <tr>
                        <th><?php echo __('Product Category', 'rex-product-feed'); ?></th>
                        <th><?php echo __('Google Merchant Category', 'rex-product-feed'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                        <?php bwfm_hierarchical_product_category_tree(0); ?>
                    </tbody>
                </table>
                <div class="cat-map-actions">
                    <button type="submit" class="waves-effect waves-light btn-large green" id="save_mapping_cat">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



