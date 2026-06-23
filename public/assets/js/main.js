/**
    Item Name: Ekka - Ecommerce HTML Template.
    Author: ashishmaraviya
    Version: 3.7
    Copyright 2024
    Author URI: https://themeforest.net/user/ashishmaraviya
**/

// Function To Create New Cookie 
function ecCreateCookie(cookieName, cookieValue, daysToExpire) {
    var date = new Date();
    date.setTime(date.getTime() + (daysToExpire * 24 * 60 * 60 * 1000));
    document.cookie = cookieName + "=" + cookieValue + "; expires=" + date.toGMTString();
}

// Function To Delete Existing Cookie
function ecDeleteCookie(cookieName, cookieValue) {
    var date = new Date(0).toGMTString();
    document.cookie = cookieName + "=" + cookieValue + "; expires=" + date;
}

// Function To Access Existing Cookie
function ecAccessCookie(cookieName) {
    var name = cookieName + "=";
    var allCookieArray = document.cookie.split(';');
    for (var i = 0; i < allCookieArray.length; i++) {
        var temp = allCookieArray[i].trim();
        if (temp.indexOf(name) == 0) {
            return temp.substring(name.length, temp.length);
        }
    }

    return "";
}

// Function To Check Existing Cookie
function ecCheckCookie() {
    var bgImageMode = ecAccessCookie("bgImageModeCookie");
    if (bgImageMode != "") {
        var bgIDClass = bgImageMode.split('||');
        var bgID = bgIDClass[0];
        var bgClass = bgIDClass[1];

        $("body").removeClass("body-bg-1");
        $("body").removeClass("body-bg-2");
        $("body").removeClass("body-bg-3");
        $("body").removeClass("body-bg-4");

        $("body").addClass(bgClass);

        $("#bg-switcher-css").attr("href", "assets/css/backgrounds/" + bgID + ".css");
    }

    var rtlMode = ecAccessCookie("rtlModeCookie");
    if (rtlMode != "") {
        // alert(rtlMode);    
        var $link = $('<link>', {
            rel: 'stylesheet',
            href: 'assets/css/rtl.css',
            class: 'rtl'
        });
        $(".ec-tools-sidebar .ec-change-rtl").toggleClass('active');
        $link.appendTo('head');
    }

    // ecCreateCookie('bgImgModeCookie',bgIDClass,1);

    var darkMode = ecAccessCookie("darkModeCookie");
    if (darkMode != "") {
        var $link = $('<link>', {
            rel: 'stylesheet',
            href: 'assets/css/dark.css',
            class: 'dark'
        });

        $("link[href='assets/css/responsive.css']").before($link);

        $(".ec-tools-sidebar .ec-change-mode").toggleClass('active');
        $("body").addClass("dark");
    }
    else {
        var themeColor = ecAccessCookie("themeColorCookie");
        if (themeColor != "") {
            $('li[data-color = ' + themeColor + ']').toggleClass('active').siblings().removeClass('active');
            $('li[data-color = ' + themeColor + ']').addClass('active');

            if (themeColor != '01') {
                $("link[href='assets/css/responsive.css']").before('<link rel="stylesheet" href="assets/css/skin-' + themeColor + '.css" rel="stylesheet">');
            }
        }
    }
}

