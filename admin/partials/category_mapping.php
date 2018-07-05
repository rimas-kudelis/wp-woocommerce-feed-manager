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
                    <div class="acordion-item">
                        <h6><a href="#" class="mapper_name_update"><?php echo $map_name ?></a></h6>
                        <div class="inner" style="display: none;">
                            <form action="" method="post" class="update_cat_map">
                                <table class="widefat fixed cat-map highlight" id="cat-map">
                                    <thead>
                                    <tr>
                                        <th>Product Category</th>
                                        <th>Merchant Category</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php foreach ($map_config as $config){
                                        ?>

                                        <tr data-row-id="<?php echo $x; ?>" class="trow">
                                            <td>
                                                <select name="category-map[<?php echo $x; ?>][map-key]">
                                                    <?php
                                                    if($categories){
                                                        foreach ($categories as $category){
                                                            $temp_key = $temp_value = '';
                                                            $temp_key = $category->term_id;

                                                            $selected = $config['map-key'] ==  $temp_key ? 'selected' : '';
                                                            ?>
                                                            <option value='<?php echo $category->term_id ?>' <?php echo $selected; ?>><?php echo $category->name ?></option>
                                                        <?php }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td class="input-map">
                                                <input class='category-suggest' type='text' name='category-map[<?php echo $x; ?>][map-value]' data-value="" value="<?php echo $config['map-value']; ?>">
                                            </td>
                                            <td>
                                                <a class="btn-floating waves-effect waves-light red delete">
                                                    <i class="material-icons">delete</i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $x++; } ?>
                                    </tbody>
                                </table>
                                <a id="rex-new-cat" class="waves-effect waves-light btn-large rex-new-cat bwf-btn"><i class="material-icons left">add</i>Add New Category</a>
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


            <form action="" method="post" class="add_cat_map">

                <table class="widefat fixed cat-map highlight" id="cat-map">
                    <thead>
                    <tr>
                        <th>Product Category</th>
                        <th>Merchant Category</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr data-row-id="0" class="trow">
                        <td>
                            <select name="category-map[0][map-key]">
                                <?php
                                if($product_category){
                                    foreach ($categories as $category){?>
                                        <option value='<?php echo $category->term_id ?>'><?php echo $category->name ?></option>
                                    <?php }
                                }
                                ?>
                            </select>
                        </td>
                        <td class="input-map">
                            <input class='category-suggest' type='text' name='category-map[0][map-value]' data-value="">
                        </td>
                        <td>
                            <a class="btn-floating waves-effect waves-light red delete">
                                <i class="material-icons">delete</i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <a id="rex-new-cat" class="waves-effect waves-light btn-large rex-new-cat bwf-btn"><i class="material-icons left">add</i>Add New Category</a>

                <div class="cat-map-actions">
                    <button type="submit" class="waves-effect waves-light btn-large green" id="save_mapping_cat">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



