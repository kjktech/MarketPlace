
// //close modal by click window area if modal is showing
// function windowOnClick(event) {
//     if (event.target === modal) {
//       toggleModal();
//     }
//   }


// var okButton = document.querySelector(".ok");
let modal = document.querySelector(".modal_container");
let body = document.querySelector("body");

  

//   window.addEventListener("click", windowOnClick);




//toggle modal
function toggleModal(event) {
  modal.classList.toggle("show_modal");
  body.classList.toggle("hide_scroll")
}