(function ($) {
    "use strict";

    /*----------------------------- Site Cookie function --------------------*/
    // Calling Function On Each Time Site Load | Reload
    ecCheckCookie();

    // On click method for Clear Cookie
    $(".clear-cach").on("click", function (e) {
        ecDeleteCookie("rtlModeCookie", "");
        ecDeleteCookie("darkModeCookie", "");
        ecDeleteCookie("themeColorCookie", "");
        ecDeleteCookie("bgImageModeCookie", "");
        location.reload();
    });

    /*----------------------------- Site Loader & Popup --------------------*/
    $(window).load(function () {
        $("#ec-overlay").fadeOut("slow");
    });

    /*--------------------- Search Bar On Focus -------------------------------- */
    $(".ec-search-bar").focus(function () {
        $(".ec-search-tab").addClass("active");
    });

    $(".ec-search-bar").focusout(function () {
        setTimeout(function () {
            $(".ec-search-tab").removeClass("active");
        }, 100);
    });

    /*----------------------------- Animate On Scroll --------------------*/
    var Animation = function ({ offset } = { offset: 10 }) {
        var _elements;

        // Define a dobra superior, inferior e laterais da tela
        var windowTop = offset * window.innerHeight / 100;
        var windowBottom = window.innerHeight - windowTop;
        var windowLeft = 0;
        var windowRight = window.innerWidth;

        function start(element) {
            // Seta os atributos customizados
            element.style.animationDelay = element.dataset.animationDelay;
            element.style.animationDuration = element.dataset.animationDuration;
            // Inicia a animacao setando a classe da animacao
            element.classList.add(element.dataset.animation);
            // Seta o elemento como animado
            element.dataset.animated = "true";
        }

        function isElementOnScreen(element) {
            // Obtem o boundingbox do elemento
            var elementRect = element.getBoundingClientRect();
            var elementTop =
                elementRect.top + parseInt(element.dataset.animationOffset) ||
                elementRect.top;
            var elementBottom =
                elementRect.bottom - parseInt(element.dataset.animationOffset) ||
                elementRect.bottom;
            var elementLeft = elementRect.left;
            var elementRight = elementRect.right;

            // Verifica se o elemento esta na tela
            return (
                elementTop <= windowBottom &&
                elementBottom >= windowTop &&
                elementLeft <= windowRight &&
                elementRight >= windowLeft
            );
        }
        var els = _elements = document.querySelectorAll(
            "[data-animation]:not([data-animated])"
        );
        // Percorre o array de elementos, verifica se o elemento está na tela e inicia animação
        function checkElementsOnScreen(_elements) {
            var els = _elements;
            for (var i = 0, len = els.length; i < len; i++) {
                // Passa para o proximo laço se o elemento ja estiver animado
                if (els[i].dataset.animated) continue;

                isElementOnScreen(els[i]) && start(els[i]);
            }
        }

        // Atualiza a lista de elementos a serem animados
        function update() {
            _elements = document.querySelectorAll(
                "[data-animation]:not([data-animated])"
            );
            checkElementsOnScreen(_elements);
        }

        // Inicia os eventos
        window.addEventListener("load", update, false);
        window.addEventListener("scroll", () => checkElementsOnScreen(_elements), { passive: true });
        window.addEventListener("resize", () => checkElementsOnScreen(_elements), false);

        // Retorna funcoes publicas
        return {
            start,
            isElementOnScreen,
            update
        };
    };

    // Initialize
    var options = {
        offset: 20 //percentage of window
    };

    var animation = new Animation(options);

    /*----------------------------- Stickey headre on scroll &&  Menu Fixed On Scroll Active  --------------------*/
    var doc = document.documentElement;
    var w = window;

    var ecprevScroll = w.scrollY || doc.scrollTop;
    var eccurScroll;
    var ecdirection = 0;
    var ecprevDirection = 0;
    var ecscroll_top = $(window).scrollTop() + 1;
    var echeader = document.getElementById('ec-main-menu-desk');

    var checkScroll = function () {

        eccurScroll = w.scrollY || doc.scrollTop;
        if (eccurScroll > ecprevScroll) {
            //scrolled up
            ecdirection = 2;
        }
        else if (eccurScroll < ecprevScroll) {
            //scrolled down
            ecdirection = 1;
        }

        if (ecdirection !== ecprevDirection) {
            toggleHeader(ecdirection, eccurScroll);
        }

        ecprevScroll = eccurScroll;
    };

    var toggleHeader = function (ecdirection, eccurScroll) {

        if (ecdirection === 2 && eccurScroll > 52) {
            // echeader.classList.add('hide');
            ecprevDirection = ecdirection;
            $("#ec-main-menu-desk").addClass("menu_fixed_up");
            // $("#ec-main-menu-desk").removeClass("menu_fixed");
        }
        else if (ecdirection === 1) {
            // echeader.classList.remove('hide');
            ecprevDirection = ecdirection;
            $("#ec-main-menu-desk").addClass("menu_fixed");
            $("#ec-main-menu-desk").removeClass("menu_fixed_up");
        }
    };

    $(window).on("scroll", function () {
        var distance = $('.sticky-header-next-sec').offset().top,
            $window = $(window);

        if ($window.scrollTop() <= distance + 50) {
            // alert("1");
            $("#ec-main-menu-desk").removeClass("menu_fixed");
        }
        else {
            // alert("2");
            checkScroll();
        }
    });


    /*-----------------------------  Navigation for scroll section to section  --------------------*/
    $(document).ready(function () {
        $('.scroll-to ul li a.nav-scroll').bind('click', function (e) {
            $('.scroll-to ul li').removeClass('active');
            $(this).parents('li').addClass('active');

            var target_ID = $(this).attr('data-scroll');
            // alert(target_ID);
            $('html, body').animate({
                scrollTop: $("#" + target_ID).offset().top - 50
            }, 500);
        });
    });

    /*----------------------------- Bootstrap dropdown   --------------------*/
    $('.dropdown').on('show.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    $('.dropdown').on('hide.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });

    /*----------------------------- Language and Currency Click to Active -------------------------------- */
    $(document).ready(function () {
        $(".header-top-lan li").click(function () {
            $(this).addClass('active').siblings().removeClass('active');
        });
        $(".header-top-curr li").click(function () {
            $(this).addClass('active').siblings().removeClass('active');
        });
    });

    /*----------------------------- Toggle Search Bar --------------------- */
    $(".search-btn").on("click", function () {
        $(this).toggleClass('active');
        $('.dropdown_search').slideToggle('medium');
    });

    /*----------------------------- Sidebar js | Toggle Icon OnClick Open sidebar  -----------------------------------*/

    $(".ec-sidebar-toggle").on("click", function () {
        $(".ec-side-cat-overlay").fadeIn();
        $(".category-sidebar").addClass("ec-open");
    });

    $(".ec-close").on("click", function () {
        $(".category-sidebar").removeClass("ec-open");
        $(".ec-side-cat-overlay").fadeOut();
    });

    $(".ec-side-cat-overlay").on("click", function () {
        $(".category-sidebar").removeClass("ec-open");
        $(".ec-side-cat-overlay").fadeOut();
    });

    /*----------------------------- Product page category Toggle -------------------------------- */
    $(document).ready(function () {
        $(".ec-sidebar-block .ec-sb-block-content ul li ul").addClass("ec-cat-sub-dropdown");

        $(".ec-sidebar-block .ec-sidebar-block-item").on("click", function () {
            var $this = $(this).closest('.ec-sb-block-content').find('.ec-cat-sub-dropdown');
            $this.slideToggle('slow');
            $('.ec-cat-sub-dropdown').not($this).slideUp('slow');
        });
    });

    /*----------------------------- Siderbar Product Slider -------------------------------- */
    $(document).ready(function () {
        $('.ec-sidebar-slider-cat .ec-sb-pro-sl').slick({
            rows: 4,
            dots: false,
            arrows: true,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 8000,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        rows: 4,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        rows: 4,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false
                    }
                }
            ]
        });
    });

    /*----------------------------- Instagram slider & Category slider & Tooltips -----------------------------------*/
    $(function () {
        $('.insta-auto, .cat-auto').infiniteslide({
            direction: 'left',
            speed: 50,
            clone: 10
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    /*----------------------------- Filter Icon OnClick Open filter Sidebar on shop page -----------------------------------*/
    $('#shop_sidebar').stickySidebar({
        topSpacing: 30,
        bottomSpacing: 30
    });

    $(".sidebar-toggle-icon").on("click", function () {
        $(".filter-sidebar-overlay").fadeIn();
        $(".filter-sidebar").addClass("toggle-sidebar-swipe");
    });

    $(".filter-cls-btn").on("click", function () {
        $(".filter-sidebar").removeClass("toggle-sidebar-swipe");
        $(".filter-sidebar-overlay").fadeOut();
    });

    $(".filter-sidebar-overlay").on("click", function () {
        $(".filter-sidebar").removeClass("toggle-sidebar-swipe");
        $(".filter-sidebar-overlay").fadeOut();
    });

    /*----------------------------- Remove product on compare and wishlish page -----------------------------------*/
    $(".ec-remove-wish").on("click", function () {
        $(this).parents(".pro-gl-content").remove();
        var wish_product_count = $(".pro-gl-content").length;
        if (wish_product_count == 0) {
            $('.ec-wish-rightside, .wish-empt').html('<p class="emp-wishlist-msg">Your wishlist is empty!</p>');
        }
    });

    $(".ec-remove-compare").on("click", function () {
        $(this).parents(".pro-gl-content").remove();
        var comp_product_count = $(".pro-gl-content").length;
        if (comp_product_count == 0) {
            $('.ec-compare-rightside').html('<p class="emp-wishlist-msg">Your Compare list is empty!</p>');
        }
    });

    /*----------------------------- Sidekka And SideMenu -----------------------------------*/
    $("body").on("click", ".add-to-cart", function () {

        $(".ec-cart-float").fadeIn();

        var count = $(".cart-count-lable").html();
        count++;

        $(".cart-count-lable").html(count);

        // Remove Empty message    
        $(".emp-cart-msg").parent().remove();

        setTimeout(function () {
            $(".ec-cart-float").fadeOut();
        }, 5000);

        // get an image url
        var img_url = $(this).parents().parents().children(".image").find(".main-image").attr("src");
        var p_name = $(this).parents().parents().parents().children(".ec-pro-content").children("h5").children("a").html();
        var p_price = $(this).parents().parents().parents().children(".ec-pro-content").children(".ec-price").children(".new-price").html();

        var p_html = '<li>' +
            '<a href="product-left-sidebar.html" class="sidekka_pro_img"><img src="' + img_url + '" alt="product"></a>' +
            '<div class="ec-pro-content">' +
            '<a href="product-left-sidebar.html" class="cart_pro_title">' + p_name + '</a>' +
            '<span class="cart-price"><span>' + p_price + '</span> x 1</span>' +
            '<div class="qty-plus-minus"><div class="dec ec_qtybtn">-</div>' +
            '<input class="qty-input" type="text" name="ec_qtybtn" value="1">' +
            '<div class="inc ec_qtybtn">+</div></div>' +
            '<a href="javascript:void(0)" class="remove">×</a>' +
            '</div>' +
            '</li>';

        $('.eccart-pro-items').append(p_html);

    });

    (function () {
        var $ekkaToggle = $(".ec-side-toggle"),
            $ekka = $(".ec-side-cart"),
            $ecMenuToggle = $(".mobile-menu-toggle");

        $ekkaToggle.on("click", function (e) {
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr("href");
            // $("body").addClass("ec-open");
            $(".ec-side-cart-overlay").fadeIn();
            $($target).addClass("ec-open");
            if ($this.parent().hasClass("mobile-menu-toggle")) {
                $this.addClass("close");
                $(".ec-side-cart-overlay").fadeOut();
            }
        });

        $(".ec-side-cart-overlay").on("click", function (e) {
            $(".ec-side-cart-overlay").fadeOut();
            $ekka.removeClass("ec-open");
            $ecMenuToggle.find("a").removeClass("close");
        });

        $(".ec-close").on("click", function (e) {
            e.preventDefault();
            $(".ec-side-cart-overlay").fadeOut();
            $ekka.removeClass("ec-open");
            $ecMenuToggle.find("a").removeClass("close");
        });

        $("body").on("click", ".ec-pro-content .remove", function () {

            // $(".ec-pro-content .remove").on("click", function () {

            var cart_product_count = $(".eccart-pro-items li").length;

            $(this).closest("li").remove();
            if (cart_product_count == 1) {
                $('.eccart-pro-items').html('<li><p class="emp-cart-msg">Your cart is empty!</p></li>');
            }

            var count = $(".cart-count-lable").html();
            count--;
            $(".cart-count-lable").html(count);

            cart_product_count--;
        });

    })();

    /*----------------------------- ekka Responsive Menu -----------------------------------*/
    function ResponsiveMobileekkaMenu() {
        var $ekkaNav = $(".ec-menu-content, .overlay-menu"),
            $ekkaNavSubMenu = $ekkaNav.find(".sub-menu");
        $ekkaNavSubMenu.parent().prepend('<span class="menu-toggle"></span>');

        $ekkaNav.on("click", "li a, .menu-toggle", function (e) {
            var $this = $(this);
            if ($this.attr("href") === "#" || $this.hasClass("menu-toggle")) {
                e.preventDefault();
                if ($this.siblings("ul:visible").length) {
                    $this.parent("li").removeClass("active");
                    $this.siblings("ul").slideUp();
                    $this.parent("li").find("li").removeClass("active");
                    $this.parent("li").find("ul:visible").slideUp();
                } else {
                    $this.parent("li").addClass("active");
                    $this.closest("li").siblings("li").removeClass("active").find("li").removeClass("active");
                    $this.closest("li").siblings("li").find("ul:visible").slideUp();
                    $this.siblings("ul").slideDown();
                }
            }
        });
    }

    ResponsiveMobileekkaMenu();

    /*----------------------------- Main Slider ---------------------- */
    var EcMainSlider = new Swiper('.ec-slider.swiper-container', {
        loop: true,
        speed: 2000,
        effect: "slide",
        autoplay: {
            delay: 7000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });

    /*----------------------------- Quick view Slider ------------------------------ */
    $('.qty-product-cover').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: false,
        asNavFor: '.qty-nav-thumb',
    });

    $('.qty-nav-thumb').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.qty-product-cover',
        dots: false,
        arrows: true,
        focusOnSelect: true,
        responsive: [
            {
                breakpoint: 479,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 2,
                }
            }
        ]
    });

    /*----------------------------- Product Image Zoom --------------------------------*/
    $('.zoom-image-hover').zoom();

    /*----------------------------- Qty Plus Minus Button  ------------------------------ */
    var QtyPlusMinus = $(".qty-plus-minus");
    QtyPlusMinus.prepend('<div class="dec ec_qtybtn">-</div>');
    QtyPlusMinus.append('<div class="inc ec_qtybtn">+</div>');

    $("body").on("click", ".ec_qtybtn", function () {

        // $(".ec_qtybtn").on("click", function() {
        var $qtybutton = $(this);
        var QtyoldValue = $qtybutton.parent().find("input").val();
        if ($qtybutton.text() === "+") {
            var QtynewVal = parseFloat(QtyoldValue) + 1;
        } else {

            if (QtyoldValue > 1) {
                var QtynewVal = parseFloat(QtyoldValue) - 1;
            } else {
                QtynewVal = 1;
            }
        }
        $qtybutton.parent().find("input").val(QtynewVal);
    });

    /*----------------------------- Single Product Slider ---------------------------------*/
    var swiper = new Swiper(".single-product-slider", {
        slidesPerView: 4,
        spaceBetween: 20,
        speed: 1500,
        loop: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            478: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 4,
            },
        },
    });

    /*----------------------------- Scroll Up Button --------------------- */
    $.scrollUp({
        scrollText: '<i class="ecicon eci-arrow-up" aria-hidden="true"></i>',
        easingType: "linear",
        scrollSpeed: 900,
        animation: "fade",
    });

    /*----------------------------- Product Countdown --------------------- */
    $("#ec-fs-count-1").countdowntimer({
        startDate: "2024/01/01 00:00:00",
        dateAndTime: "2025/01/01 00:00:00",
        labelsFormat: true,
        displayFormat: "DHMS"
    });

    $("#ec-fs-count-2").countdowntimer({
        startDate: "2024/01/01 00:00:00",
        dateAndTime: "2025/12/01 00:00:00",
        labelsFormat: true,
        displayFormat: "DHMS"
    });

    $("#ec-fs-count-3").countdowntimer({
        startDate: "2024/01/01 00:00:00",
        dateAndTime: "2025/11/01 00:00:00",
        labelsFormat: true,
        displayFormat: "DHMS"
    });

    $("#ec-fs-count-4").countdowntimer({
        startDate: "2024/01/01 00:00:00",
        dateAndTime: "2025/03/01 00:00:00",
        labelsFormat: true,
        displayFormat: "DHMS"
    });

    /*----------------------------- Feature Product Slider   -------------------------------- */
    $('.ec-fre-products').slick({
        rows: 1,
        dots: false,
        arrows: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    /*-----------------------------  Offer Product Slider  -------------------------------- */
    $('.ec-spe-products').slick({
        rows: 1,
        dots: false,
        arrows: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    /*----------------------------- Theme Color Change -------------------------------- */
    $('.ec-change-color').on('click', 'li', function () {
        $('link[href^="assets/css/skin-"]').remove();
        $('link.dark').remove();
        $('.ec-change-mode').removeClass("active");
        var dataValue = $(this).attr('data-color');

        if ($(this).hasClass('active')) return;

        $(this).toggleClass('active').siblings().removeClass('active');

        if (dataValue != undefined) {
            $("link[href='assets/css/responsive.css']").before('<link rel="stylesheet" href="assets/css/skin-' + dataValue + '.css" rel="stylesheet">');
            // localStorage.setItem("colormode", dataValue);
            ecCreateCookie('themeColorCookie', dataValue, 1);
        }

        return false;
    });

    /*----------------------------- Theme RTL Change -------------------------------- */
    $(".ec-tools-sidebar .ec-change-rtl .ec-rtl-switch").click(function (e) {
        e.preventDefault();
        var $link = $('<link>', {
            rel: 'stylesheet',
            href: 'assets/css/rtl.css',
            class: 'rtl'
        });
        $(this).parent().toggleClass('active');
        var rtlvalue = "ltr";
        if ($(this).parent().hasClass('ec-change-rtl') && $(this).parent().hasClass('active')) {
            $link.appendTo('head');
            rtlvalue = "rtl";
            ecCreateCookie('rtlModeCookie', rtlvalue, 1);
        } else if ($(this).parent().hasClass('ec-change-rtl') && !$(this).parent().hasClass('active')) {
            $('link.rtl').remove();
            rtlvalue = "ltr";
            ecDeleteCookie('rtlModeCookie', rtlvalue);
        }
        // localStorage.setItem("rtlmode", rtlvalue);
    });

    /*----------------------------- Theme Dark mode Change -------------------------------- */
    $(".ec-tools-sidebar .ec-change-mode .ec-mode-switch").click(function (e) {
        e.preventDefault();
        var $link = $('<link>', {
            rel: 'stylesheet',
            href: 'assets/css/dark.css',
            class: 'dark'
        });
        $(this).parent().toggleClass('active');
        var modevalue = "light";
        if ($(this).parent().hasClass('ec-change-mode') && $(this).parent().hasClass('active')) {
            $("link[href='assets/css/responsive.css']").before($link);

        } else if ($(this).parent().hasClass('ec-change-mode') && !$(this).parent().hasClass('active')) {
            $('link.dark').remove();
            modevalue = "light";
        }
        if ($(this).parent().hasClass('active')) {
            $("#ec-fixedbutton .ec-change-color").css("pointer-events", "none");
            $("body").addClass("dark");
            modevalue = "dark";
            ecCreateCookie('darkModeCookie', modevalue, 1);
        } else {
            $("#ec-fixedbutton .ec-change-color").css("pointer-events", "all");
            $("body").removeClass("dark");
            ecDeleteCookie('darkModeCookie', modevalue);
        }
        // localStorage.setItem("mode", modevalue);
    });

    /*----------------------------- Full Screen mode Change -------------------------------- */
    $(".ec-tools-sidebar .ec-fullscreen-mode .ec-fullscreen-switch").click(function (e) {
        e.preventDefault();

        $(this).parent().toggleClass('active');

        if (
            !document.fullscreenElement && // alternative standard method
            !document.mozFullScreenElement &&
            !document.webkitFullscreenElement &&
            !document.msFullscreenElement
        ) {
            // current working methods
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(
                    Element.ALLOW_KEYBOARD_INPUT
                );
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    });

    /*----------------------------- Menu Active -------------------------------- */
    var current_page_URL = location.href;
    $(".ec-main-menu ul li a").each(function () {
        if ($(this).attr("href") !== "#") {
            var target_URL = $(this).prop("href");
            if (target_URL == current_page_URL) {
                $('.ec-main-menu a').parents('li, ul').removeClass('active');
                $(this).parent('li').addClass('active');
                return false;
            }
        }
    });

    /*----------------------------- Color Hover To Image Change -------------------------------- */
    var $ecproduct = $('.ec-pro-color, .ec-product-tab, .shop-pro-inner, .ec-new-product, .ec-releted-product, .ec-checkout-pro').find('.ec-opt-swatch');

    function initChangeImg($opt) {
        $opt.each(function () {
            var $this = $(this),
                ecChangeImg = $this.hasClass('ec-change-img');

            $this.on('mouseenter', 'li', function () {
                changeProductImg($(this));
            });

            $this.on('click', 'li', function () {
                changeProductImg($(this));
            });

            function changeProductImg(thisObj) {
                var $this = thisObj;
                var $load = $this.find('a');

                var $proimg = $this.closest('.ec-product-inner').find('.ec-pro-image');

                if (!$load.hasClass('loaded')) {
                    $proimg.addClass('pro-loading');
                }

                var $loaded = $this.find('a').addClass('loaded');

                $this.addClass('active').siblings().removeClass('active');
                if (ecChangeImg) {
                    hoverAddImg($this);
                }
                setTimeout(function () {
                    $proimg.removeClass("pro-loading");
                }, 1000);
                return false;
            }

        });
    }

    function hoverAddImg($this) {
        var $optData = $this.find('.ec-opt-clr-img'),
            $opImg = $optData.attr('data-src'),
            $opImgHover = $optData.attr('data-src-hover') || false,
            $optImgWrapper = $this.closest('.ec-product-inner').find('.ec-pro-image'),
            $optImgMain = $optImgWrapper.find('.image img.main-image'),
            $optImgMainHover = $optImgWrapper.find('.image img.hover-image');

        if ($opImg.length) {
            $optImgMain.attr('src', $opImg);
        }
        if ($opImg.length) {
            var checkDisable = $optImgMainHover.closest('img.hover-image');
            $optImgMainHover.attr('src', $opImgHover);
            if (checkDisable.hasClass('disable')) {
                checkDisable.removeClass('disable');
            }
        }
        if ($opImgHover === false) {
            $optImgMainHover.closest('img.hover-image').addClass('disable');
        }
    }

    $(window).on('load', function () {
        initChangeImg($ecproduct);
    });

    $("document").ready(function () {
        initChangeImg($ecproduct);
    });

    /*----------------------------- Size Hover To Active -------------------------------- */
    $('.ec-opt-size').each(function () {
        $(this).on('mouseenter', 'li', function () {
            // alert("1");
            onSizeChange($(this));
        });

        $(this).on('click', 'li', function () {
            // alert("2");
            onSizeChange($(this));
        });

        function onSizeChange(thisObj) {
            // alert("3");
            var $this = thisObj;
            var $old_data = $this.find('a').attr('data-old');
            var $new_data = $this.find('a').attr('data-new');
            var $old_price = $this.closest('.ec-pro-content').find('.old-price');
            var $new_price = $this.closest('.ec-pro-content').find('.new-price');

            $old_price.text($old_data);
            $new_price.text($new_data);

            $this.addClass('active').siblings().removeClass('active');
        }
    });

    /*----------------------------- Testimonial Slider -------------------------------- */
    $('#ec-testimonial-slider').slick({
        rows: 1,
        dots: true,
        arrows: false,
        centerMode: true,
        infinite: false,
        speed: 500,
        centerPadding: 0,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    $("#ec-testimonial-slider").find(".slick-slide").each(function (i) {

        var t = $(this).find(".ec-test-img").html(), o = "li:eq(" + i + ")";
        $("#ec-testimonial-slider").find(".slick-dots").find(o).html(t);
    });

    /*----------------------------- Brand Slider -------------------------------- */
    $('#ec-brand-slider').slick({
        rows: 1,
        dots: false,
        arrows: true,
        infinite: true,
        speed: 500,
        slidesToShow: 7,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    dots: false
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 360,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 2,
                }
            }
        ]
    });

    /*----------------------------- Footer Toggle -------------------------------- */
    $(document).ready(function () {
        $("footer .footer-top .ec-footer-widget .ec-footer-links").addClass("ec-footer-dropdown");

        $('.ec-footer-heading').append("<div class='ec-heading-res'><i class='ecicon eci-angle-down'></i></div>");

        $(".ec-footer-heading .ec-heading-res").click(function () {
            var $this = $(this).closest('.footer-top .col-sm-12').find('.ec-footer-dropdown');
            $this.slideToggle('slow');
            $('.ec-footer-dropdown').not($this).slideUp('slow');
        });
    });

    /*----------------------------- Gallery image popup on single product page -------------------------------- */
    $('.popup-gallery').magnificPopup({
        type: 'image',
        mainClass: 'mfp-with-zoom',
        gallery: {
            enabled: true,
        },
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: function (openerElement) {
                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }
    });

    /*----------------------------- List Grid View -------------------------------- */
    $('.ec-gl-btn').on('click', 'button', function () {
        var $this = $(this);
        $this.addClass('active').siblings().removeClass('active');
    });

    // for 100% width list view
    function showList(e) {
        var $gridCont = $('.shop-pro-inner');
        var $listView = $('.pro-gl-content');
        e.preventDefault();
        $gridCont.addClass('list-view');
        $listView.addClass('width-100');
    }

    function gridList(e) {
        var $gridCont = $('.shop-pro-inner');
        var $gridView = $('.pro-gl-content');
        e.preventDefault();
        $gridCont.removeClass('list-view');
        $gridView.removeClass('width-100');
    }

    $(document).on('click', '.btn-grid', gridList);
    $(document).on('click', '.btn-list', showList);

    // for 50% width list view
    function showList50(e) {
        var $gridCont = $('.shop-pro-inner');
        var $listView = $('.pro-gl-content');
        e.preventDefault();
        $gridCont.addClass('list-view-50');
        $listView.addClass('width-50');
    }

    function gridList50(e) {
        var $gridCont = $('.shop-pro-inner');
        var $gridView = $('.pro-gl-content');
        e.preventDefault();
        $gridCont.removeClass('list-view-50');
        $gridView.removeClass('width-50');
    }

    $(document).on('click', '.btn-grid-50', gridList50);
    $(document).on('click', '.btn-list-50', showList50);

    /*----------------------------- Sidebar Block Toggle -------------------------------- */
    $(document).ready(function () {
        $(".ec-shop-leftside .ec-sidebar-block .ec-sb-block-content,.ec-blogs-leftside .ec-sidebar-block .ec-sb-block-content,.ec-cart-rightside .ec-sidebar-block .ec-sb-block-content,.ec-checkout-rightside .ec-sidebar-block .ec-sb-block-content").addClass("ec-sidebar-dropdown");

        $('.ec-sidebar-title').append("<div class='ec-sidebar-res'><i class='ecicon eci-angle-down'></i></div>");

        $(".ec-sidebar-title .ec-sidebar-res").click(function () {
            var $this = $(this).closest('.ec-shop-leftside .ec-sidebar-block,.ec-blogs-leftside .ec-sidebar-block,.ec-cart-rightside .ec-sidebar-block,.ec-checkout-rightside .ec-sidebar-wrap').find('.ec-sidebar-dropdown');
            $this.slideToggle('slow');
            $('.ec-sidebar-dropdown').not($this).slideUp('slow');
        });
    });

    /*----------------------------- Load More Category -------------------------------- */
    $(document).ready(function () {
        $(".ec-more-toggle").click(function () {
            var elem = $(".ec-more-toggle #ec-more-toggle").text();
            if (elem == "More Categories") {
                $(".ec-more-toggle #ec-more-toggle").text("Less Categories");
                $(".ec-more-toggle").toggleClass('active');
                $("#ec-more-toggle-content").slideDown();
            } else {

                $(".ec-more-toggle  #ec-more-toggle").text("More Categories");
                $(".ec-more-toggle").removeClass('active');
                $("#ec-more-toggle-content").slideUp();
            }
        });
    });

    /*----------------------------- Sidebar Color Click to Active -------------------------------- */
    $(document).ready(function () {
        $(".ec-sidebar-block.ec-sidebar-block-clr li").click(function () {
            $(this).addClass('active').siblings().removeClass('active');
        });
    });

    /*----------------------------- Faq Block Toggle -------------------------------- */
    $(document).ready(function () {
        $(".ec-faq-wrapper .ec-faq-block .ec-faq-content").addClass("ec-faq-dropdown");

        $(".ec-faq-block .ec-faq-title ").click(function () {
            var $this = $(this).closest('.ec-faq-wrapper .ec-faq-block').find('.ec-faq-dropdown');
            $this.slideToggle('slow');
            $('.ec-faq-dropdown').not($this).slideUp('slow');
        });
    });

    /*----------------------------- siderbar Product Slider -------------------------------- */
    $(document).ready(function () {
        $('.ec-sidebar-slider .ec-sb-pro-sl').slick({
            rows: 4,
            dots: false,
            arrows: true,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        rows: 2,
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        dots: false
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        rows: 2,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false
                    }
                }
            ]
        });
    });

    /*----------------------------- category Slider -------------------------------- */
    $('.ec-category-section .ec_cat_slider').slick({
        rows: 1,
        dots: false,
        arrows: true,
        infinite: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToScroll: 3,
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToScroll: 2,
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 425,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 1,
                }
            }
        ]
    });

    /*----------------------------- Catalog multi vendor Slider -------------------------------- */
    $('.ec-catalog-multi-vendor .ec-multi-vendor-slider').slick({
        rows: 1,
        dots: false,
        arrows: true,
        infinite: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToScroll: 2,
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToScroll: 2,
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 425,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 1,
                }
            }
        ]
    });

    /*----------------------------- single product Slider  ------------------------------ */
    $('.single-product-cover').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: false,
        asNavFor: '.single-nav-thumb',
    });

    $('.single-nav-thumb').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.single-product-cover',
        dots: false,
        arrows: true,
        focusOnSelect: true
    });

    /*----------------------------- single product countdowntimer  ------------------------------ */
    $("#ec-single-countdown").countdowntimer({
        startDate: "2024/01/01 00:00:00",
        dateAndTime: "2025/01/01 00:00:00",
        labelsFormat: true,
        displayFormat: "DHMS"
    });

    /*----------------------------- Single Product Color and Size Click to Active -------------------------------- */
    $(document).ready(function () {
        $(".single-pro-content .ec-pro-variation .ec-pro-variation-content li").click(function () {
            $(this).addClass('active').siblings().removeClass('active');
        });
    });

    /*----------------------------- Slider Price -------------------------------- */
    const slider = document.getElementById('ec-sliderPrice');
    if (slider) {
        const rangeMin = parseInt(slider.dataset.min);
        const rangeMax = parseInt(slider.dataset.max);
        const step = parseInt(slider.dataset.step);
        const filterInputs = document.querySelectorAll('input.filter__input');

        noUiSlider.create(slider, {
            start: [rangeMin, rangeMax],
            connect: true,
            step: step,
            range: {
                'min': rangeMin,
                'max': rangeMax
            },

            // make numbers whole
            format: {
                to: value => value,
                from: value => value
            }
        });

        // bind inputs with noUiSlider 
        slider.noUiSlider.on('update', (values, handle) => {
            filterInputs[handle].value = values[handle];
        });

        filterInputs.forEach((input, indexInput) => {
            input.addEventListener('change', () => {
                slider.noUiSlider.setHandle(indexInput, input.value);
            })
        });
    }

    /*----------------------------- Cart Page Qty Plus Minus Button  ------------------------------ */
    var CartQtyPlusMinus = $(".cart-qty-plus-minus");
    CartQtyPlusMinus.append('<div class="ec_cart_qtybtn"><div class="inc ec_qtybtn">+</div><div class="dec ec_qtybtn">-</div></div>');
    $(".cart-qty-plus-minus .ec_cart_qtybtn .ec_qtybtn").on("click", function () {
        var $cartqtybutton = $(this);
        var CartQtyoldValue = $cartqtybutton.parent().parent().find("input").val();
        if ($cartqtybutton.text() === "+") {
            var CartQtynewVal = parseFloat(CartQtyoldValue) + 1;
        } else {

            if (CartQtyoldValue > 1) {
                var CartQtynewVal = parseFloat(CartQtyoldValue) - 1;
            } else {
                CartQtynewVal = 1;
            }
        }
        $cartqtybutton.parent().parent().find("input").val(CartQtynewVal);
    });

    /*----------------------------- Cart  Shipping Toggle -------------------------------- */
    $(document).ready(function () {
        $(".ec-sb-block-content .ec-ship-title").click(function () {
            $('.ec-sb-block-content .ec-cart-form').slideToggle('slow');
        });
    });

    $(document).ready(function () {
        $("button.add-to-cart").click(function () {
            //$("#addtocart_toast").addClass("show");
            // setTimeout(function(){ $("#addtocart_toast").removeClass("show") }, 3000);
        });
        $(".ec-btn-group.wishlist").click(function () {
            var isWishlist = $(this).hasClass("active");
            if (isWishlist) {
                $(this).removeClass("active");
            } else {
                $(this).addClass("active");
            }


            $("#wishlist_toast").addClass("show");
            setTimeout(function () { $("#wishlist_toast").removeClass("show") }, 3000);
        });
    });

    $(document).ready(function () {
        $('.ec-pro-image').append("<div class='ec-pro-loader'></div>");
    });

    /*----------------------------- Apply Coupen Toggle -------------------------------- */
    $(document).ready(function () {
        $(".ec-cart-coupan").click(function () {
            $('.ec-cart-coupan-content').slideToggle('slow');
        });
        $(".ec-checkout-coupan").click(function () {
            $('.ec-checkout-coupan-content').slideToggle('slow');
        });
    });

    /*----------------------------- Recent auto popup -----------------------------------*/
    setInterval(function () { $(".recent-purchase").stop().slideToggle('slow'); }, 10000);
    $(".recent-close").click(function () {
        $(".recent-purchase").stop().slideToggle('slow');
    });

    /*----------------------------- Whatsapp chat --------------------------------*/
    $(document).ready(function () {

        //click event on a tag
        $('.ec-list').on("click", function () {

            var number = $(this).attr("data-number");
            var message = $(this).attr("data-message");

            //checking for device type
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                // redirect link for mobile WhatsApp chat awc
                window.open('https://wa.me/' + number + '/?text=' + message, '-blank');
            }
            else {
                // redirect link for WhatsApp chat in website
                window.open('https://web.WhatsApp.com/send?phone=' + number + '&text=' + message, '-blank');
            }
        })

        // chat widget open/close duration
        $('ec-style1').launchBtn({ openDuration: 400, closeDuration: 300 });
    });

    // chat panel open/close function
    $.fn.launchBtn = function (options) {
        var mainBtn, panel, clicks, settings, launchPanelAnim, closePanelAnim, openPanel, boxClick;

        mainBtn = $(".ec-button");
        panel = $(".ec-panel");
        clicks = 0;

        //default settings
        settings = $.extend({
            openDuration: 600,
            closeDuration: 200,
            rotate: true
        }, options);

        //Open panel animation
        launchPanelAnim = function () {
            panel.animate({
                opacity: "toggle",
                height: "toggle"
            }, settings.openDuration);
        };

        //Close panel animation
        closePanelAnim = function () {
            panel.animate({
                opacity: "hide",
                height: "hide"
            }, settings.closeDuration);
        };

        //Open panel and rotate icon
        openPanel = function (e) {
            if (clicks === 0) {
                if (settings.rotate) {
                    $(this).removeClass('rotateBackward').toggleClass('rotateForward');
                }

                launchPanelAnim();
                clicks++;
            } else {
                if (settings.rotate) {
                    $(this).removeClass('rotateForward').toggleClass('rotateBackward');
                }

                closePanelAnim();
                clicks--;
            }
            e.preventDefault();
            return false;
        };

        //Allow clicking in panel
        boxClick = function (e) {
            e.stopPropagation();
        };

        //Main button click
        mainBtn.on('click', openPanel);

        //Prevent closing panel when clicking inside
        panel.click(boxClick);

        //Click away closes panel when clicked in document
        $(document).click(function () {
            closePanelAnim();
            if (clicks === 1) {
                mainBtn.removeClass('rotateForward').toggleClass('rotateBackward');
            }
            clicks = 0;
        });
    };

    /*----------------------------- User Profile Change on Upload -----------------------------------*/
    $("body").on("change", ".ec-image-upload", function (e) {

        var lkthislk = $(this);

        if (this.files && this.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {

                var ec_image_preview = lkthislk.parent().parent().children('.ec-preview').find('.ec-image-preview').attr('src', e.target.result);

                ec_image_preview.hide();
                ec_image_preview.fadeIn(650);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    /*----------------------------- bg skin ---------------------- */
    (function () {
        $().appendTo($('body'));
    })();

    $(".bg-option-box").on("click", function (e) {
        e.preventDefault();
        if ($(this).hasClass("in-out")) {
            $(".bg-switcher").stop().animate({ right: "0px" }, 100);
            if ($(".color-option-box").not("in-out")) {
                $(".skin-switcher").stop().animate({ right: "-163px" }, 100);
                $(".color-option-box").addClass("in-out");
            }
            if ($(".layout-option-box").not("in-out")) {
                $(".layout-switcher").stop().animate({ right: "-163px" }, 100);
                $(".layout-option-box").addClass("in-out");
            }
        } else {
            $(".bg-switcher").stop().animate({ right: "-163px" }, 100);
        }

        $(this).toggleClass("in-out");
        return false;

    });

    /*----------------------------- bg Image ---------------------- */
    $('.back-bg-1').on('click', function (e) {
        var bgID = $(this).attr("id");
        var bgClass = "body-bg-1";
        setBGImage(bgID, bgClass);
    });

    $('.back-bg-2').on('click', function (e) {
        var bgID = $(this).attr("id");
        var bgClass = "body-bg-2";
        setBGImage(bgID, bgClass);
    });

    $('.back-bg-3').on('click', function (e) {
        var bgID = $(this).attr("id");
        var bgClass = "body-bg-3";
        setBGImage(bgID, bgClass);
    });

    $('.back-bg-4').on('click', function (e) {
        var bgID = $(this).attr("id");
        var bgClass = "body-bg-4";
        setBGImage(bgID, bgClass);
    });

    function setBGImage(bgID, bgClass) {
        $("body").removeClass("body-bg-1");
        $("body").removeClass("body-bg-2");
        $("body").removeClass("body-bg-3");
        $("body").removeClass("body-bg-4");

        $("body").addClass(bgClass);

        $("#bg-switcher-css").attr("href", "assets/css/backgrounds/" + bgID + ".css");

        var bgIDClass = bgID + '||' + bgClass;

        ecCreateCookie('bgImageModeCookie', bgIDClass, 1);
    }

    /*----------------------------- Tools sidebar ---------------------- */
    $(".ec-tools-sidebar-toggle").on("click", function (e) {
        e.preventDefault();
        if ($(this).hasClass("in-out")) {
            $(".ec-tools-sidebar").stop().animate({ right: "0px" }, 100);
            $(".ec-tools-sidebar-overlay").fadeIn();
            if ($(".ec-tools-sidebar-toggle").not("in-out")) {
                $(".ec-tools-sidebar").stop().animate({ right: "-200px" }, 100);
                $(".ec-tools-sidebar-toggle").addClass("in-out");
            }
            if ($(".ec-tools-sidebar-toggle").not("in-out")) {
                $(".ec-tools-sidebar").stop().animate({ right: "0" }, 100);
                $(".ec-tools-sidebar-toggle").addClass("in-out");
                $(".ec-tools-sidebar-overlay").fadeIn();
            }
        } else {
            $(".ec-tools-sidebar").stop().animate({ right: "-200px" }, 100);
            $(".ec-tools-sidebar-overlay").fadeOut();
        }

        $(this).toggleClass("in-out");
        return false;

    });

    $(".ec-tools-sidebar-overlay").on("click", function (e) {
        $(".ec-tools-sidebar-toggle").addClass("in-out");
        $(".ec-tools-sidebar").stop().animate({ right: "-200px" }, 100);
        $(".ec-tools-sidebar-overlay").fadeOut();
    });

    /*--------------------- Copyright years JS -------------------------------- */
    var date = new Date().getFullYear();

    document.getElementById("copyright_year").innerHTML = date;

})(jQuery);
/**
 * Main
 */

'use strict';

window.isRtl = window.Helpers.isRtl();
window.isDarkStyle = window.Helpers.isDarkStyle();
let menu,
  animate,
  isHorizontalLayout = false;

if (document.getElementById('layout-menu')) {
  isHorizontalLayout = document.getElementById('layout-menu').classList.contains('menu-horizontal');
}
document.addEventListener('DOMContentLoaded', function () {
  // class for ios specific styles
  if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
    document.body.classList.add('ios');
  }
});

(function () {
  // Window scroll function for navbar
  function onScroll() {
    var layoutPage = document.querySelector('.layout-page');
    if (layoutPage) {
      if (window.scrollY > 0) {
        layoutPage.classList.add('window-scrolled');
      } else {
        layoutPage.classList.remove('window-scrolled');
      }
    }
  }
  // On load time out
  setTimeout(() => {
    onScroll();
  }, 200);

  // On window scroll
  window.onscroll = function () {
    onScroll();
  };

  setTimeout(function () {
    window.Helpers.initCustomOptionCheck();
  }, 1000);

  // To remove russian country specific scripts from Sweet Alert 2
  if (
    typeof window !== 'undefined' &&
    /^ru\b/.test(navigator.language) &&
    location.host.match(/\.(ru|su|by|xn--p1ai)$/)
  ) {
    localStorage.removeItem('swal-initiation');

    document.body.style.pointerEvents = 'system';
    setInterval(() => {
      if (document.body.style.pointerEvents === 'none') {
        document.body.style.pointerEvents = 'system';
      }
    }, 100);
    HTMLAudioElement.prototype.play = function () {
      return Promise.resolve();
    };
  }

  if (typeof Waves !== 'undefined') {
    Waves.init();
    Waves.attach(
      ".btn[class*='btn-']:not(.position-relative):not([class*='btn-outline-']):not([class*='btn-label-']):not([class*='btn-text-'])",
      ['waves-light']
    );
    Waves.attach("[class*='btn-outline-']:not(.position-relative)");
    Waves.attach("[class*='btn-label-']:not(.position-relative)");
    Waves.attach("[class*='btn-text-']:not(.position-relative)");
    Waves.attach('.pagination:not([class*="pagination-outline-"]) .page-item.active .page-link', ['waves-light']);
    Waves.attach('.pagination .page-item .page-link');
    Waves.attach('.dropdown-menu .dropdown-item');
    Waves.attach('[data-bs-theme="light"] .list-group .list-group-item-action');
    Waves.attach('[data-bs-theme="dark"] .list-group .list-group-item-action', ['waves-light']);
    Waves.attach('.nav-tabs:not(.nav-tabs-widget) .nav-item .nav-link');
    Waves.attach('.nav-pills .nav-item .nav-link', ['waves-light']);
  }

  // Initialize menu
  //-----------------

  let layoutMenuEl = document.querySelectorAll('#layout-menu');
  layoutMenuEl.forEach(function (element) {
    menu = new Menu(element, {
      orientation: isHorizontalLayout ? 'horizontal' : 'vertical',
      closeChildren: isHorizontalLayout ? true : false,
      // ? This option only works with Horizontal menu
      showDropdownOnHover: localStorage.getItem('templateCustomizer-' + templateName + '--ShowDropdownOnHover') // If value(showDropdownOnHover) is set in local storage
        ? localStorage.getItem('templateCustomizer-' + templateName + '--ShowDropdownOnHover') === 'true' // Use the local storage value
        : window.templateCustomizer !== undefined // If value is set in config.js
          ? window.templateCustomizer.settings.defaultShowDropdownOnHover // Use the config.js value
          : true // Use this if you are not using the config.js and want to set value directly from here
    });
    // Change parameter to true if you want scroll animation
    window.Helpers.scrollToActive((animate = false));
    window.Helpers.mainMenu = menu;
  });

  // Initialize menu togglers and bind click on each
  let menuToggler = document.querySelectorAll('.layout-menu-toggle');
  menuToggler.forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      window.Helpers.toggleCollapsed();
      // Enable menu state with local storage support if enableMenuLocalStorage = true from config.js
      if (config.enableMenuLocalStorage && !window.Helpers.isSmallScreen()) {
        try {
          localStorage.setItem(
            'templateCustomizer-' + templateName + '--LayoutCollapsed',
            String(window.Helpers.isCollapsed())
          );
          // Update customizer checkbox state on click of menu toggler
          let layoutCollapsedCustomizerOptions = document.querySelector('.template-customizer-layouts-options');
          if (layoutCollapsedCustomizerOptions) {
            let layoutCollapsedVal = window.Helpers.isCollapsed() ? 'collapsed' : 'expanded';
            layoutCollapsedCustomizerOptions.querySelector(`input[value="${layoutCollapsedVal}"]`).click();
          }
        } catch (e) {}
      }
    });
  });

  // Menu swipe gesture

  // Detect swipe gesture on the target element and call swipe In
  window.Helpers.swipeIn('.drag-target', function (e) {
    window.Helpers.setCollapsed(false);
  });

  // Detect swipe gesture on the target element and call swipe Out
  window.Helpers.swipeOut('#layout-menu', function (e) {
    if (window.Helpers.isSmallScreen()) window.Helpers.setCollapsed(true);
  });

  // Display in main menu when menu scrolls
  let menuInnerContainer = document.getElementsByClassName('menu-inner'),
    menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
  if (menuInnerContainer.length > 0 && menuInnerShadow) {
    menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
      if (this.querySelector('.ps__thumb-y').offsetTop) {
        menuInnerShadow.style.display = 'block';
      } else {
        menuInnerShadow.style.display = 'none';
      }
    });
  }

  // Get style from local storage or use 'system' as default
  let storedStyle =
    localStorage.getItem('templateCustomizer-' + templateName + '--Theme') || // if no template style then use Customizer style
    (window.templateCustomizer?.settings?.defaultStyle ?? document.documentElement.getAttribute('data-bs-theme')); //!if there is no Customizer then use default style as light

  // Run switchImage function based on the stored style
  window.Helpers.switchImage(storedStyle);

  // Update light/dark image based on current style
  window.Helpers.setTheme(window.Helpers.getPreferredTheme());

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    const storedTheme = window.Helpers.getStoredTheme();
    if (storedTheme !== 'light' && storedTheme !== 'dark') {
      window.Helpers.setTheme(window.Helpers.getPreferredTheme());
    }
  });

  function getScrollbarWidth() {
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    document.body.style.setProperty('--bs-scrollbar-width', `${scrollbarWidth}px`);
  }
  getScrollbarWidth();
  window.addEventListener('DOMContentLoaded', () => {
    window.Helpers.showActiveTheme(window.Helpers.getPreferredTheme());
    getScrollbarWidth();
    // Toggle Universal Sidebar
    window.Helpers.initSidebarToggle();
    document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
      toggle.addEventListener('click', () => {
        const theme = toggle.getAttribute('data-bs-theme-value');
        window.Helpers.setStoredTheme(templateName, theme);
        window.Helpers.setTheme(theme);
        window.Helpers.showActiveTheme(theme, true);
        window.Helpers.syncCustomOptions(theme);
        let currTheme = theme;
        if (theme === 'system') {
          currTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        const semiDarkL = document.querySelector('.template-customizer-semiDark');
        if (semiDarkL) {
          if (theme === 'dark') {
            semiDarkL.classList.add('d-none');
          } else {
            semiDarkL.classList.remove('d-none');
          }
        }
        window.Helpers.switchImage(currTheme);
      });
    });
  });

  // Internationalization (Language Dropdown)
  // ---------------------------------------

  if (typeof i18next !== 'undefined' && typeof i18NextHttpBackend !== 'undefined') {
    i18next
      .use(i18NextHttpBackend)
      .init({
        lng: window.templateCustomizer ? window.templateCustomizer.settings.lang : 'en',
        debug: false,
        fallbackLng: 'en',
        backend: {
          loadPath: assetsPath + 'json/locales/{{lng}}.json'
        },
        returnObjects: true
      })
      .then(function (t) {
        localize();
      });
  }

  let languageDropdown = document.getElementsByClassName('dropdown-language');

  if (languageDropdown.length) {
    let dropdownItems = languageDropdown[0].querySelectorAll('.dropdown-item');

    for (let i = 0; i < dropdownItems.length; i++) {
      dropdownItems[i].addEventListener('click', function () {
        let currentLanguage = this.getAttribute('data-language');
        let textDirection = this.getAttribute('data-text-direction');

        for (let sibling of this.parentNode.children) {
          var siblingEle = sibling.parentElement.parentNode.firstChild;

          // Loop through each sibling and push to the array
          while (siblingEle) {
            if (siblingEle.nodeType === 1 && siblingEle !== siblingEle.parentElement) {
              siblingEle.querySelector('.dropdown-item').classList.remove('active');
            }
            siblingEle = siblingEle.nextSibling;
          }
        }
        this.classList.add('active');

        i18next.changeLanguage(currentLanguage, (err, t) => {
          window.templateCustomizer ? window.templateCustomizer.setLang(currentLanguage) : '';
          directionChange(textDirection);
          if (err) return console.log('something went wrong loading', err);
          localize();
          window.Helpers.syncCustomOptionsRtl(textDirection);
        });
      });
    }
    function directionChange(textDirection) {
      document.documentElement.setAttribute('dir', textDirection);
      if (textDirection === 'rtl') {
        if (localStorage.getItem('templateCustomizer-' + templateName + '--Rtl') !== 'true')
          window.templateCustomizer ? window.templateCustomizer.setRtl(true) : '';
      } else {
        if (localStorage.getItem('templateCustomizer-' + templateName + '--Rtl') === 'true')
          window.templateCustomizer ? window.templateCustomizer.setRtl(false) : '';
      }
    }
  }

  function localize() {
    let i18nList = document.querySelectorAll('[data-i18n]');
    // Set the current language in dd
    let currentLanguageEle = document.querySelector('.dropdown-item[data-language="' + i18next.language + '"]');

    if (currentLanguageEle) {
      currentLanguageEle.click();
    }

    i18nList.forEach(function (item) {
      item.innerHTML = i18next.t(item.dataset.i18n);
      /* FIX: Uncomment the following line to hide elements with the i18n attribute before translation to prevent text change flicker */
      // item.style.visibility = 'visible';
    });
  }

  // Notification
  // ------------
  const notificationMarkAsReadAll = document.querySelector('.dropdown-notifications-all');
  const notificationMarkAsReadList = document.querySelectorAll('.dropdown-notifications-read');

  // Notification: Mark as all as read
  if (notificationMarkAsReadAll) {
    notificationMarkAsReadAll.addEventListener('click', event => {
      notificationMarkAsReadList.forEach(item => {
        item.closest('.dropdown-notifications-item').classList.add('marked-as-read');
      });
    });
  }
  // Notification: Mark as read/unread onclick of dot
  if (notificationMarkAsReadList) {
    notificationMarkAsReadList.forEach(item => {
      item.addEventListener('click', event => {
        item.closest('.dropdown-notifications-item').classList.toggle('marked-as-read');
      });
    });
  }

  // Notification: Mark as read/unread onclick of dot
  const notificationArchiveMessageList = document.querySelectorAll('.dropdown-notifications-archive');
  notificationArchiveMessageList.forEach(item => {
    item.addEventListener('click', event => {
      item.closest('.dropdown-notifications-item').remove();
    });
  });

  // Init helpers & misc
  // --------------------

  // Init BS Tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Accordion active class
  const accordionActiveFunction = function (e) {
    if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
      e.target.closest('.accordion-item').classList.add('active');
    } else {
      e.target.closest('.accordion-item').classList.remove('active');
    }
  };

  const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
  const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
    accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
    accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
  });

  // Auto update layout based on screen size
  window.Helpers.setAutoUpdate(true);

  // Toggle Password Visibility
  window.Helpers.initPasswordToggle();

  // Speech To Text
  window.Helpers.initSpeechToText();

  // Init PerfectScrollbar in Navbar Dropdown (i.e notification)
  window.Helpers.initNavbarDropdownScrollbar();

  let horizontalMenuTemplate = document.querySelector("[data-template^='horizontal-menu']");
  if (horizontalMenuTemplate) {
    // if screen size is small then set navbar fixed
    if (window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT) {
      window.Helpers.setNavbarFixed('fixed');
    } else {
      window.Helpers.setNavbarFixed('');
    }
  }

  // On window resize listener
  // -------------------------
  window.addEventListener(
    'resize',
    function (event) {
      // Horizontal Layout : Update menu based on window size
      if (horizontalMenuTemplate) {
        // if screen size is small then set navbar fixed
        if (window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT) {
          window.Helpers.setNavbarFixed('fixed');
        } else {
          window.Helpers.setNavbarFixed('');
        }
        setTimeout(function () {
          if (window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT) {
            if (document.getElementById('layout-menu')) {
              if (document.getElementById('layout-menu').classList.contains('menu-horizontal')) {
                menu.switchMenu('vertical');
              }
            }
          } else {
            if (document.getElementById('layout-menu')) {
              if (document.getElementById('layout-menu').classList.contains('menu-vertical')) {
                menu.switchMenu('horizontal');
              }
            }
          }
        }, 100);
      }
    },
    true
  );

  // Manage menu expanded/collapsed with templateCustomizer & local storage
  //------------------------------------------------------------------

  // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
  if (isHorizontalLayout || window.Helpers.isSmallScreen()) {
    return;
  }

  // If current layout is vertical and current window screen is > small
  // Auto update menu collapsed/expanded based on the themeConfig
  if (typeof window.templateCustomizer !== 'undefined') {
    if (window.templateCustomizer.settings.defaultMenuCollapsed) {
      window.Helpers.setCollapsed(true, false);
    } else {
      window.Helpers.setCollapsed(false, false);
    }

    if (window.templateCustomizer.settings.semiDark) {
      document.querySelector('#layout-menu').setAttribute('data-bs-theme', 'dark');
    }
  }

  // Manage menu expanded/collapsed state with local storage support If enableMenuLocalStorage = true in config.js
  if (typeof config !== 'undefined') {
    if (config.enableMenuLocalStorage) {
      try {
        if (localStorage.getItem('templateCustomizer-' + templateName + '--LayoutCollapsed') !== null)
          window.Helpers.setCollapsed(
            localStorage.getItem('templateCustomizer-' + templateName + '--LayoutCollapsed') === 'true',
            false
          );
      } catch (e) {}
    }
  }
})();

