<div data-toggle-name="times_section" class="hidden"
     style="display: none;background: #fff; position: fixed; top: 0; right: 0; width: 50%; max-width: 360px; height: 100vh; z-index: 300;">
    <div class="p-0" style="overflow-y: scroll; height: 100vh;">
        <div id="editor">

        </div>
    </div>
</div>

<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Pricing specifications") }}</h6>
    <div class="card-body">
        <div class="row ">
           <div class="col-sm-6">
             <label>Weight (Kg) <span class="required">*</span></label>
             {!! Form::number('weight', null, ['class' => 'form-control', 'step' => 'any']) !!}
           </div>
        </div>
        <br>
        <div class="row ">
          <div class="col-sm-12 col-md-4">
            <label>Length (cg)</label>
            {!! Form::number('length', null, ['class' => 'form-control', 'step' => 'any']) !!}
          </div>
          <div class="col-sm-12 col-md-4">
            <label>Width (cm)</label>
            {!! Form::number('width', null, ['class' => 'form-control', 'step' => 'any']) !!}
          </div>
          <div class="col-sm-12 col-md-4">
            <label>height (cm)</label>
            {!! Form::number('height', null, ['class' => 'form-control', 'step' => 'any']) !!}
          </div>
        </div>
   </div>
</div>

<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Pricing information") }}</h6>
    <div class="card-body">
        <div class="row ">

            <div class="col-sm-6">
                <div class="form-group mt-2">
                    <label>{{ __("Price in :currency", ['currency' => $listing->currency]) }} <span class="required">*</span></label>
                    <div class="input-group mb-0">
                        {!! Form::text('price', null, ['class' => 'form-control']) !!}

                        @if($listing->pricing_model->widget != 'request')
                        <div class="input-group-append">
                            @if($listing->pricing_model->widget == 'book_date')
                                <span class="input-group-text">{{ __("per") }} {{ $listing->pricing_model->duration_name }}</span>
                            @else
                                <span class="input-group-text">{{ __("per") }} {{ $listing->pricing_model->pricing_unit }}</span>
                            @endif
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            <div class="col-sm-6">
                @if($listing->pricing_model->widget == 'request')
                    <div class="form-group mt-2">
                        <label>{{ __("Pricing display name") }} <small class="text-muted">{{ __("(optional)") }}</small></label>
                        {!! Form::text('price_per_unit_display', null, ['class' => 'form-control', 'style' => 'border-lefts => 1px dashed #eee', 'placeholder' =>__("e.g. per...")]) !!}
                        <small class="form-text text-muted">
                            {{ __("e.g. per week, per kg, per box, per unit") }}
                        </small>
                    </div>
                @else
                    <div class="form-group mt-2">
                        <label>{{ $listing->pricing_model->quantity_label }}</label>
                        {!! Form::number('stock', null, ['class' => 'form-control']) !!}
                        @if($listing->pricing_model->widget == 'buy')
                        <p class="form-text text-muted small">{{ __("Only applicable if the item does not have variants") }}</p>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-sm-6">
                <div class="form-group mt-2">
                    <label>{{ __("Discount in :percentage, between 0 - 100") }}</label>
                    <div class="input-group mb-0">
                        {!! Form::number('discount', null, ['class' => 'form-control', 'max' => 100, 'min' => 0]) !!}
                    </div>

                </div>
            </div>
        </div>

        <div class="row ">

            @if($listing->pricing_model->widget == 'book_date')
            <div class="col-sm-6">
                <div class="form-group mt-2">
                    <label>{{ __('Minimum rent period') }} <small class="text-muted">({{ ($listing->pricing_model->duration_name) }})</small></label>
                    {!! Form::text('min_duration', null, ['class' => 'form-control']) !!}

                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group mt-2">
                    <label>{{ __('Maximum rent period') }} <small class="text-muted">({{ ($listing->pricing_model->duration_name) }})</small></label>
                    {!! Form::number('max_duration', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            @endif


            @if($listing->pricing_model->widget == 'book_time')
            <div class="col-sm-6">
                <div class="form-group mt-2">
                    <label>{{ __("Length of") }} {{($listing->pricing_model->duration_name)}} (mins)</label>
                    {!! Form::text('session_duration', null, ['class' => 'form-control']) !!}
                    <small id="setupTimes" class="form-text text-muted">
                        <a href="#setupTimes" data-toggle-target="times_section"  ic-get-from="{{route('create.times', $listing)}}" ic-target="#editor" ic-select-from-response="#editor-area-wrapper" ic-replace-target="true">{{ __("Set-up availability") }}</a></small>

                </div>
                <div></div>
            </div>
            @endif

        </div>

    </div>
</div>
