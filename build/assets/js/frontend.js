;(function($, window) {
    window.PRAEF = {

        init: function() {
            $('.prafe-form-add').on('submit', this.formSubmit);
        },

        formSubmit: function(e) {
            e.preventDefault();
            let form = $(this),
                submitButton = form.find('input[type=submit]')
                form_data = PRAEF.validateForm(form);

            if (form_data) {
                 form.find('li.prafe-submit').append('<span class="prafe-loading"></span>');
                 submitButton.attr('disabled', 'disabled').addClass('button-primary-disabled');

                 $.post(prafe_frontend.ajaxurl, form_data, function(res) {
                    if ( res.success) {
                        $('body').trigger('prafeform:success', res);

                        if ( res.show_message == true) {
                                form.before( '<div class="prafe-success">' + res.message + '</div>');
                                form.slideUp( 'fast', function() {
                                    form.remove();
                                });

                                //focus
                                $('html, body').animate({
                                    scrollTop: $('.prafe-success').offset().top - 100
                                }, 'fast');

                        } else {
                            window.location = res.redirect_to;
                        }

                    } else {

                            if ( typeof res.type !== 'undefined' && res.type === 'login' ) {

                                if ( confirm(res.error) ) {
                                    window.location = res.redirect_to;
                                } else {
                                    submitButton.removeAttr('disabled');
                                    submitButton.removeClass('button-primary-disabled');
                                    form.find('span.prafe-loading').remove();
                                }

                                return;
                            } else {

                                if ( form.find('.g-recaptcha').length > 0 ) {
                                    grecaptcha.reset();
                                }

                                swal({
                                    html: res.error,
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonColor: '#d54e21',
                                    confirmButtonText: 'OK',
                                    cancelButtonClass: 'btn btn-danger',
                                });
                            }
                            submitButton.removeAttr('disabled');
                        }

                        submitButton.removeClass('button-primary-disabled');
                        form.find('span.prafe-loading').remove();
                    });
                }
        },

        validateForm: function( self ) {

            let temp,
                temp_val    = '',
                error       = false,
                error_items = [];
                error_type  = '';

            // remove all initial errors if any
            PRAEF.removeErrors(self);
            PRAEF.removeErrorNotice(self);

            // ===== Validate: Text and Textarea ========
            // var required = self.find('[data-required="yes"]:visible');
            var required = self.find('[data-required="yes"]');

            required.each(function(i, item) {
                console.log('check item');
                console.log(item);
                let data_type = $(item).data('type')
                // let error_message = $(item).data('errormessage');
                let error_message = $(item).closest('li').data('errormessage');
                    val = '';
                switch(data_type) {
                    case 'textarea':
                    case 'text':
                        val = $.trim( $(item).val() );
                        if ( val === '') {
                            error = true;
                            error_type = 'required';
                            // make it warn collor
                            PRAEF.markError( item, error_type, error_message );
                        }
                        break;
                    case 'select':
                        val = $(item).val();

                        // console.log(val);
                        if ( !val || val === '-1' ) {
                            error = true;
                            error_type = 'required';

                            // make it warn collor
                            PRAEF.markError( item, error_type, error_message );
                        }
                        break;
                    case 'multiselect':
                        val = $(item).val();

                        if ( val === null || val.length === 0 ) {
                            error = true;
                            error_type = 'required';

                            // make it warn collor
                            PRAEF.markError( item,  error_type, error_message );
                        }
                        break;

                    case 'radio':
                    case 'checkbox':
                        var length = $(item).find('input:checked').length;

                        if ( !length ) {
                            error = true;
                            error_type = 'required';

                            // make it warn collor
                            PRAEF.markError( item,  error_type, error_message );
                        }
                        break;

                    case 'file':
                        var length = $(item).find('ul').children().length;

                        if ( !length ) {
                            error = true;
                            error_type = 'required';

                            // make it warn collor
                            PRAEF.markError( item,  error_type, error_message );
                        }
                        break;

                    case 'email':
                        var val = $(item).val();

                        if ( val !== '' ) {
                            //run the validation
                            if( !PRAEF.isValidEmail( val ) ) {
                                error = true;
                                error_type = 'validation';

                                PRAEF.markError( item,  error_type );
                            }
                        } else if( val === '' ) {
                            error = true;
                            error_type = 'required';

                            PRAEF.markError( item,  error_type, error_message );
                        }
                        break;


                    case 'url':
                        var val = $(item).val();

                        if ( val !== '' ) {
                            //run the validation
                            if( !PRAEF.isValidURL( val ) ) {
                                error = true;
                                error_type = 'validation';

                                PRAEF.markError( item,  error_type );
                            }
                        } else if( val === '' ) {
                            error = true;
                            error_type = 'required';
                            PRAEF.markError( item,  error_type, error_message )
                        }
                        break;
                };

            });

            //check Google Map is required
            var map_required = self.find('[data-required="yes"][name="google_map"]');
            if ( map_required ) {
                var val = $(map_required).val();
                if ( val == ',' ) {
                    error = true;
                    error_type = 'required';

                    PRAEF.markError( map_required,  error_type );
                }
            }

            // if already some error found, bail out
            if (error) {
                // add error notice
                PRAEF.addErrorNotice(self,'end');
                return false;
            }

            let form_data = self.serialize();

            return form_data;
        },

        addErrorNotice: function( form, position ) {
            $(form).find('li.prafe-submit').append('<div class="prafe-errors">' + prafe_frontend.error_message + '</div>');
        },

        removeErrorNotice: function(form) {
            $(form).find('.prafe-errors').remove();
        },

        markError: function(item, error_type, error_message = null) {
            var error_string = '';
            $(item).closest('li').addClass('has-error');

            if ( error_type ) {
                error_string = $(item).closest('li').data('label');

                if( error_message != null ) {
                    error_string = error_message;
                } else {
                    switch ( error_type ) {
                        case 'required' :
                            error_string = error_string + ' ' + error_str_obj[error_type];
                            break;
                        case 'mismatch' :
                            error_string = error_string + ' ' +error_str_obj[error_type];
                            break;
                        case 'validation' :
                            error_string = error_string + ' ' + error_str_obj[error_type];
                            break
                    }
                }
                $(item).siblings('.prafe-error-msg').remove();
                $(item).after('<div class="prafe-error-msg">'+ error_string +'</div>')
            }

            $(item).focus();
        },

        removeErrors: function(item) {
            $(item).find('.has-error').removeClass('has-error');
            $('.prafe-error-msg').remove();
        },

        isValidEmail: function( email ) {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
            return pattern.test(email);
        },

        isValidURL: function(url) {
            var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.|http:\/\/|https:\/\/){1}([0-9A-Za-z]+\.)");
            return urlregex.test(url);
        },
    };

    jQuery(document).ready(function($) {
        // PRAEF.init();
    });

})(jQuery, window);
