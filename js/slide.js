var slideNow = 1;
var slideCount = 0;
var slideInterval = 3000;

$(document).ready(function() {
    slideCount = $('#slidewrapper').children().length;
    var switchInterval = setInterval(nextSlide, slideInterval);

    $('#viewport').hover(function() {
        clearInterval(switchInterval);
    }, function() {
        switchInterval = setInterval(nextSlide, slideInterval);
    });

    $('#next-btn').click(function() {
        nextSlide();
    });

    $('#prev-btn').click(function() {
        prevSlide();
    });

    $('#viewport').height($('html').height() - 280);
    $('html').resize(function() {
    	$('#viewport').height($('html').height() - 280);
    });
});


function nextSlide() {

    if(slideNow >= slideCount) {
        slideNow = 1;
    } else {
        slideNow++;
    }

    $('#slidewrapper li').fadeOut(300);
    $('#slidewrapper li:eq('+(slideNow - 1)+')').fadeIn(300);
}

function prevSlide() {
    if(slideNow <= 1) {
        slideNow = slideCount;
    } else {
        slideNow--;
    }

    $('#slidewrapper li').fadeOut(300);
    $('#slidewrapper li:eq('+(slideNow - 1)+')').fadeIn(300);
}
