@extends('panel::layouts.master-market')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.6/dist/quill.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.6/dist/quill.snow.css" rel="stylesheet"/>

    <h2>Edit Store</h2>
    <br />
    @include('alert::bootstrap')

    <div class="container-fluid">
      <div class="row">
      <div class="col-sm-3 d-none d-sm-block">
          <div id="sidebar" class="p-0 mt-0">

              <div class="card w-100">
               <a data-scroll="details_section" class="card-body clickable {{ active(['panel.stores.edit']) }}" href="#details_section">
              <h6 class="card-title mb-1">{{ __("Stores Details") }}</h6>
              <p class="card-text small">{{ __("Enter the description of your store.") }}</p>
              </a>
              <a data-scroll="images_section" class="card-body clickable" href="#images">
                  <h6 class="card-title mb-1">{{ __("Images") }}</h6>
                  <p class="card-text  small">{{ __("Upload at least one image to make your listing stand out.") }}</p>
              </a>
            </div>

      </div>
    </div>
    <div class="col-sm-9 pl-sm-5">

      <div class="row">
        <div class="col-6">
            <h5 class="text-dark">{{ __("Edit Store") }}</h5>
            <p class="text-muted pt-0 mb-2">{{ $store->name }}
              <a href="{{$store->url}}/page" class="small">(view)</a><br>
              <a href="{{ route('panel.setupstore', ['id' => $store->id]) }}" class="small">(Setup)</a>
              <a href="{{ route('panel.listings.create') }}?store_id={{ $store->id }}" class="small">(Add product)</a>
              <a href="{{ route('panel.listings.index') }}?store_id={{ $store->id }}" class="small">(Manage products)</a>
            </p>
            <p>
              <a href="{{ route('panel.storeledger.index', $store) }}" class="small">Change Ownership</a>
            </p>
            <div class="mb-3">
                <span class="badge badge-pill badge-secondary mr-2"></span>
            </div>
        </div>
        <div class="col-6 pt-4">

        @if(!$store->is_published)
         {!! Form::model($store, ['route' => ['panel.stores.update', $store], 'class' => 'form', 'method' =>'PUT']) !!}
          <input type="submit" name="publish" value="{{ __('Publish')  }}" class="btn btn-primary float-right">
         {!! Form::close() !!}
         @else
          {!!  Form::model($store, ['route' => ['panel.stores.update', $store], 'class' => 'form float-left', 'method' => 'PUT']) !!}

         {!! Form::close() !!}
          {!!  Form::model($store, ['route' => ['panel.stores.update', $store], 'class' => 'form float-left', 'method' => 'PUT']) !!}

                <input type="submit" name="draft" value="{{ __('Unpublish') }}" class="btn btn-danger float-right">

          {!! Form::close() !!}
          {!! Form::close() !!}
           {!!  Form::model($store, ['route' => ['panel.stores.update', $store], 'class' => 'form float-left', 'method' => 'PUT']) !!}
             @if($store->is_verified)
                 <input type="submit" name="verify" value="{{ __('Unverify') }}" class="btn btn-danger float-right" style="cursor: default">
             @else
                 <input type="submit" name="verify" value="{{ __('Verify') }}" class="btn btn-primary float-right" style="cursor: default">
             @endif
           {!! Form::close() !!}
          @endif
        </div>
      </div>
      {!! Form::model($store, ['route' => ['panel.stores.update', $store], 'class' => 'formsy form', 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
      <a id="details_section"></a>
      @include('panel::stores.partials.details')
      <a id="images_section"></a>
      @include('panel::stores.partials.images')


      <div class="card mb-4">

          <div class="card-body">

              <input id="save-listing" type="submit" value="{{ __('Edit store')  }}" class="btn  btn-primary float-right">

          </div>

      {!! Form::close() !!}
    </div>
    </div>
  </div>

  <script>

    function form_submit(){
      $( ".formsy").unbind("submit");
      $('.formsy').submit(function (evt) {
       evt.preventDefault();
       if($.trim($('#inputName').val()) == "" || $.trim($('#inputPosition').val()) == ""){
         alert("Name or Position cannot be blank");
         return;
       }
       $.ajax({
         url: "{{route('create.team', $store)}}",
         type: "POST",
         data:  new FormData(this),
         contentType: false,
         cache: false,
        processData:false,
       beforeSend : function(request)
       {
        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        //$("#preview").fadeOut();
        //$("#err").fadeOut();
      },
       success: function(data)
      {
      $("#inputId").val(0);
      $("#inputPosition").val("");
      $("#inputName").val("");
        location.reload();
      },
      error: function(e)
      {
       //$("#err").html(e).fadeIn();
      }
      });
       //window.history.back();
      });
     }
    $(document).ready(function(){

    $("#save-listing").on('click', function(){
     //alert('me');
     $("#inputId").val(0);
     $( ".formsy").unbind("submit");
     $(".formsy").submit();
    })
    });
  </script>
@stop
