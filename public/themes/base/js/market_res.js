

let dropDownContainer = document.querySelector(".full--nav");
let dropDownTrigger = document.querySelector(".header__resp_menu-button-container");
let newEl = dropDownContainer.cloneNode(true);
dropDownTrigger.appendChild(newEl);




let navLinks = document.querySelectorAll(".market__nav-link");
let navImgs = document.querySelectorAll(".market__nav-link img")

for (let i = 0; i < navImgs.length; i++) {
    navLinks[i].addEventListener("mouseover", function () {
        navImgs[i].src = "/images/nav__forward--gold.svg";
    })
    navLinks[i].addEventListener("mouseout", function () {
        navImgs[i].src = "/images/nav__forward.svg";
    })
}


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
let closeButton = document.querySelector(".close_button");

closeButton.addEventListener("click", function () {
    toggleModal();
})

//add click event on all button
for (let i = 0; i < quickViewButton.length; i++) {
    quickViewButton[i].addEventListener("click", function (e) {
        toggleModal();
        console.log("event triggered")
    })
}

function windowOnClick(event) {
    if (event.target === modal) {
        toggleModal();
    }
}


window.addEventListener("click", windowOnClick);


// increase quantity code

let increaseButton = document.querySelector(".modal_quantity__increase");
let reduceButton = document.querySelector(".modal_quantity__reduce");
let inputValue = document.querySelector(".modal_quantity__input");
let max = 20;
let min = 1;

increaseButton.addEventListener("click", function () {
    increaseInput();
});

reduceButton.addEventListener("click", function () {
    reduceInput();
});


function increaseInput() {
    if (inputValue.value < max) {
        inputValue.value++;
    } else {
        return;
    }
}

function reduceInput() {
    if (inputValue.value > min) {
        inputValue.value--;
    } else {
        return;
    }
}


let thumbnailContainers = document.querySelectorAll(".modal_product__thumbnail");

let largeImage = document.querySelector(".modal_body__second_column img")

let src = document.querySelectorAll(".modal_product__thumbnail img").src



// loop thru all thumbnails

for (let i = 0; i < thumbnailContainers.length; i++) {

    thumbnailContainers[i].addEventListener("click", function () {
        let newSrc = thumbnailContainers[i].childNodes[1].src
        largeImage.src = newSrc;

    });


}

let menuIconMega = $(".header__resp_menu-btn");
let menuModalMega = $(".nav_menu__resp-mega-menu");

menuIconMega.click(function () {
    menuModalMega.animate({ left: "0%" });
    document.body.style.height = "100vh";
    if (window.screen.width <= 1100) {

        document.body.style.overflow = "hidden";
    }
});

$(window).click(function (e) {
    if (e.target.className === "nav_menu__resp-mega-menu") {
        //remv the modal
        menuModalMega.animate({ left: "-100%" });
        // height of the document
        document.body.style.height = "auto";
        //make the document scrollable again
        document.body.style.overflow = "auto";
        // document.querySelector(".menu_icon").style.visibility = "visible";
    }
});


$(".close_mega_menu").click(function () {
    menuModalMega.animate({ left: "-100%" });
    document.body.style.height = "auto";
    document.body.style.overflow = "auto";
    // document.querySelector(".menu_icon").style.visibility = "visible";

});


const megaMenuLink = document.querySelectorAll(".resp-menu__mega-link-title-header");

const megaMenuDropDown = document.querySelectorAll(".resp-menu__mega-wrapper");

const megaMenuArrow = document.querySelectorAll(".header__mega_menu-arrow");


for (let x = 0; x < megaMenuLink.length; x++) {
    megaMenuLink[x].addEventListener("click", function () {
        for (let z = 0; z < megaMenuLink.length; z++) {
            if (z !== x) {
                // megaMenuArrow[z].classList
                megaMenuDropDown[z].classList.remove("resp-menu__mega-wrapper--view");
                megaMenuArrow[z].classList.remove("header__mega_menu-arrow--rotate");
            }
        }
        megaMenuDropDown[x].classList.toggle("resp-menu__mega-wrapper--view");
        megaMenuArrow[x].classList.toggle("header__mega_menu-arrow--rotate");
    }
    );
}


