let filterBtn = $(".filter_button--two");
let filterModal = $(".nav_menu__resp-filter-menu");


filterBtn.click(function () {
    filterModal.animate({
        left: "0%"
    }

    );
    document.body.style.height = "100vh";
    document.body.style.overflow = "hidden";
}

);

$(window).click(function (e) {
    if (e.target.className === "nav_menu__resp-filter-menu") {

        //remv the modal
        filterModal.animate({
            left: "-100%"
        }

        );
        // height of the document
        document.body.style.height = "auto";
        //make the document scrollable again
        document.body.style.overflow = "auto";
    }
}

);


$(".menu__close").click(function () {
    filterModal.animate({
        left: "-100%"
    }

    );
    document.body.style.height = "auto";
    document.body.style.overflow = "auto";
    // document.querySelector(".menu_icon").style.visibility = "visible";

}

);


let viewFilterItemTrigger = document.getElementsByClassName("resp_menu-filter-item-title");
let filterItem = document.getElementsByClassName("resp_menu-filter-item");
let filterArrowImage = document.getElementsByClassName("filter__down-arrow");

for (let i = 0; i < filterItem.length; i++) {
    viewFilterItemTrigger[i].addEventListener("click", function () {
        for (let z = 0;
            z < filterItem.length;

            z++) {
            if (z !== i) {
                filterItem[z].classList.remove("resp_menu-filter-item--open");
                filterArrowImage[z].classList.remove("filter__down-arrow--rotate");
            }
        }

        filterItem[i].classList.toggle("resp_menu-filter-item--open");
        filterArrowImage[i].classList.toggle("filter__down-arrow--rotate");
    }

    );

};