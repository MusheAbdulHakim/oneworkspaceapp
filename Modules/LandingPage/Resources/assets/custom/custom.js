    $(window).on('load', function(){
        $('#p1 a').miniPreview({ prefetch: 'pageload' });
    });
    
    function init_slider(x){
        if($(".product-left").length){
                var productSlider = new Swiper('.product-slider', {
                    spaceBetween: 0,
                    centeredSlides: false,
                    loop:false,
                    direction: 'horizontal',
                    loopedSlides: 5,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    resizeObserver:true,
                });
                var indexToMove = x;
                productSlider.controller.control = productSlider;
                productSlider.slideTo(indexToMove,1000,false);
        }
    }
    
    $(document).on('click', '.view-images', function () {
        var url = $(this).attr('data-url');
        var title = $(this).attr('data-title');
        var id = $(this).attr('data-id');
        $.ajax({
            url: url,
            type: 'post',
            data: {
                'title': title,
            },
            beforeSend: function () {
                $(".loader-wrapper").removeClass('d-none');
            },
            success: function (res) {
                $(".loader-wrapper").addClass('d-none');
                $('.image_sider_div').html(res);
                // $('#exampleModalCenter').modal('show');  
                $('#exampleModalCenter').appendTo("body").modal('show');
                setTimeout(function(){
                    var total = $('.product-left').find('.product-slider').length
                    if(total > 0){
                        init_slider(id); 
                    }
                },200);
            },
            error: function(xhr){
                $(".loader-wrapper").addClass('d-none');
                toastrs('Error', xhr.responseJSON.error, 'error')
            }
        });
    });