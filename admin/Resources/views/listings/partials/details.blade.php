<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Listing details") }}</h6>
    <div class="card-body">


        <div class="form-group">
            <label>{{ __("Title") }}</label>
            {!! Form::text('title', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{ __("Description") }}</label>
            <div id="description" style="height: 200px">
                {!! $listing->description !!}
            </div>
            {!! Form::hidden('description', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label>{{ __("Category") }}</label>
            {!! Form::select('category_id', $categories, null, ['class' => 'autocomplete form-controls ']) !!}
        </div>
        <div class="form-group">
            <label>{{ __("Brands") }}</label>
            {!! Form::select('brand_id', $brands, null, ['class' => 'autocomplete form-controls ']) !!}
        </div>

    </div>

</div>


<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Location") }}</h6>
    <div class="card-body">


        <div class="form-group">

			<div class="row mt-4">
				<div class="col-6">
					<div class="form-group">

            <label>{{ __("City") }}</label>
            {!! Form::select('city', $city_array, $city, ['class' => 'autocomplete form-controls ']) !!}
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>{{ __("Country") }}</label>
						{!! Form::text('country', null, ['class' => 'form-control', 'id' => 'country', 'readonly'=>'readonly']) !!}
					</div>
				</div>
			</div>

        </div>

    </div>
</div>

<script>
    if($('#description').length) {
        var quill = new Quill('#description', {
            placeholder: '',
            theme: 'snow'  // or 'bubble'
        });
        quill.on('editor-change', function (eventName, args) {
            $('input[name=description]').val(quill.root.innerHTML);
        });
    }

</script>
