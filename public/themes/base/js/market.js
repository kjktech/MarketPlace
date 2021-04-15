
let slideOuterContainer = $(".market__product-wrapper");
let slideInnerContainer = $(".market_product_slider_wrapper");

let sliderz = $(".product_c_f_R");
if (sliderz.length > 4) {
    let widthOfInnerContainer = sliderz.length * 25;
    let finalWidth = widthOfInnerContainer + "%";
    slideInnerContainer.css("width", finalWidth);
} else {
    slideInnerContainer.css("width", "100%");
    slideInnerContainer.css('left', '0%');
    $(".product_c_f_R:first-child").css("visibility", "visible");
}


function nextSlider() {
    slideInnerContainer.animate({ left: '-50%' }, 400, function () {
        slideInnerContainer.css('left', '-25%');
        $('.product_c_f_R').last().after($('.product_c_f_R').first());
    });
}

function previousSlider() {
    slideInnerContainer.animate({ left: '0%' }, 400, function () {
        slideInnerContainer.css('left', '-25%');
        $('.product_c_f_R').first().before($('.product_c_f_R').last());
    });
}

// setInterval(nextSlider, 8000);
document.querySelector(".slider__next").addEventListener("click", function () {
    if (sliderz.length > 4) {
        nextSlider();
    } else {
        return;
    }
});

document.querySelector(".slider__prev").addEventListener("click", function () {
    if (sliderz.length > 4) {
        nextSlider();
    } else {
        return;
    }
});

//second slider
let slideOuterContainerTwo = $(".market__product-wrapper--two");
let slideInnerContainerTwo = $(".market_product_slider_wrapper--two");

let sliderzTwo = $(".product__crf");

if (sliderzTwo.length > 4) {
    let widthOfInnerContainerTwo = sliderzTwo.length * 25;
    let finalWidthTwo = widthOfInnerContainerTwo + "%";
    slideInnerContainerTwo.css("width", finalWidthTwo);
} else {
    slideInnerContainerTwo.css("width", "100%");
    slideInnerContainerTwo.css('left', '0%');
    $(".product__crf:first-child").css("visibility", "visible");
}


function nextSliderTwo() {
    slideInnerContainerTwo.animate({ left: '-50%' }, 400, function () {
        slideInnerContainerTwo.css('left', '-25%');
        $('.product__crf').last().after($('.product__crf').first());
    });
}

function previousSliderTwo() {
    slideInnerContainerTwo.animate({ left: '0%' }, 400, function () {
        slideInnerContainerTwo.css('left', '-25%');
        $('.product__crf').first().before($('.product__crf').last());
    });
}

// setInterval(nextSlider, 8000);
document.querySelector(".slider__next--two").addEventListener("click", function () {
    if (sliderzTwo.length > 4) {
        nextSliderTwo();
    } else {
        return;
    }
});

document.querySelector(".slider__prev--two").addEventListener("click", function () {
    if (sliderzTwo.length > 4) {
        previousSliderTwo();
    } else {
        return;
    }
});


//third slider
// let thirdSliderOuterContainer = $(".")
let thirdSliderInnerContainer = $(".col_wrapper_inner_container");

let sliderzThree = $(".col_wrapper_inner");

if (sliderzThree.length > 2) {
    let widthOfInnerContainerThree = sliderzThree.length * 50;
    let finalWidthThree = widthOfInnerContainerThree + "%";
    thirdSliderInnerContainer.css("width", finalWidthThree);
} else {
    thirdSliderInnerContainer.css("width", "100%");
    thirdSliderInnerContainer.css('left', '0%');
    $(".col_wrapper_inner:first-child").css("visibility", "visible");
}



function nextSliderThree() {
    thirdSliderInnerContainer.animate({ left: '-100%' }, 400, function () {
        thirdSliderInnerContainer.css('left', '-50%');
        $('.col_wrapper_inner').last().after($('.col_wrapper_inner').first());
    });
}

function previousSliderThree() {
    thirdSliderInnerContainer.animate({ left: '0%' }, 400, function () {
        thirdSliderInnerContainer.css('left', '-50%');
        $('.col_wrapper_inner').first().before($('.col_wrapper_inner').last());
    });
}

document.querySelector(".slider__next_two").addEventListener("click", function () {
    if (sliderzThree.length > 2) {
        nextSliderThree();
    } else {
        return;
    }
});

document.querySelector(".slider__prev_two").addEventListener("click", function () {
    if (sliderzThree.length > 2) {
        previousSliderThree();
    } else {
        return;
    }
});


let fourthSliderInnerContainer = $(".col_wrapper_inner_container--two");

let sliderzFour = $(".col_wrapper_inner--two");

if (sliderzFour.length > 2) {
    let widthOfInnerContainerFour = sliderzFour.length * 50;
    let finalWidthFour = widthOfInnerContainerFour + "%";
    fourthSliderInnerContainer.css("width", finalWidthFour);
} else {
    fourthSliderInnerContainer.css("width", "100%");
    fourthSliderInnerContainer.css('left', '0%');
    $(".col_wrapper_inner--two:first-child").css("visibility", "visible");
}



