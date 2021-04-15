<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Store details") }}</h6>
    <div class="card-body">


        <div class="form-group">
            <label>{{ __("Name") }} <span class="required">*</span></label>
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{ __("Description") }} <span class="required">*</span></label>
            <div id="description" style="height: 200px">
                {!!  $store->description !!}
            </div>
            {!! Form::hidden('description', null, ['class' => 'form-control']) !!}

        <br>

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
						<label>{{ __("City") }} <span class="required">*</span></label>

            {!! Form::select('city', $city_array, $city, ['class' => 'state-select autocomplete form-controls ']) !!}
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>{{ __("Country") }}</label>
						{!! Form::text('country', null, ['class' => 'form-control', 'id' => 'country', 'readonly' => 'readonly']) !!}
					</div>
				</div>
        <div class="col-6">
        <div class="form-group">
          <label>{{ __("Lga") }} <span class="required">*</span></label>

          {!! Form::select('city_id', $lga_array, $store->city_id, ['class' => 'lga-select autocomplete form-controls ']) !!}
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
    $(".state-select").on('change', function(){
      let state_name = $(this).val();
      $.ajax({
        url: '{{route('ajax.getcities')}}?state_name=' + state_name,
        method: 'GET',
        error: function(){

        },
        success: function(response){
          let cities_arr = [];
          //$(".lga-select").empty();
          $.each(response.cities, function(index, value){
            //$(".lga-select").append(`<option value=${value.id}>${value.name}</option>`);
              cities_arr.push({'value':value.id, 'text':value.name});
          });
          var selectize = $(".lga-select")[0].selectize;
          selectize.clear();
          selectize.clearOptions();
          selectize.renderCache['option'] = {};
          selectize.renderCache['item'] = {};
          selectize.addOption(cities_arr);
          selectize.setValue(cities_arr[0].value);
        },
        complete: function(){

        }
      });
    });
</script>
