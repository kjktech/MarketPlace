function secondSlide() {
    let secondSlide = $('.gallery-img');

    secondSlide.animate({ left: '0%' }, 2000, function () {

        secondSlide.css('left', '-54%');

        $('.gallery__img').first().before($('.gallery__img').last());
        $('.gallery-label').last().after($('.gallery-label').first());
    });
}




    function thirdSlider() {
        let thirdSlide = $('.gallery-img');

        thirdSlide.animate({ left: '-108%' }, 2000, function () {

            thirdSlide.css('left', '-54%');

            $('.gallery__img').last().after($('.gallery__img ').first());
            $('.gallery-label').first().before($('.gallery-label').last());
        });
    }

    setInterval(thirdSlider, 9000);

    

        next = document.querySelector(".next");
        previous = document.querySelector(".previous");

        previous.addEventListener("click", function(){
            console.log("it")
            secondSlide();
        });
        next.addEventListener("click", function(){

            thirdSlider();

        });
