var CONTACT_US_URL = 'https://saukya.hsenidmobile.com/wellness/admin/v1/contact/save';
// var CONTACT_US_URL = 'https://wellness.hsenidmobile.com/wellness/admin/v1/contact/save';
//var CONTACT_US_URL = 'http://localhost:10060/wellness/admin/v1/contact/save';

$(document).ready(function () {
    $('#form-success-message-message').addClass('row hidden');
    $('#form-error-message').addClass('row hidden');

    // Tooltip
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    // Scroll to product overview
    $(".about-link").click(function () {
        $('html, body').animate({
            scrollTop: $(".about-area").offset().top
        }, 500);
    });

    $(".product-platforms-link").click(function () {
        $('html, body').animate({
            scrollTop: $(".platforms-area").offset().top
        }, 500);
    });

    $(".product-features-link").click(function () {
        $('html, body').animate({
            scrollTop: $(".features-area").offset().top
        }, 500);
    });

    $(".contact-area-link").click(function () {
        $('html, body').animate({
            scrollTop: $(".page-content__sign-up-area").offset().top
        }, 500);
    });

    // Top Sign Up button click
    $(".home-main-content__sign-btn").click(function () {
        $('html, body').animate({
            scrollTop: $(".page-content__sign-up-area").offset().top
        }, 500);
    });

    //Back to Top
    if ($('#back-to-top').length) {
        var scrollTrigger = 100, // px
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('#back-to-top').addClass('show');
                } else {
                    $('#back-to-top').removeClass('show');
                }
            };
        backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });
        $('#back-to-top').on('click', function (e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }

// Header background color change when scroll
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 350) {
            $(".navbar-home").addClass("color-fill");
        } else {
            $(".navbar-home").removeClass("color-fill");
        }
    });

    $("#contact-us-submit-btn").click(function () {
        $('#form-fill-details-message').addClass('hidden')
        var name = $('#contact-us-name').val();
        var email = $('#contact-us-email').val();
        var phoneNumber = $('#contact-us-phone-number').val();
        var message = $('#contact-us-message').val();

        if (name !== "" && email !== "" && phoneNumber !== "") {
            var request = {
                "name": name,
                "phoneNo": phoneNumber,
                "email": email,
                "message": message
            };
            $.ajax({
                type: "POST",
                contentType: 'application/json',
                url: CONTACT_US_URL,
                data: JSON.stringify(request),
                success: function () {
                    $('#form-success-message').removeClass('hidden');
                },
                error: function () {
                    $('#form-error-message').removeClass('hidden');
                }
            });
        }else {
            $('#form-fill-details-message').removeClass('hidden')
        }
    });

});
