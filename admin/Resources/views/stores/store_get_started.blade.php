@extends('panel::layouts.master-market')

@section('content')
<script src="https://use.fontawesome.com/2c7a93b259.js"></script>
<section class="seller-profile">
  <div class="seller-profile__started-heading">
    <h3>Getting Started</h3>
    <p>Thank you for registering to sell on Afiaanyi.
      Please complete the following tasks for your store to be approved to go live.</p>
    </div>
    <div class="seller-profile__started">
      <nav class="seller-profile__started-nav">
        <div class="nav" id="nav-tab" role="tablist">
          <a class="seller-profile__started-tab seller-profile__started-tab-active" id="nav-terms-tab"
          data-toggle="tab" href="#nav-terms" role="tab" aria-controls="nav-terms" aria-selected="true">
          <svg class="icon icon-checked">
            <use xlink:href="#icon-checked"></use>
          </svg>
          <p>Terms & Conditions</p>
        </a>
        <a class="seller-profile__started-tab" id="nav-how-it-works-tab" aria-disabled="true" data-toggle="tab"
        href="#nav-how-it-works" role="tab" aria-controls="nav-how-it-works" aria-selected="false">
        <svg class="icon icon-checked">
          <use xlink:href="#icon-checked"></use>
        </svg>
        <p>How it Works</p>
      </a>
      <a class="seller-profile__started-tab" id="nav-valid-id-tab" data-toggle="tab" href="#nav-valid-id"
      role="tab" aria-controls="nav-valid-id" aria-selected="false">
      <svg class="icon icon-checked">
        <use xlink:href="#icon-checked"></use>
      </svg>
      <p>Upload Valid ID</p>
    </a>
    <a class="seller-profile__started-tab" id="nav-verify-bank-tab" data-toggle="tab" href="#nav-verify-bank"
    role="tab" aria-controls="nav-verify-bank" aria-selected="false">
    <svg class="icon icon-checked">
      <use xlink:href="#icon-checked"></use>
    </svg>
    <p> Bank Account Details</p>
  </a>
</div>

</nav>
<div class="tab-content" id="nav-tabContent">
<div class="tab-pane fade show active seller-profile__started-content-wrapper" id="nav-terms" role="tabpanel"
aria-labelledby="nav-terms-tab">
<p>Please read through the terms & conditions</p>
<div class="seller-profile__started-content">
  <h3>Afiaanyi Merchant Service Agreement</h3>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
    labore et dolore magna aliqua. Vitae justo eget magna fermentum iaculis eu non diam. Nisl
    rhoncus mattis rhoncus urna. Ultricies lacus sed turpis tincidunt id. Et sollicitudin ac
    orci phasellus egestas tellus rutrum. Neque egestas congue quisque egestas diam in arcu
    cursus. Sed cras ornare arcu dui vivamus arcu. Id leo in vitae turpis. Suspendisse potenti
    nullam ac tortor vitae purus. Risus in hendrerit gravida rutrum quisque non tellus orci.
  </p>

  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
    labore et dolore magna aliqua. Vitae justo eget magna fermentum iaculis eu non diam. Nisl
    rhoncus mattis rhoncus urna. Ultricies lacus sed turpis tincidunt id. Et sollicitudin ac
    orci phasellus egestas tellus rutrum. Neque egestas congue quisque egestas diam in arcu
    cursus. Sed cras ornare arcu dui vivamus arcu. Id leo in vitae turpis. Suspendisse potenti
    nullam ac tortor vitae purus. Risus in hendrerit gravida rutrum quisque non tellus orci.
  </p>
  </div>
  <div class="d-flex">
    <label class="user-notification__label mr-auto">
      <input type="checkbox" name="new follower" checked>
      <span class="signup__form-subscribe">I agree to the terms and conditions</span>
      <span class="user-notification__checkbox"></span>
    </label>
    <button class="seller-profile__started-button" id="how-it-works-next">Next</button>
  </div>

</div>
<div class="tab-pane fade seller-profile__started-content-wrapper" id="nav-how-it-works" role="tabpanel"
aria-labelledby="nav-how-it-works-tab">
<p>Please read through how Afiaanyi works</p>
<div class="seller-profile__started-content-works">
  <button class="seller-profile__started-content-works-button ml-0" >
    <a href="http://www.africau.edu/images/default/sample.pdf" style="color:white;" download>Download</a>
  </button>
  <p>This document will help you understand the entire system flow and how to find your way
    around</p>
  </div>
  <div class="d-flex">
    <button class="seller-profile__started-button ml-auto" id="valid-id-next">Next</button>
  </div>
