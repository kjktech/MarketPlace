$(function () {
    //caches a jQuery object containing the header element
    var header = $(".header__top-container");
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 210) {
            header.addClass('header__top-container--shadow');
            let img = document.querySelector(".header__top-container img");
            img.src = "./themes/base/images/logo-white.png";
            var selector = document.querySelector(".greeting") !== null;
            if(selector){
              document.querySelector(".greeting").classList.add("greeting--bg");
              document.querySelector(".user__name").classList.add("user__name--bg");
            }
            let linksHeader = document.querySelectorAll("a.header__nav-link");
            for(let i = 0; i < linksHeader.length; i++){
                linksHeader[i].classList.add("header__nav-link--bg");
            }
        } else {
            header.removeClass('header__top-container--shadow');
            let img = document.querySelector(".header__top-container img");
            img.src = "./themes/base/images/market-logo.svg";
            var selector = document.querySelector(".greeting") !== null;
            if(selector){
              document.querySelector(".greeting").classList.remove("greeting--bg");
              document.querySelector(".user__name").classList.remove("user__name--bg");
            }
            let linksHeader = document.querySelectorAll("a.header__nav-link");
            for(let i = 0; i < linksHeader.length; i++){
                linksHeader[i].classList.remove("header__nav-link--bg");
            }
        }
    });
});



let slideOuterContainer = $(".people__content-box-outer");
let slideInnerContainer = $(".people__inner-container");

let slides = $(".people__content-box");

let widthOfInnerContainer = slides.length * 25;

let finalWidth = widthOfInnerContainer + "%";

slideInnerContainer.css("width", finalWidth);




function nextSlider() {
    slideInnerContainer.animate({ left: '-50%' }, 400, function () {
        slideInnerContainer.css('left', '-25%');
        $('.people__content-box').last().after($('.people__content-box').first());

    });
}

setInterval(nextSlider, 8000);


function previousSlider() {
    slideInnerContainer.animate({ left: '0%' }, 400, function () {
        slideInnerContainer.css('left', '-25%');
        $('.people__content-box').first().before($('.people__content-box').last());
    });
}

document.querySelector(".previous").addEventListener("click", function(){
    previousSlider();
});

document.querySelector(".next").addEventListener("click", function(){
    nextSlider();
});


$(".hero--btn").click(function(){
    $("html, body").animate({scrollTop: $(".about__outer-wrapper").offset().top-100}, 1000)
});
