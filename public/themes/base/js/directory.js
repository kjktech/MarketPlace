
var x, i, j, selElmnt, a, b, c;
/*look for any elements with the class "header__form-select":*/
x = document.getElementsByClassName("header__form-select");
console.log(x);
for (i = 0; i < x.length; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    /*for each element, create a new DIV that will act as the selected item:*/
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /*for each element, create a new DIV that will contain the option list:*/
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    for (j = 1; j < selElmnt.length; j++) {
        /*for each option in the original select element,
        create a new DIV that will act as an option item:*/
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function (e) {
            /*when an item is clicked, update the original select box,
            and the selected item:*/
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
        /*when the select box is clicked, close any other select boxes,
        and open/close the current select box:*/
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
    /*a function that will close all select boxes in the document,
    except the current select box:*/
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

let select = document.querySelectorAll(".select-selected");
select[0].classList.add('header__form-input--left');
select[1].classList.add('header__form-input--middle');
select[2].classList.add('header__form-input--right');
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);


let container = document.querySelector(".info-cards__container");

let cardsPerPage = 9;

let card = `
    <div class="info-card__container">

                <div class="card">
                    <div class="info-card__upper-section">
                        <img class="info-card__logo" width="60px" height="60px" src="resources/img/directory/comp-icon.svg">
                        <p class="info-card__title">Rufus Thomas And Company Limited</p>
                        <p class="info-card__content">
                            <span><img class="info-card__icon" src="resources/img/directory/star.svg">4/5 rating</span>
                            <span><img class="info-card__icon info-card__icon--left-space" src="resources/img/directory/like.svg">1
                                review</span>
                            <span><img class="info-card__icon info-card__icon--left-space" src="resources/img/directory/hired.svg">hired
                                1 time</span>
                        </p>
                        <p class="info-card__content">
                            <span><img class="info-card__icon" src="resources/img/directory/verified.svg">Verified info</span>
                            <span><img class="info-card__icon info-card__icon--left-space" src="resources/img/directory/checked.svg">Phone
                                number</span>
                        </p>
                        <p class="info-card__content">
                            <span><img class="info-card__icon" src="resources/img/directory/checked.svg">Email Address</span>
                            <span><img class="info-card__icon info-card__icon--left-space" src="resources/img/directory/checked.svg">Bank
                                Details</span>
                        </p>
                    </div>

                    <div class="info-card__lower-section">
                        <div class="info-card__lower-container">
                            <div class="info-card__phone">
                                <img class="info-card__icon info-card__icon--align" src="resources/img/directory/phone.svg">
                                <span>09099717070</span>
                            </div>
                            <a href="">
                                <img src="resources/img/directory/more.svg">
                            </a>

                        </div>

                    </div>
                </div>
            </div>
    `;

for (let i = 0; i < 9; i++) {
    //container.innerHTML += card;
}


$(function () {
    //caches a jQuery object containing the header element
    var header = $(".header__top-container");
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            header.addClass('header__top-container--shadow');
        } else {
            header.removeClass('header__top-container--shadow');
        }
    });
});