$(document).ready(function () {
    let owl = $(".owl-one");
    owl.owlCarousel({
        items: 1,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            500: {
                items: 1
            },

            760: {
                items: 2
            },
            980: {
                items: 3,
            },
            1280: {
                items: 4
            }
        }
    });

    $('.js__prev--one').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.js__next--one').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});

$(document).ready(function () {
    let owl = $(".owl-two");
    owl.owlCarousel({
        items: 1,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            500: {
                items: 1
            },
            760: {
                items: 2
            },
            980: {
                items: 3,
            },
            1280: {
                items: 4
            }
        }
    });

    $('.js__next--two').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.js__prev--two').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});

$(document).ready(function () {
    let owl = $(".owl-three");
    owl.owlCarousel({
        items: 1,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            500: {
                items: 1
            },
            760: {
                items: 2
            },
            1110: {
                items: 1
            },
            1280: {
                items: 1
            },
            1281: {
                items: 2
            }
        }
    });

    $('.js__next--three').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.js__prev--three').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});


$(document).ready(function () {
    let owl = $(".owl-four");
    owl.owlCarousel({
        items: 1,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            500: {
                items: 1
            },

            760: {
                items: 2
            },
            980: {
                items: 3,
            },
            1280: {
                items: 4
            }
        }
    });

    $('.js__next--four').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.js__prev--four').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});

$(document).ready(function () {
    let owl = $(".owl-four--two");
    owl.owlCarousel({
        items: 1,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            500: {
                items: 1
            },

            760: {
                items: 2
            },
            980: {
                items: 3,
            },
            1280: {
                items: 4
            }
        }
    });

    $('.js__next--six').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.js__prev--six').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});


$(document).ready(function () {
    let owl = $(".owl-five");
    owl.owlCarousel({
        items: 1,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            500: {
                items: 1
            },
            760: {
                items: 2
            },
            1110: {
                items: 1
            },
            1280: {
                items: 1
            },
            1281: {
                items: 2
            }
        }
    });

    $('.js__next--seven').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.js__prev--seven').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});


$(document).ready(function () {
    let owl = $(".owl-six");
    owl.owlCarousel({
        items: 1,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            500: {
                items: 1
            },
            760: {
                items: 2
            },
            1110: {
                items: 1
            },
            1280: {
                items: 1
            },
            1281: {
                items: 2
            }
        }
    });

    $('.js__next--eight').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.js__prev--eight').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});



$(document).ready(function () {
    let owl = $(".owl-seven");
    owl.owlCarousel({
        items: 1,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            500: {
                items: 1
            },
            760: {
                items: 2
            },
            1110: {
                items: 1
            },
            1280: {
                items: 1
            },
            1281: {
                items: 2
            }
        }
    });

    $('.js__next--nine').click(function () {
        owl.trigger('next.owl.carousel');
    })


    $('.js__prev--nine').click(function () {
        owl.trigger('prev.owl.carousel');
    })
});




var x, i, j, selElmnt, a, b, c;
/* Look for any elements with the class "custom-select": */
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    /* For each element, create a new DIV that will act as the selected item: */
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /* For each element, create a new DIV that will contain the option list: */
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    for (j = 1; j < selElmnt.length; j++) {
        /* For each option in the original select element,
        create a new DIV that will act as an option item: */
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function (e) {
            /* When an item is clicked, update the original select box,
            and the selected item: */
            var y, i, k, s, h;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            h = this.parentNode.previousSibling;
            for (i = 0; i < s.length; i++) {
                if (s.options[i].innerHTML == this.innerHTML) {
                    s.selectedIndex = i;
                    h.innerHTML = this.innerHTML;
                    y = this.parentNode.getElementsByClassName("same-as-selected");
                    for (k = 0; k < y.length; k++) {
                        y[k].removeAttribute("class");
                    }
                    this.setAttribute("class", "same-as-selected");
                    break;
                }
            }
            h.click();
        });
        b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function (e) {
        /* When the select box is clicked, close any other select boxes,
        and open/close the current select box: */
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
    });
}

function closeAllSelect(elmnt) {
    /* A function that will close all select boxes in the document,
    except the current select box: */
    var x, y, i, arrNo = [];
    x = document.getElementsByClassName("select-items");
    y = document.getElementsByClassName("select-selected");
    for (i = 0; i < y.length; i++) {
        if (elmnt == y[i]) {
            arrNo.push(i)
        } else {
            y[i].classList.remove("select-arrow-active");
        }
    }
    for (i = 0; i < x.length; i++) {
        if (arrNo.indexOf(i)) {
            x[i].classList.add("select-hide");
        }
    }
}

/* If the user clicks anywhere outside the select box,
then close all select boxes: */
document.addEventListener("click", closeAllSelect);



let hoverLink = document.querySelectorAll(".header__full-nav-link");

for (let c = 0; c < hoverLink.length; c++) {
    hoverLink[c].addEventListener("click", function (e) {
        e.preventDefault();
    })
}
