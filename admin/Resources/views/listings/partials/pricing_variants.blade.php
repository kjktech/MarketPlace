@if($listing->pricing_model->can_add_variants)
<style>
.styled-select {

 height: 29px;
 overflow: hidden;
}

.styled-select select {
 background: transparent;
 border: none;
 font-size: 14px;
 height: 29px;
 padding: 5px; /* If you add too much padding here, the options won't show in IE */
 width: 208px;
}

.styled-select.slate {
 background: url(http://i62.tinypic.com/2e3ybe1.jpg) no-repeat right center;
 height: 34px;
 width: 100%;
}

.styled-select.slate select {
 border: 1px solid #ccc;
 font-size: 16px;
 height: 34px;
}
</style>
@if($listing->variant_options)
<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Variations") }}</h6>

    <div class="card-body">

        <div class="repeater-variation-option">
            <div>
                <div class="row mb-2">
                    <div class="col-sm-3 small">
                        {{ __("Attribute") }}
                    </div>
                    <div class="col-sm-8 small">
                        {{ __("Values") }} <i>({{ __("e.g. small, medium, large") }})</i>
                    </div>
                </div>
            </div>
            <div data-repeater-list="variations">
                @if(!$listing->variant_options)
                 <div data-repeater-item>
                    <div class="">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <div class="styled-select slate">
                                {!! Form::select('name', $variants, 'Select variant', ['class' => 'selectize-control single autocompleteb form-controls']) !!}
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="options" placeholder="{{ __("e.g. small, medium, large") }}" class="tags  "/>
                            </div>
                            <div class="col-sm-1">
									         <span data-repeater-delete class="btn btn-link btn-lg">
										         <span class="mdi mdi-close"></span>
									         </span>
                            </div>
                        </div>
                    </div>
                 </div>
                @else
                  @foreach($listing->variant_options as $variant_name => $variant_option)

                  <div data-repeater-item>
                    <div class="">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <div class="styled-select slate">
                                {!! Form::select('name', $variants, $variant_name, ['class' => 'selectize-control single autocompleteb form-controls']) !!}
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="options" value="{{ implode(',', $variant_option) }}" placeholder="{{ __("e.g. small, medium, large") }}" class="tags "/>
                            </div>
                            <div class="col-sm-1">
									<span data-repeater-delete class="btn btn-link btn-lg">
										<span class="mdi mdi-close"></span>
									</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>


            <div class="form-group">
                <div class="">
			  <span data-repeater-create class="btn btn-info btn-sm">
				<span class="mdi mdi-plus"></span> {{ __("Add variation") }}
			  </span>
                </div>
            </div>

        </div>


        <div class="card-bodys text-muted text-center border-top pt-1 pb-1 bg-light pl-0 hidden" style="display: none">
            <a href="" class=" small" ic-put-to="{{route('create.update', $listing)}}"><i class="mdi mdi-refresh"></i>{{ __("Update variation list") }}</a>
        </div>

        @if($listing->variant_options)
        <div class="card-bodys text-muted text-left border-top-0 pt-0 pb-0 bg-white pl-0">

            <table class="table table-sm">
                <thead class="thead-light">
                <tr>
                    <th scope="col">{{ __("Variation") }}</th>
                    <th scope="col">{{ __("Additional Price") }}</th>
                    <th scope="col">{{ __("Stock") }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($listing->variants as $variant)
                <tr>
                    <th scope="row">{{$variant->name}}</th>
                    <td>
                        <input type="text" name="variants[{{ $variant->id }}][price]" value="{{ $variant->price }}" placeholder="" class="form-control form-control-sm"/>
                    </td>
                    <td>
                        <input type="text" name="variants[{{ $variant->id }}][stock]" value="{{ $variant->stock }}" placeholder="" class="form-control form-control-sm"/>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>


        </div>
        @endif


    </div>
</div>

@endif

@if($listing->store->directory->is_topbrand)
<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Whole sale") }}</h6>
    <div class="card-body">
        <div class="row ">
          <div class="col-sm-12 col-md-6">
           <div class="form-check">
               {{ Form::checkbox('is_whole_sale', true, null, ['class' => 'form-check-input', 'id' => 'is_whole_sale']) }}
               <label>Enable WholeSale</label>
           </div>
         </div>
        </div>
        <br>
        <div class="row ">
          <div class="col-sm-12 col-md-6">
            <label>Minimum quantity</label>
            {!! Form::number('whole_sale_min_quantity', null, ['class' => 'form-control']) !!}
          </div>
        </div>

        <div class="row ">
          <div class="col-sm-12 col-md-6">
            <br>
            <label>WholeSale Price</label>
            {!! Form::text('whole_sale_price', null, ['class' => 'form-control']) !!}
          </div>
        </div>
   </div>
</div>
@endif

<script>
var_op = {!! json_encode($variants_options) !!};
console.log("dump",var_op[0]);
curr_bn = "";
var select_tag = [];
function show_demo(){
 $('.autocompleteb').unbind('change');
 $('.autocompleteb').on('change', function(e){
      //alert($('.autocompleteb').index( this ));
      //alert($(this).prop('selectedIndex'));
      if($(this).prop('selectedIndex') != 0){
          var curr_v = var_op[$(this).prop('selectedIndex') -1];
          console.log("Here ", curr_v);
          ind = $('.autocompleteb').index($(this));
          var obj = select_tag[ind];
          console.log("select_index", ind);
          curr_bn = var_op[$(this).prop('selectedIndex') -1];
          console.log("Me too",curr_v);
          $(obj)[0].selectize.clearOptions();
          $(obj)[0].selectize.clear();
          $.each(curr_bn, function(index, item){
             $(obj)[0].selectize.addOption({text: item["value"], value: item["value"]});
          });
       }else{

           ind = $('.autocompleteb').index( this );
           var obj = select_tag[ind];
           $(obj)[0].selectize.clearOptions();

       }
 });
}
function load_atts(){
  $('.autocompleteb').each(function (i, obj) {
    ind = i;
    console.log('jammer', ind);
    var obj = select_tag[ind];
    curr_bn = var_op[$(this).prop('selectedIndex') -1];

    $(obj)[0].selectize.clearOptions();
    $.each(curr_bn, function(index, item){
       $(obj)[0].selectize.addOption({text: item["value"], value: item["value"]});
    });
  });
}
$('.repeater-default').repeater({});
$('.repeater-variation-option').repeater({
defaultValues: {
  'name': 'Select variant'
},
    show: function () {

        show_tags();
        show_demo();
        load_atts();
        $(this).slideDown();
    }
})
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
show_tags();
show_demo();
console.log("count",select_tag.length);
</script>
@endif
