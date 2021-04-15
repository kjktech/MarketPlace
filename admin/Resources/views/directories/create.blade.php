@extends('panel::layouts.master')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.6/dist/quill.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.6/dist/quill.snow.css" rel="stylesheet"/>

    <h2>
      @if($topbrand)
       {{ __("Create Top Brand") }}
      @else
       {{ __("Create Business") }}
      @endif
    </h2>
    <br />
    @include('alert::bootstrap')

    <div class="container-fluid">
      <div class="row">
      <div class="col-sm-3 d-none d-sm-block">
          <div id="sidebar" class="p-0 mt-0">

              <div class="card w-100">
               <a data-scroll="details_section" class="card-body clickable {{ active(['create.edit']) }}" href="#details_section">
              <h6 class="card-title mb-1">{{ __("Business Details") }}</h6>
              <p class="card-text small">{{ __("Enter the tile, description and location of your listing.") }}</p>
              </a>
            </div>

      </div>
    </div>
    <div class="col-sm-9 pl-sm-5">

      <div class="row">
        <div class="col-6">
            <h5 class="text-dark">
              @if($topbrand)
               {{ __("Create Top Brand") }}
              @else
               {{ __("Create Business") }}
              @endif
            </h5>
        </div>

      </div>
      {!! Form::model(null, ['route' => ['panel.directories.store'], 'class' => 'formsy form create-form', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
      @if($topbrand)
        {!! Form::hidden('topbrand', 1) !!}
      @endif
      <a id="details_section"></a>
      @include('panel::directories.partials.create_initial')
      <div class="card mb-4">

          <div class="card-body">

              <input id="save-listing" type="submit" value="{{ __('Save business')  }}" class="btn  btn-primary float-right">

          </div>

      {!! Form::close() !!}
    </div>
    </div>
  </div>
@stop
