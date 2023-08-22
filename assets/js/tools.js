jQuery(document).ready(function($) {

    $( '.chi_forms_import_action' ).on( 'click', function() {
        var file_data = $( '#contactum-forms-import' ).prop( 'files' )[0],
            form_data = new FormData();

        form_data.append( 'importFile', file_data );
        form_data.append( 'action', 'contactum_import_form' );
        form_data.append( 'security', contactum_tools.nonce );

        $.ajax({
            url: contactum_tools.ajaxurl,
            dataType: 'json', // JSON type is expected back from the PHP script.
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST',
            beforeSend: function () {
                var spinner = '<i class="contactum-loading contactum-loading-active"></i>';
                $( '.chi_forms_import_action' ).closest( '.chi_forms_import_action' ).append( spinner );
                $( '.contactum-froms-import_notice' ).remove();
            },
            complete: function( response ) {
                var message_string = '';

                $( '.chi_forms_import_action' ).closest( '.chi_forms_import_action' ).find( '.contactum-loading' ).remove();
                $( '.contactum-froms-import_notice' ).remove();

                if ( true === response.responseJSON.success ) {
                    message_string = '<div id="message" class="updated inline contactum-froms-import_notice"><p><strong>' + response.responseJSON.data + '</strong></p></div>';
                } else {
                    message_string = '<div id="message" class="error inline contactum-froms-import_notice"><p><strong>' + response.responseJSON.data + '</strong></p></div>';
                }

                $( '.contactum-forms-import-form' ).find( 'h3' ).after( message_string );
                $( '#contactum-forms-import' ).val( '' );
            }
        });
    });
});
