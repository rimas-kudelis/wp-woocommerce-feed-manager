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

$all_options = wp_load_alloptions();
$cat_map_options  = array();

foreach ( $all_options as $name => $value ) {
    if ( stristr( $name, 'rex_cat_map_' ) ) {
        $cat_map_options[ $name ] = $value;
    }
}

$x = 0;
$cat_keys = [];

require plugin_dir_path( __FILE__ ) . 'loading-spinner.php';
?>

<div class="row">
    <div class="col s12 m12">
        <div class="rex-accordion">
            <?php
            if ($cat_map_options) {
                foreach ($cat_map_options as $key => $value) {
                        $data = unserialize($value);
                        $map_name = $data['map-name'];
                        $map_config = $data['map-config'];

                    ?>
                    <?php
                    $existing_category_mapping_array = array();
                    $temp_cat_array = array();
                    $temp_config_array = array();
                    if($categories){
                        foreach ($categories as $category){
                            $temp_cat_array[$category->term_id] = array(
                                    'name'  => $category->name,
                                    'id'  => $category->term_id,
                            );
                        }
                    }

                    if($map_config) {
                        foreach ($map_config as $config) {
                            $temp_config_array[$config['map-key']] = $config;
                        }
                    }

                    foreach ($temp_cat_array as $k=>$v) {
                        if($temp_config_array[$k]) {
                            $temp_cat_array[$k] = $temp_cat_array[$k] + $temp_config_array[$k];
                        }
                    }

                    ?>
                    <div class="acordion-item">
                        <h6><a href="#" class="mapper_name_update"><?php echo $map_name ?></a></h6>
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


                                            <?php foreach ($temp_cat_array as $index => $cat_value){
                                                echo "<tr>";
                                                echo "<td>{$cat_value['name']}</td>";
                                                echo "<td><input class='category-suggest' type='text' name='category-{$cat_value['id']}' value='{$cat_value['map-value']}'></td>";
                                                echo "</tr>";
                                            } ?>

                                    </tbody>
                                </table>
                                <div class="cat-map-actions">
                                    <button type="submit" class="waves-effect waves-light btn-large green" id="update_mapping_cat">Update</button>
                                    <button type="submit" class="waves-effect waves-light btn-large red" id="delete_mapping_cat">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php }
            }
            ?>
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
                    <?php
                        if($product_category){
                            foreach ($categories as $category):
                                echo "<tr>";
	                                echo "<td>{$category->name}</td>";
	                                echo "<td><input class='category-suggest' type='text' name='category-{$category->term_id}'></td>";
	                            echo "</tr>";
	                        endforeach;
	                    }
	                ?>
                    </tbody>
                </table>
                <div class="cat-map-actions">
                    <button type="submit" class="waves-effect waves-light btn-large green" id="save_mapping_cat">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



