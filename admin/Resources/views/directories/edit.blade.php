@extends('panel::layouts.master')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.6/dist/quill.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.6/dist/quill.snow.css" rel="stylesheet"/>

    <h2>
      @if($directory->is_topbrand)
      Edit Top Brand
      @else
      Edit business
      @endif
    </h2>
    <br />
    @include('alert::bootstrap')

    <div class="container-fluid">
      <div class="row">
      <div class="col-sm-3 d-none d-sm-block">
          <div id="sidebar" class="p-0 mt-0">

              <div class="card w-100">
               <a data-scroll="details_section" class="card-body clickable {{ active(['panel.directories.edit']) }}" href="#details_section">
              <h6 class="card-title mb-1">{{ __("Business Details") }}</h6>
              <p class="card-text small">{{ __("Enter the tile, description and location of your business.") }}</p>
              </a>
              <!--
                <a data-scroll="images_section" class="card-body clickable" href="#images">
                  <h6 class="card-title mb-1">{{ __("Banner Images") }}</h6>
                  <p class="card-text  small">{{ __("Upload at least one image to make your business stand out.") }}</p>
                </a>
               -->
              @if($directory->is_topbrand)
              <!--
               <a data-scroll="video_section" class="card-body clickable" href="#video">
                   <h6 class="card-title mb-1">{{ __("Video") }}</h6>
                   <p class="card-text  small">{{ __("Upload a video to create rich content.") }}</p>
               </a>
             -->
              @endif
              <a data-scroll="opening_section" class="card-body clickable" href="#opening">
                  <h6 class="card-title mb-1">{{ __("Opening hours") }}</h6>
                  <p class="card-text  small">{{ __("Setup your opening hours.") }}</p>
              </a>
              @if($directory->is_topbrand)
              <!--
               <a data-scroll="top_section" class="card-body clickable" href="#top">
                   <h6 class="card-title mb-1">{{ __("Top Brand Details") }}</h6>
                   <p class="card-text  small">{{ __("Setup your top brand info.") }}</p>
               </a>
              -->
              @endif
            </div>

      </div>
    </div>
    <div class="col-sm-9 pl-sm-5">

      <div class="row">
        <div class="col-6">
            <h5 class="text-dark">{{ __("Edit Business") }}</h5>
            <p class="text-muted pt-0 mb-2">{{ $directory->name }} <a href="{{$directory->url}}" class="small">(view)</a>
              @if($directory->is_topbrand)
               <a href="{{ route('panel.topledger.index', $directory) }}" class="small">Change Ownership</a>
              @else
              <a href="{{ route('panel.ledger.index', $directory) }}" class="small">Change Ownership</a>
              @endif
            </p>
            <div class="mb-3">
                <span class="badge badge-pill badge-secondary mr-2">{{ __("Category") }}: {{ $directory->directory_category->name }}</span>
            </div>
        </div>
        <div class="col-6 pt-4">

        @if(!$directory->is_published)
         {!! Form::model($directory, ['route' => ['panel.directories.update', $directory], 'class' => 'form', 'method' =>'PUT']) !!}
          <input type="submit" name="publish" value="{{ __('Publish')  }}" class="btn btn-primary float-right">
         {!! Form::close() !!}
         @else
          {!!  Form::model($directory, ['route' => ['panel.directories.update', $directory], 'class' => 'form float-left', 'method' => 'PUT']) !!}
          @if($directory->topbrand)
             <input type="submit" name="undotop" value="{{ __('Undo Top Brand') }}" class="btn btn-danger float-left">
          @else
             <input type="submit" name="top" value="{{ __('Make topbrand') }}" class="btn btn-primary float-left" style="cursor: default">
          @endif
         {!! Form::close() !!}
          {!!  Form::model($directory, ['route' => ['panel.directories.update', $directory], 'class' => 'form float-left', 'method' => 'PUT']) !!}

                <input type="submit" name="draft" value="{{ __('Unpublish') }}" class="btn btn-danger float-right">

          {!! Form::close() !!}
          {!! Form::close() !!}
          @role('super-admin')
           {!!  Form::model($directory, ['route' => ['panel.directories.update', $directory], 'class' => 'form float-left', 'method' => 'PUT']) !!}
             @if($directory->is_verified)
                 <input type="submit" name="verify" value="{{ __('Unverify') }}" class="btn btn-danger float-right" style="cursor: default">
             @else
                 <input type="submit" name="verify" value="{{ __('Verify') }}" class="btn btn-primary float-right" style="cursor: default">
             @endif
           {!! Form::close() !!}
           @endrole
          @endif
        </div>
      </div>
      {!! Form::model($directory, ['route' => ['panel.directories.update', $directory], 'class' => 'formsy form', 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
      <a id="details_section"></a>
      @include('panel::directories.partials.details')
      <!--<a id="images_section"></a>-->

      @if($directory->is_topbrand)
        @include('panel::directories.partials.social_details')
      @endif
      <a id="opening_section"></a>
      @include('panel::directories.partials.opening_details')

      <div class="card mb-4">

          <div class="card-body">

              <input id="save-listing" type="submit" value="{{ __('Edit business')  }}" class="btn  btn-primary float-right">

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
         url: "{{route('create.team', $directory)}}",
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
