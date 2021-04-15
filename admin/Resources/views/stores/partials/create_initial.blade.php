<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Store details") }}</h6>
    <div class="card-body">
        <div class="form-group">
            <label>{{ __("Name") }} <span class="required">*</span></label>
            {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>

        <div class="form-group">
            <label>{{ __("Description") }} <span class="required">*</span></label>
            <div id="description" style="height: 200px">

            </div>
            {!! Form::hidden('description', null, ['class' => 'form-control']) !!}
            {!! Form::hidden('directory_id', $directory_id, ['class' => 'form-control']) !!}
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
