/*
let sliderInnerWrapper = $(".gallery-img_inner_wrapper");
let next = document.querySelector(".next");
let previous = document.querySelector(".previous");
let slider = $(".gallery-img-inner");

console.log(slider.length);

if (slider.length  > 2) {
    let widthOfInnerContainer = slider.length * 50;
    let finalWidth = widthOfInnerContainer + "%";
    sliderInnerWrapper.css("width", finalWidth);
} else {
    sliderInnerWrapper.css("width", "100%");
    sliderInnerWrapper.css('left', '0%');
}


function nextSlider() {
    sliderInnerWrapper.animate({ left: '-100%' }, 400, function () {
        sliderInnerWrapper.css('left', '-50%');
        $('.gallery-img-inner').last().after($('.gallery-img-inner').first());
        $(".gallery-label").first().before($('.gallery-label').last());
    });
}

function previousSlider() {
    sliderInnerWrapper.animate({ left: '0%' }, 400, function () {
        sliderInnerWrapper.css('left', '-50%');
        $('.gallery-img-inner').first().before($('.gallery-img-inner').last());
    });
}


next.addEventListener("click", function () {
    if (slider.length > 2) {
        nextSlider();
    } else {
        return;
    }
});

previous.addEventListener("click", function () {
    if (slider.length > 2) {
        previousSlider();
    } else {
        return;
    }
});

setInterval(nextSlider, 9000);
*/

$(document).ready(function () {
    let owl = $(".owltwo");
    owl.owlCarousel({
        items: 2,
        responsive: {
            0: {
                items: 1
            },
            1180: {
                items: 2,
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
