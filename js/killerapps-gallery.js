(function($) {
	var photoset_grid = $('.killerapps-photoset-grid');
	photoset_grid.photosetGrid({
	  highresLinks: true,
	  gutter: '4px',
	
	  onComplete: function(){
	    photoset_grid.attr('style', '');
	    photoset_grid.find('a').colorbox({
	      photo: true,
	      scalePhotos: true,
	      maxHeight:'90%',
	      maxWidth:'90%'
	    });
	  }
	});
	
})(jQuery);