</div>
<div class="tab-pane fade" id="nav-valid-id" role="tabpanel" aria-labelledby="nav-valid-id-tab">
  <div class="seller-profile__started-content-works p-4">
    <h3>Valid ID can be a scanned copy of either</h3>
    <ul class="seller-profile__started-ids">
      <li>
        <label for="driver-license">Driver's License</label>
      </li>
      <li>
        <label for="national-id">National ID</label>
      </li>
      <li>
        <label for="international-passport">International Passport</label>
      </li>
      <li>
        <label for="other-id">Other valid means of ID</label>
        <p></p>
      </li>
    </ul>

    <div  id="image" style="width:0 auto; max-height:270px;" >
      @if($setup)
          @if($setup->identity)
            <img width="50%" height="50%"  id="preview_image" style="width:0 auto; max-height:250px;" src="{{ $setup->identity }}"/>
         @else
           <img width="50%" height="50%"  id="preview_image" style="width:0 auto; max-height:250px;" src="{{asset('images/nofile.jpg')}}"/>
         @endif
      @else
        <img width="50%" height="50%"  id="preview_image" style="width:0 auto; max-height:250px;" src="{{asset('images/nofile.jpg')}}"/>
      @endif
      <i id="loading" class="fa fa-spinner fa-spin fa-3x fa-fw" style="position: absolute;left: 55%;top: 85%;display: none"></i>
    </div>
    <p>
      <a href="javascript:changeProfile()" style="text-decoration: none;">
        <i class="glyphicon glyphicon-edit"></i>Click to {{ ($setup and $setup->identity) ? 'Update' : 'Upload' }} Document
      </a>&nbsp;&nbsp;

    </p>
    <input type="file" id="file" style="display: none"/>
    <input type="hidden" id="file_name"/>

    <input type="hidden" id="store_id" name="store_id" value="{{ $store_id }}">


    <script>
    function changeProfile() {
      $('#file').click();
    }
    $('#file').change(function () {
      if ($(this).val() != '') {
        upload(this);
      }
    });
    function upload(img) {
      var form_data = new FormData();
      form_data.append('file', img.files[0]);
      form_data.append('store_id', document.getElementById('store_id').value);
      form_data.append('_token', '{{csrf_token()}}');
      $('#loading').css('display', 'block');
      $.ajax({
        url: "{{url('uploadFile')}}",
        data: form_data,
        type: 'POST',
        contentType: false,
        processData: false,
        success: function (data) {
          if(!data.status) {
            $('#preview_image').attr('src', '{{asset('images/nofile.jpg')}}');
            alert(data.errors['file']);
          }else{
            $('#file_name').val(data.filename);
            $('#preview_image').attr('src', data.filename);
          }
          $('#loading').css('display', 'none');
        },
        error: function (xhr, status, error) {
          alert(xhr.responseText);
          $('#preview_image').attr('src', '{{asset('images/nofile.jpg')}}');
        }
      });
    }
    </script>
  </div>
  <div class="d-flex">
    <button class="seller-profile__started-button ml-auto" id="verify-bank-next">Next</button>
  </div>

</div>
<div class="tab-pane fade" id="nav-verify-bank" role="tabpanel" aria-labelledby="nav-verify-bank-tab">

  <div class="seller-profile__started-content-works seller-profile__started-verify-bank">
    <button class="seller-profile__started-button seller-profile__started-verify-bank-button"
    data-toggle="modal" data-target="#provide-bank">{{ ($setup and $setup->bank_id) ? 'Update' : 'Provide' }}
    Bank Details</button>
  </div>
  <div class="d-flex">
    <a href="{{route('panel.stores.edit', $store)}}" class="seller-profile__started-button ml-auto" id="verify-bank-next">Next</a>
  </div>
</div>
</div>
</div>
</section>

