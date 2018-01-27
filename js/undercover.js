// $Id: undercover.js

(function() {
 
  /**
   * Implementation of Drupal behavior.
   */
  
  Drupal.behaviors.undercover = function( context ) {
    Drupal.undercover.init( context );
  };

  Drupal.undercover = {
    'init' : function ( context ) {
	  if ( jQuery('body').hasClass('node-type-published-article') ) {
        jQuery('#excerpt').truncate( { 
          'max_length' : 300,
          'more': Drupal.t('Read more') 
        });
	  }
	  
	  if ( jQuery('body').hasClass('front') && $('#block-views-clusters_carousel-block_1').length ) {
        var carousel = $('<ul id="mycarousel" class="jcarousel-skin-tango"></ul>');        
        jQuery('#block-views-clusters_carousel-block_1').append( carousel );
        
        jQuery('#block-views-clusters_carousel-block_1 .view-content .views-row').each( function( index ) {
          var li = $('<li id="li'+ index +'"/>');          
              li.append( $( this ).find('a') );
              li.appendTo( carousel );
       });
       
       carousel.jcarousel({ wrap: 'circular', visible : 1 });       
	  }
    }
  };
  
})();