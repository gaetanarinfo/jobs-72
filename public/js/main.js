$(function() {

    if ($('.owl-2').length > 0) {
        $('.owl-2').owlCarousel({
            center: true,
            items: 1,
            loop: true,
            stagePadding: 0,
            margin: 20,
            smartSpeed: 1200,
            autoplay: true,
            nav: false,
            dots: false,
            pauseOnHover: false,
            responsive: {
                600: {
                    margin: 20,
                    nav: false,
                    items: 2
                },
                1000: {
                    margin: 20,
                    stagePadding: 0,
                    nav: false,
                    items: 3
                }
            }
        });
    }

})