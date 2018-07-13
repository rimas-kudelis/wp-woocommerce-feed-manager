(function( $ ) {
    'use strict';

    var progressWidth = 0;

    $(function() {
        $(".meter > span").each(function() {
            $(this)
                .data("origWidth", $(this).width())
                .width(0)
                .animate({
                    width: $(this).data("origWidth")
                }, 1200);
        });
    });



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
        var rowId = $(this).siblings('#config-table').find('tbody tr').length;
        var lastrow = $(this).siblings('#config-table').find('tbody tr:last');
        var parent = $(this).siblings('#config-table').parent();

        if(parent.hasClass('rex-feed-config-filter')) {
            var filter = true;
        }else {
            filter = false;
        }


        $(this).siblings('#config-table').find('tbody tr:first')
            .clone()
            .insertAfter(lastrow)
            .attr('data-row-id', rowId)


        var $row = $(this).siblings('#config-table').find("[data-row-id='" + rowId + "']");
        $row.find('ul.dropdown-content.select-dropdown, .caret, .select-dropdown ').remove();

        $row.find('input, select').val('');

        updateFormNameAtts( $row, rowId, filter);
        $row.find('select').material_select();


    });


    /**
     * Delete a table-row and update all row-id
     * beneath it and their input attributes names.
     */
    $(document).on('click', '#config-table .delete', function () {
        var $nextRows, rowId;

        var table = $(this).closest('table');
        var parent = table.parent();

        // delete row and get it's row-id
        rowId = $(this).closest('tr').remove().data('row-id');

        if(parent.hasClass('rex-feed-config-filter')) {
            var filter = true;
        }else {
            filter = false;
        }

        // Gell the next rows
        if ( rowId == 0) {
            $nextRows = $('#config-table tbody').children();
        }else{
            $nextRows = $('#config-table').find("[data-row-id='" + (rowId -1) + "']").nextAll('tr');
        }

        // Update their row-id and name attributes
        $nextRows.each( function (index, el) {
            $(el).attr( 'data-row-id', rowId);
            updateFormNameAtts( $(el), rowId, filter);
            rowId++;
        });
    });

    /**
     * Function for updating select and input box name
     * attribute under a table-row.
     */
    function updateFormNameAtts( $row, rowId, filter){
        var name, $el;
        $el = $row.find('input, select');
        $el.each(function(index, item) {
            name = $(item).attr('name');

            if ( name != undefined ) {
                // get new name via regex
                if (filter) {
                    name = name.replace(/^ff\[\d+\]/, 'ff[' + rowId + ']');
                    $(item).attr('name', name);
                }else {
                    name = name.replace(/^fc\[\d+\]/, 'fc[' + rowId + ']');
                    $(item).attr('name', name);
                }

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
     * Event listener for Filter Product.
     */
    $(document).on('change', '#rex_feed_products', function () {
        var selected = $('#rex_feed_products').find(':selected').val();
        if ( selected == 'filter' ) {
            $('.cmb2-id-rex-feed-config-filter-title').show();
            $('#rex-feed-config-filter').show();
        }else{
            $('.cmb2-id-rex-feed-config-filter-title').hide();
            $('#rex-feed-config-filter').hide();
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

        var $confBox = $('.rex-feed-config');

        $confBox.find('.rex-loading-spinner').slideDown('fast');

        var $payload = {
            merchant: $('#rex_feed_merchant').find(':selected').val(),
            post_id: $('#post_ID').val()
            // feed_format: $('#rex_feed_feed_format').find(':selected').val()
        };


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


    // $(document).on('click', '#publish', save_feed);



    function get_product_number(event) {
        event.preventDefault();
        var $payload = {};
        $('#publishing-action span.spinner').addClass('is-active');
        $(this).addClass('disabled');
        $('.bwfm-progressbar, .progress-msg').fadeIn();
        $('.progress-msg span').html('Calculating products.....')

        wpAjaxHelperRequest( 'my-handle', $payload )
            .success( function( response ) {
                console.log('Total Number of Products: ' + response.products);
                generate_feed(response.products, 0, 1);
            })
            .error( function( response ) {
                $('#publishing-action span.spinner').removeClass('is-active');
                $('#publish').removeClass('disabled');
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on('click', '#publish', get_product_number);


    function generate_feed( product, offset, batch ) {
        
        var $payload = {
            merchant: $('#rex_feed_merchant').find(':selected').val(),
            feed_format: $('#rex_feed_feed_format').find(':selected').val(),
            info : {
                post_id     : $('#post_ID').val(),
                title       : $('#title').val(),
                desc        : $('#title').val(),
                offset      : offset,
                batch       : batch
            },

            products: {
                products_scope: $('#rex_feed_products').find(':selected').val(),
                tags: get_checkbox_val('tags'),
                cats: get_checkbox_val('cats'),
                // items: $('#rex_feed_product_items').val().split(',').slice()
            },

            feed_config : $('form').serialize(),

        };
        var batches = Math.ceil( product/100 );
        console.log('Total Batch: '+ batches);
        console.log('Total Product(s): '+ product);
        console.log('Processing Batch Number: '+ batch);
        console.log('Offset Number: '+ offset);

        var progressbar = 100/batches;
        progressWidth = progressWidth + progressbar;
        // feed_progressBar(progressWidth);
        if (progressWidth >= 100) {
            $('.progress-msg span').html('Generating feed. Please wait.....');
        }else {
            $('.progress-msg span').html('Processing feed.....');
        }



        wpAjaxHelperRequest( 'generate-feed', $payload )
            .success( function( response ) {
                console.log( 'Woohoo!' );
                console.log(response);
                var msg = '<div id="message" class="error notice notice-error is-dismissible"><p>You feed exceed the limit.Please <a href="edit.php?post_type=product-feed&page=best-woocommerce-feed-pricing">Upgrade!!!</a> </p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

                if(response == 'false' || response == ''){
                    generate_feed(product, offset, batch);
                }else if (response.msg == 'finish') {
                    feed_progressBar(progressWidth);
                    $('#publish').removeClass('disabled');
                    $(document).off( 'click', '#publish', get_product_number );
                    $('#publish').trigger( 'click' );
                } else {
                    if ( batch < batches ) {
                        setTimeout(function(){
                            offset = offset + 100;
                            batch++;
                            feed_progressBar(progressWidth);
                            generate_feed(product, offset, batch);
                        }, 2000);
                    }
                }
            })
            .error( function( response ) {
                $('#publishing-action span.spinner').removeClass('is-active');
                $('#publish').removeClass('disabled');
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });

    }

    function feed_progressBar(width) {

        $('.progressbar-bar').animate({
            width:width + '%'
        },1000);
        $('.progressbar-bar-percent').html(width+ '%');

    }


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
                    console.log('Woohoo!');
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
                console.log('Woohoo!');
            })
            .error( function( response ) {
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on('click', '#update_mapping_cat', category_mapping_update);

})( jQuery );

