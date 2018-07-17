$(document).ready(function () {
    $(document).scroll(function(){
        if($(document).scrollTop() > $('header').height ()){
            $('.menu').eq(0).addClass('fixed');
        }
        else{
            $('.menu').eq(0).removeClass('fixed');
        }

        if ($(document).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    $('#menuShow').click(function(){
        if($('#menuShow').is(':visible')){
            $('#menu').css('display','block');
        }
        else{
            $('#menu').css('display','none');
        }
    });

    $('#showMobileCategories').click(function(){
        $('#mobileCategories').slideToggle(500);
    });
});