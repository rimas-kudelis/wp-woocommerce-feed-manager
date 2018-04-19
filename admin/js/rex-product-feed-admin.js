(function( $ ) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).ready(function() {
        $('select').material_select();

        if ( $('#rex_feed_xml_file').val() == '' ) {
            $('#rex_feed_file_link').slideUp('fast');
        }
    });

    /**
     * Add a new table-row and update it's
     */
    $(document).on('click', '#rex-new-attr', function () {
        var rowId = $('#config-table tbody tr').length;
        $("#config-table tbody tr:first")
            .clone()
            .insertAfter('#config-table tbody tr:last')
            .attr('data-row-id', rowId)


        var $row = $('#config-table').find("[data-row-id='" + rowId + "']");
        $row.find('ul.dropdown-content.select-dropdown, .caret, .select-dropdown ').remove();

        $row.find('input, select').val('');

        updateFormNameAtts( $row, rowId);
        $row.find('select').material_select();


    });

    /**
     * Delete a table-row and update all row-id
     * beneath it and their input attributes names.
     */
    $(document).on('click', '#config-table .delete', function () {
        var $nextRows, rowId;

        // delete row and get it's row-id
        rowId = $(this).closest('tr').remove().data('row-id');

        // Gell the next rows
        if ( rowId == 0) {
            $nextRows = $('#config-table tbody').children();
        }else{
            $nextRows = $('#config-table').find("[data-row-id='" + (rowId -1) + "']").nextAll('tr');
        }

        // Update their row-id and name attributes
        $nextRows.each( function (index, el) {
            $(el).attr( 'data-row-id', rowId);
            updateFormNameAtts( $(el), rowId);
            rowId++;
        });
    });

    /**
     * Function for updating select and input box name
     * attribute under a table-row.
     */
    function updateFormNameAtts( $row, rowId){
        var name, $el;
        $el = $row.find('input, select');
        $el.each(function(index, item) {
            name = $(item).attr('name');

            if ( name != undefined ) {
                // get new name via regex
                name = name.replace(/^fc\[\d+\]/, 'fc[' + rowId + ']');
                $(item).attr('name', name);
            }

        });
    }

    /**
     * Event listener for Attribute type change functionality.
     */
    $(document).on('change', 'select.type-dropdown', function () {
        var selected = $(this).find('option:selected').val();
        if ( selected == 'static' ) {
            $(this).closest('td').next('td').find('.meta-dropdown').hide();
            $(this).closest('td').next('td').find('.static-input').show();
        }else{
            $(this).closest('td').next('td').find('.static-input').hide();
            $(this).closest('td').next('td').find('.meta-dropdown').show();
        }
    });

    /**
     * Event listener for feed type change.
     */

    $(document).on('change', '#rex_feed_merchant', function () {
        var selected = $(this).find('option:selected').val();
        if ( selected == 'google' ) {
            $('.cmb2-id-rex-feed-feed-format').hide();
        }else{
            $('.cmb2-id-rex-feed-feed-format').show();
        }
    });


    /**
     * Event listener for Merchant change functionality.
     */
    $(document).on('change', '#rex_feed_merchant', function () {

        $('.rex-loading-spinner').slideDown('fast');

        var $payload = {
            merchant: $('#rex_feed_merchant').find(':selected').val(),
            post_id: $('#post_ID').val()
            // feed_format: $('#rex_feed_feed_format').find(':selected').val()
        };
        var $confBox = $('#rex-feed-config');

        wpAjaxHelperRequest( 'merchant-change', $payload )
            .success( function( response ) {
                $confBox.fadeOut();
                $confBox.find('#config-table').html( response );
                $('select').material_select();
                $('.rex-loading-spinner').fadeOut('fast');
                $confBox.fadeIn();
            })
            .error( function( response ) {
                console.log( 'Uh, oh! Merchant change returned error!' );
                console.log( response.statusText );
            });
    });

    function get_checkbox_val( name ){
        var items = 'input[name="rex_feed_' + name + '[]"]';
        var vals = [];

        $(items).each( function (){
            if( $(this).prop('checked') == true){
                vals.push( $(this).val() );
            }
        });

        return vals;
    };

    function save_feed(event) {
        event.preventDefault();

        // activate spinner and disable button
        $('#publishing-action span.spinner').addClass('is-active');
        $(this).addClass('disabled');

        var $payload = {
            merchant: $('#rex_feed_merchant').find(':selected').val(),
            feed_format: $('#rex_feed_feed_format').find(':selected').val(),
            info : {
                post_id: $('#post_ID').val(),
                title: $('#title').val(),
                desc: $('#title').val(),
            },

            products: {
                products_scope: $('#rex_feed_products').find(':selected').val(),
                tags: get_checkbox_val('tags'),
                cats: get_checkbox_val('cats'),
                // items: $('#rex_feed_product_items').val().split(',').slice()
            },

            feed_config: $('form').serialize(),
        };

        wpAjaxHelperRequest( 'my-handle', $payload )
            .success( function( response ) {
                console.log( 'Woohoo!' );
                // 'response' will be the response from the handle's callback function, as either a string or JSON.
                console.log( response );
                var msg = '<div id="message" class="error notice notice-error is-dismissible"><p>You feed exceed the limit.Please <a href="edit.php?post_type=product-feed&page=best-woocommerce-feed-pricing">Upgrade!!!</a> </p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                if(response == 'false' || response == ''){
                    $(msg).insertAfter( $( ".wrap .page-title-action" ));
                    $('#publishing-action span.spinner').removeClass('is-active');
                    $('#publish').removeClass('disabled');
                }else {
                    $('#publish').removeClass('disabled');
                    $(document).off( 'click', '#publish', save_feed );
                    $('#publish').trigger( 'click' );
                }

            })
            .error( function( response ) {
                $('#publishing-action span.spinner').removeClass('is-active');
                $('#publish').removeClass('disabled');
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on('click', '#publish', save_feed);


    function category_mapping(event) {
        event.preventDefault();
        var $payload = {
            map_name: $('#map_name').val(),
            cat_map: $('.add_cat_map').serialize(),
        };
        if ($('#map_name').val().length != 0){
            $('.rex-loading-spinner').slideDown('fast');
            wpAjaxHelperRequest( 'category-mapping', $payload )
                .success( function( response ) {
                    $('.rex-loading-spinner').fadeOut('fast');
                    setTimeout(function(){// wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 1000);
                    console.log('ola');
                })
                .error( function( response ) {
                    console.log( 'Uh, oh!' );
                    console.log( response.statusText );
                });
        }else {
            alert('Please Insert Category Map Name')
        }

    }
    $(document).on('click', '#save_mapping_cat', category_mapping);


    function delete_mapping(event) {
        event.preventDefault();
        var container = $(this).closest('.acordion-item');
        var map_name = container.find('.mapper_name_update');
        var $payload = {
            map_name: map_name.text()
        };
        $('.rex-loading-spinner').slideDown('fast');
        wpAjaxHelperRequest( 'category-mapping-delete', $payload )
            .success( function( response ) {
                $('.rex-loading-spinner').fadeOut('fast');
                container.fadeOut();
                console.log('Hello');
            })
            .error( function( response ) {
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });

    }
    $(document).on('click', '#delete_mapping_cat', delete_mapping);



    function category_mapper_accordion(event) {
        $(this).slideDown(500);
        $(this).toggleClass('selected');

        var this_inner = $(this).parent().next();
        var this_a = $(this);

        $(this).parent().next().slideToggle(function() {
            $('.accordion > h2 > a').not(this_a).removeClass('selected');
            $(".inner").not(this_inner).slideUp();
        });
        return false;
    }
    $(document).on('click', '.rex-accordion h6 a', category_mapper_accordion);




    function category_mapping_update(event) {
        event.preventDefault();
        var form = $(this).closest('form');
        var container = $(this).closest('.acordion-item');
        var map_name = container.find('.mapper_name_update');
        var $payload = {
            map_name: map_name.text(),
            cat_map: form.serialize(),
        };
        $('.rex-loading-spinner').slideDown('fast');
        wpAjaxHelperRequest( 'category-mapping-update', $payload )
            .success( function( response ) {
                $('.rex-loading-spinner').fadeOut('fast');
                console.log('ola');
            })
            .error( function( response ) {
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on('click', '#update_mapping_cat', category_mapping_update);

})( jQuery );

