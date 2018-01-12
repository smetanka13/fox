$(document).ready(function() {

    $('.top_cnt .top_slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        centerMode: true,
        variableWidth: true,
        draggable: false,
        nextArrow: '<button class="slick-next fw-theme-metal-button"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
        prevArrow: '<button class="slick-prev fw-theme-metal-button"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
        responsive: [
            {
                breakpoint: 1250,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 900,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
        ]
    });

});
