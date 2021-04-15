
let menuIcon = $(".header__menu_icon");
let menuModal = $(".nav_menu__resp");

menuIcon.click(function () {

    menuModal.animate({ left: "0%" });
    if (screen.availWidth < 1100) {
        document.body.style.height = "100vh";
        document.body.style.overflow = "hidden";
        // document.querySelector(".menu_icon").style.visibility = "hidden";
    }
});

$(window).click(function (e) {
    if (e.target.className === "nav_menu__resp" || e.target.className === "nav_menu__resp-mega-menu") {
        //remv the modal
        menuModal.animate({ left: "-100%" });
        // height of the document
        document.body.style.height = "auto";
        //make the document scrollable again
        document.body.style.overflow = "auto";
        // document.querySelector(".menu_icon").style.visibility = "visible";
    }
});


$(".menu__close").click(function () {
    menuModal.animate({ left: "-100%" });
    document.body.style.height = "auto";
    document.body.style.overflow = "auto";
    // document.querySelector(".menu_icon").style.visibility = "visible";

});


function colorRed() {

    for (let i = 0; i < document.querySelectorAll(".resp_menu__link--active:link path").length; i++) {
        document.querySelectorAll(".resp_menu__link--active:link path")[i].setAttribute("fill", "#dcbd56");
        if (document.querySelectorAll(".resp_menu__link--active:link polygon").length > 0) {
            document.querySelectorAll(".resp_menu__link--active:link polygon")[i].setAttribute("fill", "#dcbd56");
        }
        if (document.querySelectorAll(".resp_menu__link--active:link rect").length > i) {
            document.querySelectorAll(".resp_menu__link--active:link rect")[i].setAttribute("fill", "#dcbd56");
        }
    }

}

colorRed();