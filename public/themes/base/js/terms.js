let drop = document.querySelectorAll(".drop-down__container");
let items = document.querySelectorAll(".terms__dropdown");
let image = document.querySelectorAll(".handle")

console.log(items);

for (let i = 0; i < drop.length; i++) {
    drop[i].addEventListener("click", function (e) {
        if (e.target.classList.contains("dropdown__title") || e.target.classList.contains("handle")) {
            items[i].classList.toggle("terms__dropdown--hide")
            image[i].classList.toggle("handle--open")
        }
    });
}


//scroll code jquery

// Select all links with hashes
$('a[href*="#"]')
    // Remove links that don't actually link to anything
    .not('[href="#"]')
    .not('[href="#0"]')
    .click(function (event) {
        // On-page links
        if (
            location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
            &&
            location.hostname == this.hostname
        ) {
            // Figure out element to scroll to
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            // Does a scroll target exist?
            if (target.length) {
                // Only prevent default if animation is actually gonna happen
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 94
                }, 1000, function () {
                    // Callback after animation
                    // Must change focus!
                    var $target = $(target);
                    // $target.focus();
                    if ($target.is(":focus")) { // Checking if the target was focused
                        return false;
                    } else {
                        $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                        // $target.focus(); // Set focus again
                    };
                });
            }
        }
    });


let linkBox = document.querySelector(".hero__list-box");
let links = document.querySelectorAll(".hero__list a")


for (let x = 0; x < links.length; x++) {
    links[x].addEventListener("click", function (e) {
        items[x].classList.add("terms__dropdown--hide")
        image[x].classList.add("handle--open")
    })
}


$(function () {
    //caches a jQuery object containing the header element
    var header = $(".header__top-container");
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 210) {
            header.addClass('header__top-container--shadow');
            let img = document.querySelector(".header__top-container img");
            img.src = "./themes/base/images/logo-white.png";
            var selection = document.querySelector('.greeting') !== null;
            if(selection){
              document.querySelector(".greeting").classList.add("greeting--bg");
              document.querySelector(".user__name").classList.add("user__name--bg");
            }
            let linksHeader = document.querySelectorAll("a.header__nav-link");
            for (let i = 0; i < linksHeader.length; i++) {
                linksHeader[i].classList.add("header__nav-link--bg");
            }
        } else {
            header.removeClass('header__top-container--shadow');
            let img = document.querySelector(".header__top-container img");
            img.src = "./themes/base/images/market-logo.svg";
            var selection = document.querySelector('.greeting') !== null;
            if(selection){
              document.querySelector(".greeting").classList.remove("greeting--bg");
              document.querySelector(".user__name").classList.remove("user__name--bg");
            }
            let linksHeader = document.querySelectorAll("a.header__nav-link");
            for (let i = 0; i < linksHeader.length; i++) {
                linksHeader[i].classList.remove("header__nav-link--bg");
            }
        }
    });
});





$(".terms__outer-wrapper").waypoint(function (direction) {
    if (direction == "down") {
        $(".header__top-container").addClass("header__top-container--fixed");
    } else {
        $(".header__top-container").removeClass("header__top-container--fixed");
    }
}, {
        offset: "60px;"
    })
