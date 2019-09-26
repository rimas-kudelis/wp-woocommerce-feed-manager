<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is display the custom feed configuration part of the metabox on feed edit screen.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/partials
 */

// Exit if $feed_template obj isn't available.
if ( ! isset($feed_template) ) {
    return;
}


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<table id="config-table" class="responsive-table">
    <thead>
    <tr>
        <th class="large-col">Attributes</th>
        <th class="large-col">Type</th>
        <th class="large-col">Value</th>
        <th class="small-col">Prefix</th>
        <th class="small-col">Suffix</th>
        <th class="large-col">Output Sanitization</th>
        <th colspan="2" class="small-col">Output Limit</th>
    </tr>
    </thead>

    <tbody>

    <?php foreach ( $feed_template->getTemplateMappings() as $key => $item): ?>
        <?php
        $hideStaticInput = $item['type'] != 'static' ? 'style="display:none;"' : '';
        $hideMetaInput   = $item['type'] == 'static' ? 'style="display:none;"' : '';
        ?>
        <tr data-row-id="<?php echo $key; ?>">
            <td data-title="Attributes : "><?php $feed_template->printSelectDropdown( $key, 'attr', $item['attr'] ); ?></td>
            <td data-title="Type : "><?php $feed_template->printAttType( $key, $item['type'] ); ?></td>
            <td data-title="Value : ">

                <div class="meta-dropdown" <?php echo $hideMetaInput; ?>>
                    <?php $feed_template->printSelectDropdown( $key, 'meta_key', $item['meta_key'] ); ?>
                </div>

                <div class="static-input" <?php echo $hideStaticInput; ?>>
                    <?php $feed_template->printInput( $key, 'st_value', $item['st_value'] ); ?>
                </div>


            </td>
            <td data-title="Prefix : "><?php $feed_template->printInput( $key, 'prefix', $item['prefix'] ); ?></td>
            <td data-title="Suffix : "><?php $feed_template->printInput( $key, 'suffix', $item['suffix'] ); ?></td>
            <td data-title="Output Sanitization : "><?php $feed_template->printSelectDropdown( $key, 'escape', $item['escape'] ); ?></td>
            <td data-title="Output Limit : "><?php $feed_template->printInput( $key, 'limit', $item['limit'] ); ?></td>
            <td>
                <a class="delete-row" title="Delete">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
    <?php endforeach ?>

    </tbody>

</table>