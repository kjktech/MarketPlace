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




// function nextSlider() {
//     slideInnerContainer.animate({ left: '-50%' }, 400, function () {
//         slideInnerContainer.css('left', '-25%');
//         $('.people__content-box').last().after($('.people__content-box').first());

//     });
// }

// setInterval(nextSlider, 8000);


// function previousSlider() {
//     slideInnerContainer.animate({ left: '0%' }, 400, function () {
//         slideInnerContainer.css('left', '-25%');
//         $('.people__content-box').first().before($('.people__content-box').last());
//     });
// }



// document.querySelector(".previous").addEventListener("click", function(){
//     previousSlider();
// });

// document.querySelector(".next").addEventListener("click", function(){
//     nextSlider();
// });


$(".hero--btn").click(function () {
    $("html, body").animate({ scrollTop: $(".about__outer-wrapper").offset().top - 100 }, 1000)
});

let lowTexts = document.querySelectorAll(".about__lower-txt-content");
let seeMore = document.querySelector(".see__more");
let seeLess = document.querySelector(".see__less");
let imgBtn = document.querySelector(".btn__img-section")

window.addEventListener("resize", function () {
    reduceTxt();
    reduceBtn();
})


function reduceTxt() {
    if (screen.availWidth <= 1280) {
        lowTexts[1].classList.add("hide__text");
        seeMore.classList.remove("hide__span");
        seeLess.classList.remove("hide__span");
    } else if (screen.availWidth > 1280) {
        lowTexts[1].classList.remove("hide__text");
        seeMore.classList.add("hide__span");
        seeLess.classList.add("hide__span");
    }
}


function reduceBtn() {
    if (screen.availWidth <= 500) {
        imgBtn.textContent = "APPLY NOW";
    } else if (screen.availWidth > 500) {
        imgBtn.textContent = "Interested in being a part of our journey? APPLY NOW"
    }
}


seeMore.addEventListener("click", function () {
    lowTexts[1].classList.remove("hide__text");
    seeMore.classList.toggle("hide__span");
});

seeLess.addEventListener("click", function () {
    lowTexts[1].classList.add("hide__text");
    seeMore.classList.toggle("hide__span");
})




reduceTxt();
reduceBtn();



$(document).ready(function () {
    let owl = $(".owl-one");
    owl.owlCarousel({
        items: 4,
        responsive: {
            0: {
                items: 1
            },
            540: {
                items: 1
            },
            620: {
                items: 2,
            },
            960: {
                items: 3,
            },
            1200: {
                items: 4
            }


        },
        autoplay: true,
        loop: true,
        dots: true,
        dotsEach: true,
        autoplayTimeout: 8000
    });

    $('.next').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.previous').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});


let menuIconMega = $(".header__menu_icon");
let menuModalMega = $(".nav_menu__resp");

menuIconMega.click(function () {
    menuModalMega.animate({ left: "0%" });
    document.body.style.height = "100vh";
    document.body.style.overflow = "hidden";
    // document.querySelector(".menu_icon").style.visibility = "hidden";
});

$(window).click(function (e) {
    if (e.target.className === "nav_menu__resp") {
        //remv the modal
        menuModalMega.animate({ left: "-100%" });
        // height of the document
        document.body.style.height = "auto";
        //make the document scrollable again
        document.body.style.overflow = "auto";
        // document.querySelector(".menu_icon").style.visibility = "visible";
    }
});


$(".menu__close").click(function () {
    menuModalMega.animate({ left: "-100%" });
    document.body.style.height = "auto";
    document.body.style.overflow = "auto";
    // document.querySelector(".menu_icon").style.visibility = "visible";

});
