$(document).ready(function(){
	$('.rating').attr('data-star', -1);

	$('.rating svg').mouseover(function() {

		var index = $(this).index();
		for(var i = 0; i <= index; i++)
			$('.rating svg:eq('+i+')').css('fill','#F7931E');

	});

	$('.rating').mouseout(function() {

		if(typeof($('.rating').attr('data-star')) == -1)
			$('.rating svg').css('fill','#212121');
		else {
			for(var i = 5; i >= $('.rating').attr('data-star'); i--)
			$('.rating svg:eq('+i+')').css('fill','#212121');
		}
	});

	$('.rating svg').click(function() {

		$('.rating svg').css('fill','#212121');
		var index = $(this).index();

		$('.rating').attr('data-star', index+1);
		for(var i = 0; i <= index; i++)
			$('.rating svg:eq('+i+')').css('fill','#F7931E');
	});
});
