<script>
  var input = document.querySelector("#phone");
  var iti  = window.intlTelInput(input);
  iti.setCountry("ng");
   $("#search-image").on('click', function(){
     $("#search-form").submit();
   })
   $("#search-btn-topbar").on('click', function(){
     $("#search-form-topbar").submit();
   })
  var loginForm = $("#modal-login-form");
  loginForm.submit( function(e){
    $(".loader-msg").hide();
    $(".email-login-error").hide();
    $(".password-login-error").hide();
    $(".loader-gif").show();
    e.preventDefault();
    $('#modal-login-btn').prop("disabled", true);
    //alert('Submitted');
    var formData = loginForm.serialize();
    $.ajax({
       url: '{{ route('login') }}',
       type:'POST',
       data:formData,
      success:function(data){
        $(".loader-gif").hide();
         console.log(data);
         if(data.user == 'admin'){
           location = '{{ url("/panel") }}';
         }else{
           if(data.verified){
             location.reload();
           }else{
             //location = '{{route("email-verification.index")}}';
             $("#login").modal('hide');
             $("#verifyemail").modal('show');
             //location.reload();
             //$("#verifyemail").modal('show');
           }
         }
      },
      error: function (data) {

         $(".loader-gif").hide();
         // Show proper error
         //console.log(data.responseJSON);
         if(data.responseJSON.errors != undefined){
           let errObj = data.responseJSON.errors;
         for(var key in errObj) {
           // check if the property/key is defined in the object itself, not in parent
          if (errObj.hasOwnProperty(key)) {
             let spanName = "." + key + "-" + "login-error";
             console.log(spanName);
             let errorMsg = "";
             errObj[key].forEach(function(y) {
               if(errorMsg == ""){
                 errorMsg = y + "<br>";
               }else{
                 errorMsg = errorMsg + y + "<br>";
               }
             });
             console.log(errorMsg);
             $(spanName).html(errorMsg);
             $(spanName).show();
          }
         }
         }
         $(".loader-msg").show();
         //
         $('#modal-login-btn').prop("disabled", false);

      },
      completed: function (data) {

         $(".loader-gif").hide();
         $('#modal-login-btn').prop("disabled", false);
      }
    });
    $("#login").on('hidden.bs.modal', function () {
       // do something…
       $(".signup__form-input").val('');
       $(".loader-msg").hide();
       $(".email-login-error").hide();
       $(".password-login-error").hide();
    })
    $("#verifyemail").on('hidden.bs.modal', function () {
       // do something…
       location.reload();
    })
    $("#login").on('show.bs.modal', function () {
       // do something…
       $(".signup__form-input").val('');
       $(".loader-msg").hide();
    })

  });
  var phoneValid = true;
  var accountForm = $("#modal-account-form");
  var accountBtn = $("#modal-account-btn");
  accountBtn.on('click', function(e){
    $("#myFormCheck").val('');
    var isValid = iti.isValidNumber();
    if(!isValid){
      $("#phone")[0].setCustomValidity("Invalid phone");
      //$("#phone").val('Meeeee');
      //accountForm.submit();
    }else{
      $("#phone")[0].setCustomValidity("");
      $("#myFormCheck").val('valid');
    }
  });
  accountForm.submit( function(e){
    $(".loader-account-msg").hide();
    $(".loader-account-gif").show();
    $(".email-error").hide();
    $(".password-error").hide();
    $(".name-error").hide();
    e.preventDefault();
    $("#modal-account-btn").prop("disabled", true);
    var formData = accountForm.serialize();
    $.ajax({
       url: '{{ route('auth.ajaxaccount') }}',
       type:'POST',
       data:formData,
      success:function(data){
        $("#modal-account-btn").prop("disabled", false);
        $(".loader-account-gif").hide();
         console.log(data);
         if(data.status == true){
           //location = '{{route("email-verification.index")}}';
           $("#signup-one").modal('hide');
           $("#verifyemail").modal('show');

         }else{
           $(".loader-account-msg").show();
         }
      },
      error: function (data) {

         $(".loader-account-gif").hide();
         $(".loader-account-msg").show();
         $("#modal-account-btn").prop("disabled", false);
         let errObj = data.responseJSON.msg;
         //console.log(data.responseJSON);
         for(var key in errObj) {
           // check if the property/key is defined in the object itself, not in parent
          if (errObj.hasOwnProperty(key)) {
             let spanName = "." + key + "-" + "error";
             console.log(spanName);
             let errorMsg = "";
             errObj[key].forEach(function(y) {
               if(errorMsg == ""){
                 errorMsg = y + "<br>";
               }else{
                 errorMsg = errorMsg + y + "<br>";
               }
             });
             console.log(errorMsg);
             $(spanName).html(errorMsg);
             $(spanName).show();
          }
         }
      },
      completed: function (data) {
         $(".loader-account-gif").hide();
         $("#modal-account-btn").prop("disabled", false);
      }
    });
    $("#login").on('hidden.bs.modal', function () {
       // do something…
       $(".signup__form-input").val('');
       $(".loader-msg").hide();
    })
    $("#login").on('show.bs.modal', function () {
       // do something…
       $(".signup__form-input").val('');
       $(".loader-msg").hide();
    });

    $("#signup-one").on('hidden.bs.modal', function () {
       // do something…
       $(".signup__form-input").val('');
       $(".loader-account-msg").hide();
    })
    $("#signup-one").on('show.bs.modal', function () {
       // do something…
       $(".signup__form-input").val('');
       $(".loader-account-msg").hide();
    })

  });

  $(".login__forgot-password").on('click', function(){
    $("#login").modal('hide');
    $("#recover").modal('show');
  });

  $("#recover").on('show.bs.modal', function () {
     // do something…
     $(".loader-recover-success-msg").hide();
     $(".loader-recover-msg").hide();

  });
  $("#recover").on('hide.bs.modal', function () {
     $("#recover-email").val('');
  })

  var recoverForm = $("#modal-recover-form");
  recoverForm.submit( function(e){
    $(".loader-recover-success-msg").hide();
    $(".loader-recover-msg").hide();
    e.preventDefault();
    $(".loader-recover-msg").hide();
    $(".loader-recover-gif").show();
    $('#modal-recover-btn').prop("disabled", true);
    //alert('Submitted');
    var formData = recoverForm.serialize();
    $.ajax({
       url: '{{ route('apirecoverpass') }}',
       type:'POST',
       data:formData,
      success:function(data){
        $(".loader-recover-gif").hide();
        $(".loader-recover-success-msg").show();
        $("#recover-email").val('');
      },
      error: function (data) {

         $(".loader-recover-gif").hide();
         $(".loader-recover-msg").show();
         $('#modal-recover-btn').prop("disabled", false);
      },
      completed: function (data) {

         $(".loader-recover-gif").hide();
         $('#modal-recover-btn').prop("disabled", false);
      }
    });
  });
</script>
