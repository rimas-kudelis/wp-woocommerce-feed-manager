<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is display the custom filter for product
 *
 * @link       https://rextheme.com
 * @since      1.1.10
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/partials
 */


// Exit if $feed_template obj isn't available.
if ( ! isset($feed_filter) ) {
    return;
}

unset($feed_filter->getFilterMappings()['Primary Attributes']['product_cats']);
unset($feed_filter->getFilterMappings()['Primary Attributes']['product_tags']);

?>




<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<table id="config-table" class="filter-config-table responsive-table">
    <thead>
        <tr>
            <th class="large-col">If</th>
            <th class="large-col">Condition</th>
            <th class="large-col">Value</th>
            <th colspan="2" class="small-col">Then</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ( $feed_filter->getFilterMappings() as $key => $item): ?>
            <tr data-row-id="<?php echo $key; ?>">
                <td data-title="If : "><?php $feed_filter->printSelectDropdown( $key, 'if', $item['if'] ); ?></td>
                <td data-title="condition : "><?php $feed_filter->printSelectDropdown( $key, 'condition', $item['condition'] ); ?></td>
                <td data-title="value : "><?php $feed_filter->printInput( $key, 'value', $item['value'] ); ?></td>
                <td data-title="then : "><?php $feed_filter->printSelectDropdown( $key, 'then', $item['then'] ); ?></td>
                <td>
                    <a class="delete-row" title="Delete">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>

</table>
