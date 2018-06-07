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

require plugin_dir_path( __FILE__ ) . 'loading-spinner.php';
?>

<div class="rex-accordion">
        <?php
            if ($cat_map_options){
                foreach ($cat_map_options as $key=>$value){
                    $data = unserialize($value);
                    $map_name = $data['map_name'];?>
                    <div class="acordion-item">
                        <h6><a href="#" class="mapper_name_update"><?php echo $map_name?></a></h6>
                        <div class="inner" style="display: none;">
                            <form action="" method="post" class="update_cat_map">
                                <table class="widefat fixed">
                                    <thead>
                                        <tr>
                                            <th>Product Category</th>
                                            <th>Merchant Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if($product_category){
                                            foreach ($categories as $category):
                                                $temp_key = $temp_value = '';
                                                echo "<tr>";
                                                echo "<td>{$category->name}</td>";
                                                $temp_key = 'category-'.$category->term_id;
                                                if(array_key_exists($temp_key, $data)){
                                                    $temp_value =htmlspecialchars(utf8_decode(urldecode($data[$temp_key]["value"]))) ;
                                                    echo "<td><input type='text' class='category-suggest' name='category-{$category->term_id}' value='$temp_value'></td>";
                                                }else{
                                                    echo "<td><input type='text' class='category-suggest'  name='category-{$category->term_id}'></td>";
                                                }
                                                echo "</tr>";
                                            endforeach;
                                        }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <div class="cat-map-actions">
                                                    <button type="submit" class="waves-effect waves-light btn-large green" id="update_mapping_cat">Update</button>
                                                    <button type="submit" class="waves-effect waves-light btn-large red" id="delete_mapping_cat">Delete</button>
                                                </div>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </form>
                        </div>
                    </div>

                <?php }
            }
        ?>
</div>

<div class="category-mapper-wrapper">
    <h5><strong>Add New Category Map</strong></h5>

    <table class="widefat fixed">
        <tbody>
            <tr>
                <td>Mapper Name</td>
                <td><input id="map_name" type="text" name="mapper_name"></td>
            </tr>
        </tbody>
    </table>

    <form action="" method="post" class="add_cat_map">

        <table class="widefat fixed">
            <thead>
                <tr>
                    <th>Product Category</th>
                    <th>Merchant Category</th>
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
                <tr>
                    <td></td>
                    <td>
                        <div class="cat-map-actions">
                            <button type="submit" class="waves-effect waves-light btn-large green" id="save_mapping_cat">Save</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>