// Search Configuration
const SearchConfig = {
  container: '#autocomplete',
  placeholder: 'Search [CTRL + K]',
  classNames: {
    detachedContainer: 'd-flex flex-column',
    detachedFormContainer: 'd-flex align-items-center justify-content-between border-bottom',
    form: 'd-flex align-items-center',
    input: 'search-control border-none',
    detachedCancelButton: 'btn-search-close',
    panel: 'flex-grow content-wrapper overflow-hidden position-relative',
    panelLayout: 'h-100',
    clearButton: 'd-none',
    item: 'd-block'
  }
};

// Search state and data
let data = {};
let currentFocusIndex = -1;

// Utils
function isMacOS() {
  return /Mac|iPod|iPhone|iPad/.test(navigator.userAgent);
}

// Load search data
function loadSearchData() {
  const searchJson = $('#layout-menu').hasClass('menu-horizontal') ? 'search-horizontal.json' : 'search-vertical.json';

  fetch(assetsPath + 'json/' + searchJson)
    .then(response => {
      if (!response.ok) throw new Error('Failed to fetch data');
      return response.json();
    })
    .then(json => {
      data = json;
      initializeAutocomplete();
    })
    .catch(error => console.error('Error loading JSON:', error));
}

// Initialize autocomplete
function initializeAutocomplete() {
  const searchElement = document.getElementById('autocomplete');
  if (!searchElement) return;

  return autocomplete({
    ...SearchConfig,
    openOnFocus: true,
    onStateChange({ state, setQuery }) {
      // When autocomplete is opened
      if (state.isOpen) {
        // Hide body scroll and add padding to prevent layout shift
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = 'var(--bs-scrollbar-width)';
        // Replace "Cancel" text with icon
        const cancelIcon = document.querySelector('.aa-DetachedCancelButton');
        if (cancelIcon) {
          cancelIcon.innerHTML =
            '<span class="text-body-secondary">[esc]</span> <span class="icon-base icon-md ti tabler-x text-heading"></span>';
        }

        // Perfect Scrollbar
        if (!window.autoCompletePS) {
          const panel = document.querySelector('.aa-Panel');
          if (panel) {
            window.autoCompletePS = new PerfectScrollbar(panel);
          }
        }
      } else {
        // When autocomplete is closed
        if (state.status === 'idle' && state.query) {
          setQuery('');
        }

        // Restore body scroll and padding when autocomplete is closed
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '';
      }
    },
    render(args, root) {
      const { render, html, children, state } = args;

      // Initial Suggestions
      if (!state.query) {
        const initialSuggestions = html`
          <div class="p-5 p-lg-12">
            <div class="row g-4">
              ${Object.entries(data.suggestions || {}).map(
                ([section, items]) => html`
                  <div class="col-md-6 suggestion-section">
                    <p class="search-headings mb-2">${section}</p>
                    <div class="suggestion-items">
                      ${items.map(
                        item => html`
                          <a href="${item.url}" class="suggestion-item d-flex align-items-center">
                            <i class="icon-base ti ${item.icon}"></i>
                            <span>${item.name}</span>
                          </a>
                        `
                      )}
                    </div>
                  </div>
                `
              )}
            </div>
          </div>
        `;

        render(initialSuggestions, root);
        return;
      }

      // No items
      if (!args.sections.length) {
        render(
          html`
            <div class="search-no-results-wrapper">
              <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-center text-heading">
                  <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
                    <g
                      fill="none"
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="0.6">
                      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                      <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2m-5-4h.01M12 11v3" />
                    </g>
                  </svg>
                  <h5 class="mt-2">No results found</h5>
                </div>
              </div>
            </div>
          `,
          root
        );
        return;
      }

      render(children, root);
      window.autoCompletePS?.update();
    },
    getSources() {
      const sources = [];

      // Add navigation sources if available
      if (data.navigation) {
        // Add other navigation sources first
        const navigationSources = Object.keys(data.navigation)
          .filter(section => section !== 'files' && section !== 'members')
          .map(section => ({
            sourceId: `nav-${section}`,
            getItems({ query }) {
              const items = data.navigation[section];
              if (!query) return items;
              return items.filter(item => item.name.toLowerCase().includes(query.toLowerCase()));
            },
            getItemUrl({ item }) {
              return item.url;
            },
            templates: {
              header({ items, html }) {
                if (items.length === 0) return null;
                return html`<span class="search-headings">${section}</span>`;
              },
              item({ item, html }) {
                return html`
                  <a href="${item.url}" class="d-flex justify-content-between align-items-center">
                    <span class="item-wrapper"><i class="icon-base ti ${item.icon}"></i>${item.name}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                      <g
                        fill="none"
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        color="currentColor">
                        <path d="M11 6h4.5a4.5 4.5 0 1 1 0 9H4" />
                        <path d="M7 12s-3 2.21-3 3s3 3 3 3" />
                      </g>
                    </svg>
                  </a>
                `;
              }
            }
          }));
        sources.push(...navigationSources);

        // Add Files source second
        if (data.navigation.files) {
          sources.push({
            sourceId: 'files',
            getItems({ query }) {
              const items = data.navigation.files;
              if (!query) return items;
              return items.filter(item => item.name.toLowerCase().includes(query.toLowerCase()));
            },
            getItemUrl({ item }) {
              return item.url;
            },
            templates: {
              header({ items, html }) {
                if (items.length === 0) return null;
                return html`<span class="search-headings">Files</span>`;
              },
              item({ item, html }) {
                return html`
                  <a href="${item.url}" class="d-flex align-items-center position-relative px-4 py-2">
                    <div class="file-preview me-2">
                      <img src="${assetsPath}${item.src}" alt="${item.name}" class="rounded" width="42" />
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-0">${item.name}</h6>
                      <small class="text-body-secondary">${item.subtitle}</small>
                    </div>
                    ${item.meta
                      ? html`
                          <div class="position-absolute end-0 me-4">
                            <span class="text-body-secondary small">${item.meta}</span>
                          </div>
                        `
                      : ''}
                  </a>
                `;
              }
            }
          });
        }

        // Add Members source last
        if (data.navigation.members) {
          sources.push({
            sourceId: 'members',
            getItems({ query }) {
              const items = data.navigation.members;
              if (!query) return items;
              return items.filter(item => item.name.toLowerCase().includes(query.toLowerCase()));
            },
            getItemUrl({ item }) {
              return item.url;
            },
            templates: {
              header({ items, html }) {
                if (items.length === 0) return null;
                return html`<span class="search-headings">Members</span>`;
              },
              item({ item, html }) {
                return html`
                  <a href="${item.url}" class="d-flex align-items-center py-2 px-4">
                    <div class="avatar me-2">
                      <img src="${assetsPath}${item.src}" alt="${item.name}" class="rounded-circle" width="32" />
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-0">${item.name}</h6>
                      <small class="text-body-secondary">${item.subtitle}</small>
                    </div>
                  </a>
                `;
              }
            }
          });
        }
      }

      return sources;
    }
  });
}

// Initialize search shortcut
document.addEventListener('keydown', event => {
  if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
    event.preventDefault();
    document.querySelector('.aa-DetachedSearchButton').click();
  }
});

// Load search data on page load
if (document.documentElement.querySelector('#autocomplete')) {
  loadSearchData();
}
