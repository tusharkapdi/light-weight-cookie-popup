(function ( $ ) {
    'use strict';

    if ( 'set' !== $.cookie( 'lwcp' ) ) {
  
        var addclass = 'notvisible';
        if(lwcp_options['pos'] == 'top'){addclass = 'notvisible2';}
        $("body .cookie-pop").addClass(addclass).delay(1000).queue(function(next){
            $(this).removeClass(addclass).dequeue();
        });

        $( "body" ).on( "click", ".lwcpcb", function() {
            $.cookie( 'lwcp', 'set', { expires: parseInt(lwcp_options['days']) } );
            $( '.cookie-pop' ).remove();
        });      
    }
  
}( jQuery ) );