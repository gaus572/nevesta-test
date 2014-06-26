$(function(){
	$('.tags-list span').click(function(e){
		if ($(this).hasClass('active')) $(this).removeClass('active').addClass('exclude');
		else if ($(this).hasClass('exclude')) $(this).removeClass('exclude');
		else $(this).addClass('active');
		
	});

	$('.order-list span').click(function(e){
		$('.order-list span.active').removeClass('active');
		$(this).addClass('active');
	});

	$('.filter-btn').click(function(e){
		e.preventDefault();
		var query = '?order='+$('.order-list span.active').data('order');
		$('.tags-list span.active').each(function(){
			query += '&tag[]='+$(this).data('id');
		});
		$('.tags-list span.exclude').each(function(){
			query += '&notag[]='+$(this).data('id');
		});
		window.location = query; 
	});
});