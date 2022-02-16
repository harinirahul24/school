jQuery(document).ready(function() {
	var $masonry = jQuery('.blog-style-masonry:not(.sh-recent-posts-list-carousel)').isotope({
		itemSelector: '.post-item',
		columnWidth: 0,
		gutter: 0,
	}).isotope('reloadItems');
	jQuery(window).load(function (){
		setTimeout(function(){
			$masonry.isotope('layout');
		}, 0);
	});
/*** Samba codes */
jQuery('.hfn-input-group textarea, .hfn-input-group input[type="text"], .hfn-input-group input[type="email"], .hfn-input-group input[type="number"]').focus(function(){
jQuery(this).addClass('active');
jQuery(this).parent().parent().find('label').addClass('active');
});
jQuery('.hfn-input-group textarea, .hfn-input-group input[type="text"], .hfn-input-group input[type="email"], .hfn-input-group input[type="number"]').focusout(function(){
if(jQuery(this).val().length == 0) {
jQuery(this).removeClass('active');
jQuery(this).parent().parent().find('label').removeClass('active');
}

		    // jQuery(".hfn-social-icons a").click(function(){
		    //     jQuery(this).parent().parent().find(".hfn-blog-share-pop").toggle('slide');
		    // });

});
    var opened_item = ''; 
    jQuery(".hfn-social-icons a").on('click', function(e){ 
      if (opened_item != '' && opened_item != jQuery(this).parent().parent().find(".hfn-blog-share-pop") && !jQuery(this).parent().parent().find(".hfn-blog-share-pop:visible").length) {
console.log("friring from hiding dept");
        jQuery(".hfn-social-icons a").parent().parent().find(".hfn-blog-share-pop").hide('slide');
      }
      jQuery(this).parent().parent().find(".hfn-blog-share-pop").toggle('slide');
      opened_item = jQuery(this).parent().parent().find(".hfn-blog-share-pop");
    });
    jQuery('body').on('click touchstart', function(e){
      if (opened_item != '' && !jQuery(e.srcElement).hasClass('hfn-blog-share-icon') && !jQuery(e.srcElement).hasClass('essb_network_name') &&  !jQuery(e.srcElement).hasClass('essb_icon') && opened_item.is(':visible')) {
       opened_item.toggle('slide');
      }
    });

});
