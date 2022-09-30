</div><!-- /#site-content -->

<?php
// you can find this and all other server-side markup renderering functions in ./inc/renderers.php
site_footer(); ?>
</div><!-- /#page -->

<?php wp_footer(); ?>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
    jQuery(document).ready(function ($){
        setTimeout(() => {
            $('.case_studies_with_category_items').slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 500,
            autoplay: true,
            autoplaySpeed: 2000,
            slidesToShow: 3,
            centerMode: true,
            centerPadding: '0px',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '00px',
                    slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                    arrows: false,
                    centerMode: false,
                    slidesToShow: 1
                    }
                }
            ]
        });
        }, 3000);
        
        // setTimeout(() => {
        //     $('.case-studies__items').slick({
        //     dots: false,
        //     arrows: false,
        //     infinite: true,
        //     speed: 500,
        //     autoplay: false,
        //     slidesToShow: 3,
        //     responsive: [
        //         {
        //             breakpoint: 768,
        //             settings: {
        //             arrows: false,
        //             centerMode: true,
        //             centerPadding: '00px',
        //             slidesToShow: 3
        //             }
        //         },
        //         {
        //             breakpoint: 480,
        //             settings: {
        //             arrows: false,
        //             centerMode: false,
        //             slidesToShow: 1
        //             }
        //         }
        //     ]
        // });
        // }, 5000);
        
        setInterval(() => {
            if($(window).width() < 500){
                $('.card-row__cards').slick({
                    dots: false,
                    arrows: false,
                    infinite: true,
                    speed: 500,
                });
                $('.client-results__clients').slick({
                    dots: false,
                    arrows: false,
                    infinite: true,
                    speed: 500,
                });
                
            }
            else{
                $('.card-row__cards').slick('unslick')
                $('.client-results__clients').slick('unslick')
            }
        }, 500);

    })
</script>
</body>

</html>