<!-- Modal -->
<div class="modal fade verify-bank__modal" id="provide-bank" tabindex="-1" role="dialog" aria-labelledby="provide-bankLabel"
aria-hidden="true">
<div class="modal-dialog verify-bank__modal-dialog" role="document">
<div class="modal-content verify-bank__modal-content">
  <div class="modal-header verify-bank__modal-header">
    <h5 class="modal-title" id="provide-bankLabel">ENTER YOUR BANK DETAILS</h5>
  </div>
  <div class="modal-body verify-bank__modal-body">
    <i id="loading-form" class="fa fa-spinner fa-spin fa-3x fa-fw" style="position: absolute;left: 40%;display: none;"></i>
    <p>Please provide account details where your proceeds will go</p>

    <form id="bank-form" method="post">
      <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
      <div class="form-group verify-bank__modal-form">
        <label for="Bank">Bank</label>
        <select id="bank_id" name="bank_id" class="form-control" required>
          @if($setup)
             @if($setup->bank_id)
             <option value="0" disable>Select Your Bank</option>
             @foreach($banks as $bank)
               <option {{ $setup->bank_id == $bank->id  ? ' selected ': ' '}} value="{{ $bank->id }}">{{ $bank->name }}</option>
             @endforeach
             @else
             <option value="0" selected disabled>Select Your Bank</option>
             @foreach($banks as $bank)
               <option value="{{ $bank->id }}">{{ $bank->name }}</option>
             @endforeach
             @endif
             @else
          <option value="0" selected disabled>Select Your Bank</option>
          @foreach($banks as $bank)
           <option value="{{ $bank->id }}">{{ $bank->name }}</option>
          @endforeach
          @endif

        </select>
      </div>
      <div class="form-group verify-bank__modal-form">
        <label for="Account Number">Account Number</label>
        <input id="bank_number" type="text" name="bank_number" placeholder="Account Number" class="form-control" value="{{ ($setup) ? $setup->bank_number : '' }}" required>
      </div>
      <div class="form-group verify-bank__modal-form">
        <label for="Account Number">Account Name</label>
        <input id="bank_account_name" type="text" name="bank_account_name" placeholder="Account Name" class="form-control" required value="{{ ($setup) ? $setup->bank_account_name : '' }}">
      </div>
      <input type="hidden" id="bank_store_id" name="store_id" value="{{ $store_id }}">

    </div>
    <div class="modal-footer verify-bank__modal-footer">
      <button type="button" class="btn verify-bank__modal-cancel" data-dismiss="modal">&#10005; Cancel</button>
      <button id="bank-btn" type="submit" name="submit" class="btn verify-bank__modal-proceed">Proceed</button>


    </form>

  </div>
</div>
</div>
</div>


<style>
[type=file] {
position: absolute;
filter: alpha(opacity=0);
opacity: 0;
}
input,
[type=file] + label {
text-align: left;
position: relative;
}
</style>

<!-- Subscription Modal -->
<div class="modal fade verify-bank__modal" id="subscriptionModal" tabindex="-1" role="dialog" aria-labelledby="subscriptionModalLabel"
aria-hidden="true">
<div class="modal-dialog subscription__modal-dialog" role="document">
<div class="modal-content subscription__modal-content">

  <div class="modal-body subscription__modal-body">
    <h3 class="subscription__modal-heading">Welcome to the Afiaanyi Stores Subscription Page</h3>

    <p class="subscription__modal-create">Create a store</p>
    <p class="subscription__modal-foraslowas">For as low as</p>
    <h4 class="subscription__modal-price">₦10,000</h4>
    <ul class="payment__categories-individual-todo subscription__modal-benefit">
      <li>
        <div></div>As much as 40 live products
      </li>
      <li>
        <div></div>Built-in features
      </li>
      <li>
        <div></div>Online Store
      </li>
      <li>
        <div></div>Ability to upgrade
      </li>
    </ul>
    <button class="subscription__modal-button">Subscription</button>
  </div>

</div>
</div>
</div>

<script>
$('#how-it-works-next').on('click', function () {
  $('#nav-how-it-works-tab').click();
  $('#nav-terms-tab').addClass('seller-profile__started-tab-active');
})
$('#valid-id-next').click(function () {
  $('#nav-valid-id-tab').click();
  $('#nav-how-it-works-tab').addClass('seller-profile__started-tab-active');
})
$('#upload-profile-next').on('click', function () {
  $('#nav-profile-pics-tab').click();
  $('#nav-valid-id-tab').addClass('seller-profile__started-tab-active');
})
$('#verify-bank-next').on('click', function () {
  $('#nav-verify-bank-tab').click();
  $('#nav-profile-pics-tab').addClass('seller-profile__started-tab-active');
  $('#nav-verify-bank-tab').addClass('seller-profile__started-tab-active');
})
</script>
<script src="{{ asset('themes/' .  current_theme()  . '/seller/assets/js/dropzone.js') }}"></script>

<script>
$("[type=file]").on("change", function () {
// Name of file and placeholder
var file = this.files[0].name;
var dflt = $(this).attr("placeholder");
if ($(this).val() != "") {
  $(this).next().text(file);
} else {
  $(this).next().text(dflt);
}
});

//form submit
$("#bank-form").submit(function(e){
  e.preventDefault();
  $('#loading-form').css('display', 'block');
  let bank_id = $("#bank_id").val();
  if(bank_id == 0 || bank_id == "0"){
    alert("Select a Bank");
    return;
  }
  let bank_account_name = $("#bank_account_name").val();
  let bank_number = $("#bank_number").val();
  let store_id = $("#bank_store_id").val();
  let token = $("#_token").val();
  let post_var = {bank_id: bank_id, bank_account_name: bank_account_name, bank_number: bank_number, store_id: store_id, _token: token};
  $.ajax({
    url: '{{route('stores.updateProfile')}}',
    method: 'POST',
    data: post_var,
    success: function(){
      $('#loading-form').css('display', 'none');
      $("#provide-bank").modal('hide');
    },
    error: function(){
      $('#loading-form').css('display', 'none');
    },
  });
});

</script>
@stop
