jQuery(function($) {
	var $container = $('.content');

	$container.imagesLoaded( function(){
		$container.masonry({
			itemSelector: '.entry',

			// use elements for options
			columnWidth: '.grid-sizer',
			gutterWidth: '.gutter-width',

			percentPosition: true
		});
	});
});
