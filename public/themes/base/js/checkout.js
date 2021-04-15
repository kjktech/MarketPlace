var x, i, j, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
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
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);




//modal address input

// console.log(address.innerHTML);


let triggerBtn = document.querySelector(".js_modal_handle");
//   //modal close button functionality
var closeButton = document.querySelector(".close_button");

triggerBtn.addEventListener("click", function (event) {

    toggleModal();
});

//   //events to close button and window area
closeButton.addEventListener("click", function (e) {
    toggleModal();
});



let secondProceedBtn = document.querySelector(".chkout-btn--modal");
let pickupRadioBtnFirst = document.querySelector(".js_handle_first_radio");
let pickupRadioBtnSec = document.querySelector(".js_handle_sec_radio");
let proceedBtn = document.querySelector(".js_handle_sec_proceed");
let placeInfo = document.querySelector(".pickup-info");
let confirmButton = document.querySelector(".js_confirm_order");
let firstPayRadioBtn = document.querySelector(".js_handle_payment_radio");
let secondPayRadioBtn = document.querySelector(".js_handle_payment_radio_two");
let pickContainer = document.querySelector(".js_handle_pick_container")


proceedBtn.style.opacity = 1;

pickupRadioBtnSec.addEventListener("click", function () {
    if(pickContainer.innerHTML !== "" && pickupRadioBtnSec.checked){
        console.log(pickContainer.innerHTML.length);
        proceedBtn.style.opacity = 1;
        document.querySelector(".js_handle_pick_container")
        .classList.remove("hide");
    }else if (pickupRadioBtnSec.checked) {
        console.log(pickupRadioBtnSec.value);
        proceedBtn.style.opacity = .5;
        triggerBtn.classList.remove("hide");
    }
});


pickupRadioBtnFirst.addEventListener("click", function () {
    if (pickupRadioBtnFirst.checked) {
        console.log(pickupRadioBtnFirst.value);
        triggerBtn.classList.add("hide");
        document.querySelector(".js_handle_pick_container")
        .classList.add("hide");
        proceedBtn.style.opacity = 1;
    }
});

secondProceedBtn.addEventListener("click", function () {
    //pick up station dom

    console.log("event triggered");
    // let pickUpStation = document.querySelector(".pick_up_station_name");
    let address = document.querySelector(".modal_section_left .select-selected");
    pickContainer.innerHTML = `
    <div class="pickup-info">
                                    <p class="details-content__text">
                                        <span class="pick_up_station_name">${address.innerHTML}</span> Pick-Up Station
                                    </p>

                                    <p class="details-content__text">
                                        9, Commercial Avenue, Sabo, Yaba
                                    </p>

                                    <p class="details-content__text">
                                        Shipping Fee:
                                        <span>
                                            ₦&nbsp; 400
                                        </span>
                                    </p>
                                    <button class="chkout-btn second_trigger_btn">
                                    <span>CHANGE PICKUP STATION</span>
                                </button>
                                </div>
    `;
    proceedBtn.style.opacity = 1;
    triggerBtn.classList.add("hide");
    toggleModal();
    addEventToNewBtn();
    
});

/*  */


proceedBtn.addEventListener("click", function () {
    if (proceedBtn.style.opacity == 1) {
        let thirdSection = document.querySelector(".js_handle_third_section");
        let secondSvg = document.querySelector("#second_process_svg path");
        secondSvg.setAttribute("fill", "rgb(67, 184, 64)");
        thirdSection.classList.remove("hide");
    }
})

//code browser to animate on process 3

firstPayRadioBtn.addEventListener("click", function (e) {
    if (firstPayRadioBtn.checked) {
        let thirdSvg = document.querySelector("#third_process_svg path");
        thirdSvg.setAttribute("fill", "rgb(67, 184, 64)");
    }
})

secondPayRadioBtn.addEventListener("click", function (e) {
    if (secondPayRadioBtn.checked) {
        let thirdSvg = document.querySelector("#third_process_svg path");
        thirdSvg.setAttribute("fill", "rgb(67, 184, 64)");
    }
})

// confirmButton.addEventListener("click", function)

function addEventToNewBtn() {
    document.querySelector(".second_trigger_btn").addEventListener("click", function(){
        toggleModal();
    })
}


$(function () {

    $(".js_confirm_order").click(function () {
        window.location = $(this).find("a").attr("href");
        return false;
    });

});