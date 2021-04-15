<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Social details") }}</h6>
    <div class="card-body">
      <div class="col-sm-12">
        <div class="form-group mt-2">
            <label>{{ __("Linkedin") }} <small class="text-muted">{{ __("(optional)") }}</small></label>
            {!! Form::url('pages[0][link]', $linkedin, ['class' => 'form-control', 'style' => 'border-lefts: 1px dashed #eee', 'placeholder' =>__("e.g. https://facebook.com/afiaanyi")]) !!}
            {!! Form::hidden('pages[0][key]', "linkedin", ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form-group mt-2">
            <label>{{ __("Facebook") }} <small class="text-muted">{{ __("(optional)") }}</small></label>
            {!! Form::url('pages[1][link]', $facebook, ['class' => 'form-control', 'style' => 'border-lefts: 1px dashed #eee', 'placeholder' => __("e.g. https://facebook.com/afiaanyi")]) !!}
            {!! Form::hidden('pages[1][key]', "facebook", ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form-group mt-2">
            <label>{{ __("Twitter") }} <small class="text-muted">{{ __("(optional)") }}</small></label>
            {!! Form::url('pages[2][link]', $twitter, ['class' => 'form-control', 'style' => 'border-lefts: 1px dashed #eee', 'placeholder' => __("e.g. https://twitter.com/afiaanyi")]) !!}
            {!! Form::hidden('pages[2][key]', "twitter", ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form-group mt-2">
            <label>{{ __("Instagram") }} <small class="text-muted">{{ __("(optional)") }}</small></label>
            {!! Form::url('pages[3][link]', $instagram, ['class' => 'form-control', 'style' => 'border-lefts: 1px dashed #eee', 'placeholder' => __("e.g. https://instagram.com/afiaanyi")]) !!}
            {!! Form::hidden('pages[3][key]', "instagram", ['class' => 'form-control']) !!}
        </div>
      </div>
   </div>
</div>
