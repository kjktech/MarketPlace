<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Directory details") }}</h6>
    <div class="card-body">
        <div class="form-group">
            <label>{{ __("Title") }} <span class="required">*</span></label>
            {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>

        <div class="form-group">
            <label>{{ __("Description") }} <span class="required">*</span></label>
            <div id="description" style="height: 200px">

            </div>
            {!! Form::hidden('description', null, ['class' => 'form-control']) !!}
            {!! Form::hidden('store_id', $store_id, ['class' => 'form-control']) !!}
       </div>
       <div class="form-group">
           <label>{{ __("Select a category") }}</label>
           {!! Form::select('category', $categories, null, ['class' => 'autocomplete form-controls', 'required' => 'required']) !!}
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
$('.autocomplete_b').selectize({
    create: false,
    onChange: function (val) {
        $('.payment_type').val(val);

        //Intercooler.triggerRequest('.create-form');
    }
});
$(".create-form").submit(function(e){
  //e.preventDefault();
  if($('input[name=description]').val().length < 3){
    alert('Please enter description');
    e.preventDefault();
  }
});
</script>
