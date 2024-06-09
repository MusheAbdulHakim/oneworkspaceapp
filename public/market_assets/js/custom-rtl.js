/*  jQuery Nice Select - v1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */
!function(e){e.fn.niceSelect=function(t){function s(t){t.after(e("<div></div>").addClass("nice-select").addClass(t.attr("class")||"").addClass(t.attr("disabled")?"disabled":"").attr("tabindex",t.attr("disabled")?null:"0").html('<span class="current"></span><ul class="list"></ul>'));var s=t.next(),n=t.find("option"),i=t.find("option:selected");s.find(".current").html(i.data("display")||i.text()),n.each(function(t){var n=e(this),i=n.data("display");s.find("ul").append(e("<li></li>").attr("data-value",n.val()).attr("data-display",i||null).addClass("option"+(n.is(":selected")?" selected":"")+(n.is(":disabled")?" disabled":"")).html(n.text()))})}if("string"==typeof t)return"update"==t?this.each(function(){var t=e(this),n=e(this).next(".nice-select"),i=n.hasClass("open");n.length&&(n.remove(),s(t),i&&t.next().trigger("click"))}):"destroy"==t?(this.each(function(){var t=e(this),s=e(this).next(".nice-select");s.length&&(s.remove(),t.css("display",""))}),0==e(".nice-select").length&&e(document).off(".nice_select")):console.log('Method "'+t+'" does not exist.'),this;this.hide(),this.each(function(){var t=e(this);t.next().hasClass("nice-select")||s(t)}),e(document).off(".nice_select"),e(document).on("click.nice_select",".nice-select",function(t){var s=e(this);e(".nice-select").not(s).removeClass("open"),s.toggleClass("open"),s.hasClass("open")?(s.find(".option"),s.find(".focus").removeClass("focus"),s.find(".selected").addClass("focus")):s.focus()}),e(document).on("click.nice_select",function(t){0===e(t.target).closest(".nice-select").length&&e(".nice-select").removeClass("open").find(".option")}),e(document).on("click.nice_select",".nice-select .option:not(.disabled)",function(t){var s=e(this),n=s.closest(".nice-select");n.find(".selected").removeClass("selected"),s.addClass("selected");var i=s.data("display")||s.text();n.find(".current").text(i),n.prev("select").val(s.data("value")).trigger("change")}),e(document).on("keydown.nice_select",".nice-select",function(t){var s=e(this),n=e(s.find(".focus")||s.find(".list .option.selected"));if(32==t.keyCode||13==t.keyCode)return s.hasClass("open")?n.trigger("click"):s.trigger("click"),!1;if(40==t.keyCode){if(s.hasClass("open")){var i=n.nextAll(".option:not(.disabled)").first();i.length>0&&(s.find(".focus").removeClass("focus"),i.addClass("focus"))}else s.trigger("click");return!1}if(38==t.keyCode){if(s.hasClass("open")){var l=n.prevAll(".option:not(.disabled)").first();l.length>0&&(s.find(".focus").removeClass("focus"),l.addClass("focus"))}else s.trigger("click");return!1}if(27==t.keyCode)s.hasClass("open")&&s.trigger("click");else if(9==t.keyCode&&s.hasClass("open"))return!1});var n=document.createElement("a").style;return n.cssText="pointer-events:auto","auto"!==n.pointerEvents&&e("html").addClass("no-csspointerevents"),this}}(jQuery);


