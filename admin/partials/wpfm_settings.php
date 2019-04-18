<?php
/**
 * Created by PhpStorm.
 * User: AR
 * Date: 4/16/19
 * Time: 10:08 AM
 */


$custom_field = get_option('rex-product-custom-field');
?>





<table>
    <thead>
        <tr>
            <th><?php echo __('Settings', 'rex-product-feed'); ?></th>
            <th><?php echo __('Options', 'rex-product-feed'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo __('Add brand, gtin and mpm to product single', 'rex-product-feed'); ?></td>
            <td>
                <div class="switch">
                    <label>
                        <?php echo __('No', 'rex-product-feed'); ?>
                        <input type="checkbox" id="rex-product-custom-field" <?php echo $custom_field==='yes' ? 'checked' : '' ?>>
                        <span class="lever"></span>
                        <?php echo __('Yes', 'rex-product-feed'); ?>
                    </label>
                </div>
            </td>
        </tr>
    </tbody>
</table>
