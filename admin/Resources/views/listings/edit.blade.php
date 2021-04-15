@extends('panel::layouts.master-market')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.6/dist/quill.min.js" type="text/javascript"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.6/dist/quill.snow.css" rel="stylesheet"/>

<h2>Edit Product</h2>
<br />
@include('alert::bootstrap')
<div class="container-fluid">
        <div class="row">
          <div class="col-sm-3 d-none d-sm-block">
              <div id="sidebar" class="p-0 mt-0">

                  <div class="card w-100">
                      <a data-scroll="details_section" class="card-body clickable {{ active(['create.edit']) }}" href="#details_section">
                  <h6 class="card-title mb-1">{{ __("Product Details") }}</h6>
                  <p class="card-text small">{{ __("Enter the tile, description and location of your product.") }}</p>
                  </a>
                  <a data-scroll="images_section" class="card-body clickable" href="#images">
                      <h6 class="card-title mb-1">{{ __("Images") }}</h6>
                      <p class="card-text  small">{{ __("Upload at least one image to make your product stand out.") }}</p>
                  </a>
                  @if(count($listings_form) > 0)
                  <a data-scroll="additional_section" class="card-body clickable" href="#additional_information">
                      <h6 class="card-title mb-1">{{ __("Additional Information") }}</h6>
                      <p class="card-text small">{{ __("Enter any relevant characteristics and/or specifications.") }}</p>
                  </a>
                  @endif

                  <a data-scroll="pricing_section" class="card-body clickable" href="#pricing_section">
                      <h6 class="card-title mb-1">{{ __("Pricing & specifications") }}</h6>
                      <p class="card-text small">{{ __("Set-up pricing and availability for your product.") }}</p>
                  </a>

              </div>

          </div>
      </div>
      <div class="col-sm-9 pl-sm-5">
          <div class="row">
            <div class="col-6">
                <h5 class="text-dark">{{ __("Edit Product") }}</h5>
                <p class="text-muted pt-0 mb-2">{{ $listing->title }}
                  <a href="{{ route('panel.listings.create') }}?store_id={{ $listing->store->id }}" class="small">(Add product)</a>
                  <a href="{{ route('panel.listings.index') }}?store_id={{ $listing->store->id }}" class="small">(Manage products)</a>
                  <a href="{{$listing->url}}" class="small">(view)</a>
                </p>
                <div class="mb-3">
                    <span class="badge badge-pill badge-secondary mr-2">{{ __("Category") }}: {{ $listing->category->name }}</span>
                </div>
            </div>
            <div class="col-6 pt-4">
                        @if(!$listing->is_published)
                        {!! Form::model($listing, ['route' => ['panel.listings.update', $listing], 'class' => 'form', 'method' =>
                        'PUT']) !!}
                        <input type="submit" name="publish" value="{{ __('Publish')  }}" class="btn   btn-primary float-right">
                                    {!! Form::close() !!}
                            @else
                                {!!  Form::model($listing, ['route' => ['panel.listings.update', $listing], 'class' => 'form', 'method' => 'PUT' ]) !!}
                                    @if($listing->is_verified)
                                        @if(!module_enabled('listingfee'))
                                        <input type="submit" name="draft" value="{{ __('Unpublish') }}" class="btn btn-danger float-right">
                                        @else
                                            <div class="float-right text-center">
                                            <small class="text-muted ">{{ __("Expires") }}</small><br />
                                            @if($listing->is_draft)
                                                <input type="submit" name="undraft" value="{{ __('Re-enable') }}" class="btn  btn-outline-danger btn-sm">
                                            @else
                                                <input type="submit" name="draft" value="{{ __('Disable') }}" class="btn  btn-outline-danger btn-sm">
                                                <input type="submit" name="renew" value="{{ __('Renew') }}" class="btn  btn-outline-success btn-sm">
                                            @endif
                                            </div>
                                        @endif
                                    @else
                                        <input type="button" name="draft" value="{{ __('Pending Verification') }}" class="btn  btn-lg btn-danger float-right" style="cursor: default">
                                    @endif
                                    {!! Form::close() !!}
                            @endif
                 </div>
                 </div>

                {!! Form::model($listing, ['route' => ['panel.listings.update', $listing], 'class' => 'form', 'method' => 'PUT']) !!}
                    <a id="details_section"></a>
                @include('panel::listings.partials.details')
                <a id="images_section"></a>
                @include('panel::listings.partials.images')
                <a id="pricing_section"></a>
                @include('panel::listings.partials.pricing_details')
                @include('panel::listings.partials.pricing_variants')
                <div class="card mb-4">

                    <div class="card-body">

                        <input type="submit" value="{{ __('Save listing')  }}" class="btn  btn-primary float-right">

                    </div>

                    {!! Form::close() !!}


                </div>

        </div>
      </div>

    <script>

      function show_atts(){
        $('.autocompleteb').each(function (i, obj) {
            if ($(obj)[0].selectize !== undefined) {
                $(obj)[0].selectize.destroy();
            }
        });
        $('.autocompleteb').selectize({
        create: function (input) {
            return {
                value: input,
                text: input
            }
        },
        });
        }
        function show_tags() {
            $('.tags').each(function (i, obj) {
                if ($(obj)[0].selectize !== undefined) {
                    $(obj)[0].selectize.destroy();
                }
            });

            $('.tags').selectize({
                delimiter: ',',
                persist: true,
                create: function (input) {
                    return {
                        value: input,
                        text: input
                    }
                }
            });
            select_tag = [];
            $('.tags').each(function (i, obj) {
                if ($(obj)[0].selectize !== undefined) {
                    select_tag.push(obj);
                    //$(obj)[0].selectize.addOption({text: "XS", value: "XS"});
                }
            });
        }
        //show_atts();
        //show_tags();

        $("#sidebar").stick_in_parent({offset_top: 20});

        $('#sidebar').activescroll({
            active: "active",
            offset: 20,
            animate: 1000
        });
    </script>

  @stop
