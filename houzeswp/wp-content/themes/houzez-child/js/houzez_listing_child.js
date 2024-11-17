/**
 * Created by EQ on 19/11/24.
 */

function functionShowMore(){
    var elList = document.querySelectorAll('.moreType');
    elList.forEach(el => el.style.display = "block");

    document.querySelector('.page-type-show-more.show-more').style.display = 'none';
    document.querySelector('.page-type-show-more.show-less').style.display = 'block';
}
function functionShowLess(){
    var elList = document.querySelectorAll('.moreType');
    elList.forEach(el => el.style.display = "none");

    document.querySelector('.page-type-show-more.show-more').style.display = 'block';
    document.querySelector('.page-type-show-more.show-less').style.display = 'none';
}

/* ------------------------------------------------------------------------ */
/*  Property phone, whatsapp view track
/* ------------------------------------------------------------------------ */

var propertyViewTrack = function($) {

    //view track
    $('.tracking_view').on('click', function(e){
        //e.preventDefault();
        //console.log("tracking_view");
        var $this = $(this);
        var ajaxurl = houzez_vars.admin_url+ 'admin-ajax.php';
        //var userID = houzez_vars.user_id;
        
        var type = $this.data('type');
        var prop_id = $this.data('prop_id');
        
        var ajax_request = $.ajax({
            type: 'post',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action': 'houzez_add_tracking_views',
                'type': type,
                'prop_id': prop_id
            }
        });

        ajax_request.done(function( response ) {
            if ( response.success ) {

            } else {

            }
        });

        ajax_request.fail(function( jqXHR, textStatus ) {
            //alert( "Request failed: " + textStatus );
        });

    });
}

jQuery(document).ready( function($) {
    "use strict";

    /*--------------------------------------------------------------------------
    *  Property View Tracking
    * -------------------------------------------------------------------------*/
    
    propertyViewTrack($);

});