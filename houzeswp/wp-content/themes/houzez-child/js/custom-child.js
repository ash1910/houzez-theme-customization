jQuery(document).ready(function ($) {
  var ajaxurl = child_vars.AjaxUrl;
  var userID = child_vars.userID;
  var mu_title_text = child_vars.mu_title_text;
  var mu_type_text = child_vars.mu_type_text;
  var mu_beds_text = child_vars.mu_beds_text;
  var mu_baths_text = child_vars.mu_baths_text;
  var mu_size_text = child_vars.mu_size_text;
  var mu_size_postfix_text = child_vars.mu_size_postfix_text;
  var mu_price_text = child_vars.mu_price_text;
  var mu_availability_text = child_vars.mu_availability_text;

  var confirm_btn_text = child_vars.confirm_btn_text;
  var are_you_sure_text = child_vars.are_you_sure_text;
  var delete_btn_text = child_vars.delete_btn_text;
  var cancel_btn_text = child_vars.cancel_btn_text;
  var processing_text = child_vars.processing_text;
  var featured_listings_none = child_vars.featured_listings_none;


  /* ------------------------------------------------------------------------ */
  /*  Multi Units
  /* ------------------------------------------------------------------------ */
  $( '#add-subproperty-row-custom' ).click(function( e ){
        e.preventDefault();

        var numVal = $(this).data("increment") + 1;
        $(this).data('increment', numVal);
        $(this).attr({
            "data-increment" : numVal
        });

        var newSubProperty = '' +
            '<div class="houzez-units-clone">'+
            '<div class="row">'+
            '<div class="col-md-12 col-sm-12">'+
            '<div class="remove-subproperty-row" data-remove="'+numVal+'">'+
            '<i class="houzez-icon icon-remove-circle mr-2"></i>'+
            '</div>'+
            '<div class="form-group">'+
            '<label for="fave_multi_units['+numVal+'][fave_mu_title]">'+mu_title_text+'</label>'+
            '<input name="fave_multi_units['+numVal+'][fave_mu_title]" type="text" class="form-control">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-6 col-sm-12">'+
            '<div class="form-group">'+
            '<label for="fave_multi_units['+numVal+'][fave_mu_beds]">'+mu_beds_text+'</label>'+
            '<input name="fave_multi_units['+numVal+'][fave_mu_beds]" type="text" class="form-control">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-6 col-sm-12">'+
            '<div class="form-group">'+
            '<label for="fave_multi_units['+numVal+'][fave_mu_baths]">'+mu_baths_text+'</label>'+
            '<input name="fave_multi_units['+numVal+'][fave_mu_baths]" type="text" class="form-control">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-6 col-sm-12">'+
            '<div class="form-group">'+
            '<label for="fave_multi_units['+numVal+'][fave_mu_size]">'+mu_size_text+'</label>'+
            '<input name="fave_multi_units['+numVal+'][fave_mu_size]" type="text" class="form-control">'+
            '</div>'+
            '</div>'+
            
            '<div class="col-md-6 col-sm-12">'+
            '<div class="form-group">'+
            '<label for="fave_multi_units['+numVal+'][fave_mu_price]">'+mu_price_text+'</label>'+
            '<input name="fave_multi_units['+numVal+'][fave_mu_price]" type="text" class="form-control">'+
            '</div>'+
            '</div>'+
            
            '<div class="col-md-6 col-sm-12">'+
            '<div class="form-group">'+
            '<label for="fave_multi_units['+numVal+'][fave_mu_type]">'+mu_type_text+'</label>'+
            '<input name="fave_multi_units['+numVal+'][fave_mu_type]" type="text" class="form-control">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-6 col-sm-12">'+
            '<div class="form-group">'+
            '<label for="fave_multi_units['+numVal+'][fave_mu_availability_date]">'+mu_availability_text+'</label>'+
            '<input name="fave_multi_units['+numVal+'][fave_mu_availability_date]" type="text" class="form-control">'+
            '</div>'+
            '</div>'+
            '</div>'+
            '<hr>'+
            '</div>';

        $( '#multi_units_main').append( newSubProperty );
        removeSubProperty();
    });

    var removeSubProperty = function (){

        $( '.remove-subproperty-row').click(function( event ){
            event.preventDefault();
            var $this = $( this );
            $this.parents( '.houzez-units-clone' ).remove();
        });
    }
    removeSubProperty();


    
        /*--------------------------------------------------------------------------
         *  Property actions
         * -------------------------------------------------------------------------*/
        $('.houzez-prop-action-js_custom').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            var prop_id = $this.attr('data-propid');
            var type = $this.attr('data-type');

            bootbox.confirm({
                message: "<strong>"+are_you_sure_text+"</strong>",
                buttons: {
                    confirm: {
                        label: confirm_btn_text,
                        className: 'btn btn-primary'
                    },
                    cancel: {
                        label: cancel_btn_text,
                        className: 'btn btn-grey-outlined'
                    }
                },
                callback: function (result) {
                    if(result==true) {
                        fave_processing_modal( processing_text );
                        houzez_property_actions_custom(prop_id, $this, type);
                        $this.unbind("click");
                    }
                }
            });
            
        });

        var houzez_property_actions_custom = function( prop_id, currentDiv, type ) {

            var $messages = $('#dash-prop-msg');

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'JSON',
                data: {
                    'action' : 'houzez_property_actions_custom',
                    'propid' : prop_id,
                    'type': type
                },
                success: function ( res ) {

                    if( res.success ) {
                        window.location.reload();
                    } else {
                        houzez_processing_modal_close();
                        $('html, body').animate({
                            scrollTop: $(".dashboard-content-inner-wrap").offset().top
                        }, 'slow');
                        $messages.empty().append('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+featured_listings_none+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }

                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }

            });//end ajax
        }

  
});
