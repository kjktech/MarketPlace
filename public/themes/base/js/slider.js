 $(function () {


//     function slide() {
//         //grap slider wrapper

//         var inWrap = $('.slides');

//         //animate to the left inner wrapper
//         inWrap.animate({ left: '0%' }, 3000, function () {

//             inWrap.css('left', '-100%');

//             $('.slide').first().before($('.slide').last());

//         });

//     }

//     setInterval(slide, 10000);



//     function secondSlide() {

//         if ($(window).width() < 940) {
//             let secSlide = $('.featured-images');

//             secSlide.animate({ left: '-100%' }, 3000, function () {

//                 secSlide.css('left', '-50%');

//                 $('.featured-images__card').last().after($('.featured-images__card').first());

//             });
//         }
//         else {
//             let secSlide = $('.featured-images');

//             secSlide.animate({ left: '-68%' }, 3000, function () {

//                 secSlide.css('left', '-34%');

//                 $('.featured-images__card').last().after($('.featured-images__card').first());

//             });
//         }


//     }

//     setInterval(secondSlide, 8000);

//     function thirdSlider() {
//         let thirdSlide = $('.card-wrapper__outer');

//         thirdSlide.animate({ left: '-68%' }, 3000, function () {

//             thirdSlide.css('left', '-34%');

//             $('.service__card').last().after($('.service__card').first());

//         });
//     }

//     setInterval(thirdSlider, 9000);


//     function fourthSlide() {

//         let fourthSlide = $('.testimonials');

//         fourthSlide.animate({ left: '20%' }, 3000, function () {

//             fourthSlide.css('left', '0%');

//             $('.paper').first().before($('.paper').last());

//         });
//     }

//     setInterval(fourthSlide, 8000);


    let slideContainer = $(".slider__container");
    // let prev = document.querySelector(".hero__slider-nav");
    let next = document.querySelector(".hero__slider-nav");

    $('.hero__slider-nav--left').on('click', movePrevious);
    $('.hero__slider-nav').on('click', moveNext);

    function movePrevious() {
        console.log('clicked previous')
        slideContainer.animate({ left: '0%' }, 500, function () {
            slideContainer.css('left', '-100%');
            $('.slide--market').first().before($('.slide--market').last());
            $('.legend').last().before($('.legend').first());
        });
    }

    function moveNext() {
        console.log('clicked next')
        slideContainer.animate({ left: '-200%' }, 500, function () {
            slideContainer.css('left', '-100%');
            $('.slide--market').last().before($('.slide--market').first());
            $('.legend').first().before($('.legend').last());
        });
    }

    $(".pause").on('click', pause);
    $(".play").on('click', play);

    function pause(){
        
        let pause = document.querySelector("img.pause");
        pause.classList.add("hide");
        let play = document.querySelector("img.play");
        play.classList.remove("hide");
        intervalSetter('pause');

    }

    function play(){
        let pause = document.querySelector('img.pause');
        pause.classList.remove("hide");
        let play = document.querySelector("img.play");
        play.classList.add("hide");
        moveNext();
        intervalSetter();
    }
    let autoMove;
    function intervalSetter (value){

        if(!value){
            autoMove = setInterval(moveNext, 11000);
        } else{
            clearInterval(autoMove);
        }
    }

    intervalSetter();


});

