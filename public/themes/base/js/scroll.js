
$(".scroll_to_top").css("display", "none");


$(document).scroll(function () {

    $(".scroll_to_top").css("display", "none");

    $(window).scroll(function () {

        var scroll = $(window).scrollTop();

        if (scroll >= 410) {
            ;
            $(".scroll_to_top").css("display", "flex");
        }



    });



    // console.log('d');
});
$(".scroll_to_top").click(function (e) {
    $("html, body").animate({ scrollTop: "0px" });
    $(".scroll_to_top").css("display", "none");
});