$(document).ready(function() {
    /********* On scroll heder Sticky *********/
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= 50) {
            $("header").addClass("head-sticky");
            $(".announcebar").slideUp('slow');
        } else {
            $("header").removeClass("head-sticky");
            $(".announcebar").slideDown('slow');
        }
    }); 
    
    /********* Wrapper top space ********/
    var header_hright = $('header').outerHeight();
    $('header').next('.wrapper').css('margin-top', header_hright + 'px');  
    /********* Announcebar hide ********/
    $('#announceclose').click(function () {
        $('.announcebar').slideUp();
    }); 
    /********* Mobile Menu ********/ 
    $('.mobile-menu-button').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll active-menu');
            $(".mobile-menu-wrapper").toggleClass("active-menu");
            $('.overlay').addClass('menu-overlay');
        }, 50);
    }); 
    $('body').on('click','.overlay.menu-overlay, .menu-close-icon svg', function(e){
        e.preventDefault(); 
        $('body').removeClass('no-scroll active-menu');
        $(".mobile-menu-wrapper").removeClass("active-menu");
        $('.overlay').removeClass('menu-overlay');
    });
    /********* Cart Popup ********/
    $('.cart-header').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
        $('body').addClass('no-scroll cartOpen');
        $('.overlay').addClass('cart-overlay');
        }, 50);
    }); 
    $('body').on('click','.overlay.cart-overlay, .closecart', function(e){
        e.preventDefault(); 
        $('.overlay').removeClass('cart-overlay');
        $('body').removeClass('no-scroll cartOpen');
    });
    /********* Mobile Filter Popup ********/
    $('.filter-title').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
        $('body').addClass('no-scroll filter-open');
        $('.overlay').addClass('active');
        }, 50);
    }); 
    $('body').on('click','.overlay.active, .close-filter', function(e){
        e.preventDefault(); 
        $('.overlay').removeClass('active');
        $('body').removeClass('no-scroll filter-open');
    });
    /*********  Header Search Popup  ********/ 
    $(".search-header a").click(function() { 
        $(".search-popup").toggleClass("active"); 
        $("body").toggleClass("no-scroll");
    });
    $(".close-search").click(function() { 
        $(".search-popup").removeClass("active"); 
        $("body").removeClass("no-scroll");
    });
    /******* Cookie Js *******/
    $('.cookie-close').click(function () {
        $('.cookie').slideUp();
    });
    /******* Subscribe popup Js *******/
    $('.close-sub-btn').click(function () {
        $('.subscribe-popup').slideUp();
    });      
    /********* qty spinner ********/
    var quantity = 0;
    $('.quantity-increment').click(function(){;
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        $(t).val(quantity + 1); 
    }); 
    $('.quantity-decrement').click(function(){
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        if(quantity > 1){
            $(t).val(quantity - 1);
        }
    });   
    /******  Nice Select  ******/ 
    $('select').niceSelect(); 
    /*********  Multi-level accordion nav  ********/ 
    $('.acnav-label').click(function () {
        var label = $(this);
        var parent = label.parent('.has-children');
        var list = label.siblings('.acnav-list');
        if (parent.hasClass('is-open')) {
            list.slideUp('fast');
            parent.removeClass('is-open');
        }
        else {
            list.slideDown('fast');
            parent.addClass('is-open');
        }
    }); 
    /****  TAB Js ****/
    $('ul.tabs li').click(function () {
        var tab_id = $(this).attr('data-tab');
        $(this).closest('.tabs-wrapper').find('.tab-link').removeClass('active');
        $(this).addClass('active');
        $(this).closest('.tabs-wrapper').find('.tab-content').removeClass('active');
        $(this).closest('.tabs-wrapper').find('.tab-content#' + tab_id).addClass('active');
        $(this).closest('.tabs-wrapper').find('.slick-slider').slick('refresh');
    });

    if($('.partners-logo-slider').length > 0 ){
        $('.partners-logo-slider').slick({
            autoplay: true, 
            slidesToShow: 5,
            speed: 1000,
            centerMode:true,
            centerPadding:0,
            slidesToScroll: 1,  
            dots: false,
            arrows:false,
            buttons: false,
            rtl:true,
            responsive: [ 
                {
                    breakpoint: 1200,
                    settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1   
                    }
                },  
                {
                breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1 
                    }
                },
                {
                breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1 
                    }
                }
            ]
        });
    }

    $(document).ready(function() {
        var $slider = $('.review-slider');
        var $progressBar = $('.progress');
        var $progressBarLabel = $( '.slider__label' );
        $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {   
          var calc = ( (nextSlide) / (slick.slideCount-1) ) * 100;
          $progressBar
            .css('background-size', calc + '% 100%')
            .attr('aria-valuenow', calc );
          $progressBarLabel.text( calc + '% completed' );
        });
        $slider.slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          speed: 1000,
          arrows:false,
          rtl:true,
        });  
      });


      if($('.theme-preview-slider').length > 0 ){
        $('.theme-preview-slider').slick({
            autoplay: false, 
            slidesToShow: 1,
            speed: 1000,
            centerMode:true,
            centerPadding:0,
            slidesToScroll: 1,  
            dots: false,
            rtl:true,
            arrows:true,
            buttons: false,
            prevArrow: '<button class="slide-arrow slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="4" height="6" viewBox="0 0 4 6" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z" fill="white"></path></svg></button>',
            nextArrow: '<button class="slide-arrow slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="4" height="6" viewBox="0 0 4 6" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z" fill="white"></path></svg></button>',
        });
    }

    if($('.screenshot-slider').length > 0 ){
        $('.screenshot-slider').slick({
            autoplay: false, 
            slidesToShow: 3,
            speed: 1000,
            centerMode:true,
            centerPadding:0,
            slidesToScroll: 1,  
            rtl:true,
            dots: false,
            arrows:true,
            buttons: false,
            prevArrow: '<button class="slide-arrow slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none"><path d="M12 1.63672C17.928 1.63672 22.75 6.45872 22.75 12.3867C22.75 18.3147 17.928 23.1367 12 23.1367C6.072 23.1367 1.25 18.3147 1.25 12.3867C1.25 6.45872 6.072 1.63672 12 1.63672ZM12 21.6367C17.101 21.6367 21.25 17.4877 21.25 12.3867C21.25 7.28572 17.101 3.13672 12 3.13672C6.899 3.13672 2.75 7.28572 2.75 12.3867C2.75 17.4877 6.899 21.6367 12 21.6367ZM7.25 12.3867C7.25 12.8007 7.586 13.1367 8 13.1367H14.189L12.469 14.8567C12.176 15.1497 12.176 15.6247 12.469 15.9177C12.615 16.0637 12.807 16.1377 12.999 16.1377C13.191 16.1377 13.3831 16.0647 13.5291 15.9177L16.5291 12.9177C16.5981 12.8487 16.6529 12.7658 16.6909 12.6738C16.7669 12.4908 16.7669 12.2838 16.6909 12.1008C16.6529 12.0088 16.5981 11.9257 16.5291 11.8567L13.5291 8.85669C13.2361 8.56369 12.761 8.56369 12.468 8.85669C12.175 9.14969 12.175 9.62472 12.468 9.91772L14.188 11.6377H8C7.586 11.6367 7.25 11.9727 7.25 12.3867Z" fill="#25314C"/></svg></button>',
            nextArrow: '<button class="slide-arrow slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none"><path d="M12 1.63672C17.928 1.63672 22.75 6.45872 22.75 12.3867C22.75 18.3147 17.928 23.1367 12 23.1367C6.072 23.1367 1.25 18.3147 1.25 12.3867C1.25 6.45872 6.072 1.63672 12 1.63672ZM12 21.6367C17.101 21.6367 21.25 17.4877 21.25 12.3867C21.25 7.28572 17.101 3.13672 12 3.13672C6.899 3.13672 2.75 7.28572 2.75 12.3867C2.75 17.4877 6.899 21.6367 12 21.6367ZM7.25 12.3867C7.25 12.8007 7.586 13.1367 8 13.1367H14.189L12.469 14.8567C12.176 15.1497 12.176 15.6247 12.469 15.9177C12.615 16.0637 12.807 16.1377 12.999 16.1377C13.191 16.1377 13.3831 16.0647 13.5291 15.9177L16.5291 12.9177C16.5981 12.8487 16.6529 12.7658 16.6909 12.6738C16.7669 12.4908 16.7669 12.2838 16.6909 12.1008C16.6529 12.0088 16.5981 11.9257 16.5291 11.8567L13.5291 8.85669C13.2361 8.56369 12.761 8.56369 12.468 8.85669C12.175 9.14969 12.175 9.62472 12.468 9.91772L14.188 11.6377H8C7.586 11.6367 7.25 11.9727 7.25 12.3867Z" fill="#25314C"/></svg></button>',
            responsive: [ 
                {
                    breakpoint: 1200,
                    settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1   
                    }
                },  
                {
                breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1 
                    }
                },
                {
                    breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1 
                        }
                    },
                
                {
                breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1 
                    }
                }
            ]
        });
    }
    
});