function nextSliderFour() {
    fourthSliderInnerContainer.animate({ left: '-100%' }, 400, function () {
        fourthSliderInnerContainer.css('left', '-50%');
        $('.col_wrapper_inner--two').last().after($('.col_wrapper_inner--two').first());
    });

}

function previousSliderFour() {
    fourthSliderInnerContainer.animate({ left: '0%' }, 400, function () {
        fourthSliderInnerContainer.css('left', '-50%');
        $('.col_wrapper_inner--two').first().before($('.col_wrapper_inner--two').last());
    });
}

document.querySelector(".slider__next_three").addEventListener("click", function () {

    if (sliderzFour.length > 2) {
        nextSliderFour();
    } else {
        return;
    }

});

document.querySelector(".slider__prev_three").addEventListener("click", function () {
    if (sliderzFive.length > 2) {
        previousSliderFour();
    }
});



let navLinks = document.querySelectorAll(".market__nav-link");
let navImgs = document.querySelectorAll(".market__nav-link img")

for (let i = 0; i < navImgs.length; i++) {
    navLinks[i].addEventListener("mouseover", function () {
        navImgs[i].src = "./themes/base/images/nav__forward--gold.svg";
    })
    navLinks[i].addEventListener("mouseout", function () {
        navImgs[i].src = "./themes/base/images/nav__forward.svg";
    })
}


let fifthSliderInnerContainer = $(".col_wrapper_inner_container--three");

let sliderzFive = $(".col_wrapper_inner--three");

if (sliderzFive.length > 2) {
    let widthOfInnerContainerFive = sliderzFive.length * 50;
    let finalWidthFive = widthOfInnerContainerFive + "%";
    fifthSliderInnerContainer.css("width", finalWidthFive);
} else {
    fifthSliderInnerContainer.css("width", "100%");
    fifthSliderInnerContainer.css('left', '0%');
    $(".col_wrapper_inner_container--three:first-child").css("visibility", "visible");
}



function nextSliderFive() {
    fifthSliderInnerContainer.animate({ left: '-100%' }, 400, function () {
        fifthSliderInnerContainer.css('left', '-50%');
        $('.col_wrapper_inner--three').last().after($('.col_wrapper_inner--three').first());
    });
}

function previousSliderFive() {
    fifthSliderInnerContainer.animate({ left: '0%' }, 400, function () {
        fifthSliderInnerContainer.css('left', '-50%');
        $('.col_wrapper_inner--fifth').first().before($('.col_wrapper_inner--fifth').last());
    });
}

document.querySelector(".slider__next_four").addEventListener("click", function () {
    if (sliderzFive.length > 2) {
        nextSliderFive();
    } else {
        return;
    }
});

document.querySelector(".slider__prev_four").addEventListener("click", function () {
    if (sliderzFive.length > 2) {
        previousSliderFive();
    } else {
        return;
    }
});



// $(".first-row-rateBox").rate({
//     type: 0,
//     length: 5,
//     // value: 4,
//     half: true,
//     decimal: true,
//     readonly: true,
//     text: false,
//     textList: ['Bad', 'Poor', 'Medium', 'Good', 'Perfect'],
//     theme: '#E6B712',
//     size: '10px',
//     gutter: '2px',
//     selectClass: 'fxss_rate_select',
//     incompleteClass: '',
//     customClass: 'first-star-rating'
// });

$(".first-row-rateBox").starRating({
    totalStars: 5,
    readOnly: true,
    emptyColor: 'lightgray',
    hoverColor: 'lightgray',
    activeColor: '#E6B712',
    starShape: 'rounded',
    starSize: 15,
    initialRating: 4,
    strokeWidth: 0,
    useGradient: false,
    callback: function (currentRating, $el) {
        alert('rated ' + currentRating);
        console.log('DOM element ', $el);
    }
});

$(".modal_body_second-row-rateBox").starRating({
    totalStars: 5,
    readOnly: true,
    emptyColor: 'lightgray',
    hoverColor: 'lightgray',
    activeColor: '#E6B712',
    starShape: 'rounded',
    starSize: 20,
    initialRating: 4.8,
    strokeWidth: 0,
    useGradient: false,
    callback: function (currentRating, $el) {
        alert('rated ' + currentRating);
        console.log('DOM element ', $el);
    }
});

//store btn in a variable
let quickViewButton = document.querySelectorAll(".quick-view");

//add click event on all button
for (let i = 0; i < quickViewButton.length; i++) {
    quickViewButton[i].addEventListener("click", function (e) {
        toggleModal();
        console.log("event triggered")
    })
}

function windowOnClick(event) {
    if (event.target === modal_container) {
        toggleModal();
    }
}


window.addEventListener("click", windowOnClick);
