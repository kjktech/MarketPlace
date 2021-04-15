<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Directory details") }}</h6>
    <div class="card-body">
        <div class="form-group">
            <label>{{ __("Name") }} <span class="required">*</span></label>
            {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>

        <div class="form-group">
            <label>{{ __("Description") }} <span class="required">*</span>
              <span id="word-count">0/1000 words</span>
            </label>
            <div id="description" style="height: 200px">

            </div>
            {!! Form::hidden('description', null, ['class' => 'form-control']) !!}
       </div>
       <div class="form-group">
           <label>{{ __("Select a category") }}</label>
           {!! Form::select('category', $categories, null, ['class' => 'autocomplete form-controls', 'required' => 'required']) !!}
       </div>

       <div class="form-group">
           <label>{{ __("Select your slot") }}</label>
          {!! Form::select('payment_type', $payment_types, request('payment_type'), ['class' => 'autocomplete_b form-controls']) !!}
       </div>
</div>
</div>
<script>
const limit = 1000;
if($('#description').length) {
    var quill = new Quill('#description', {
        placeholder: '',
        theme: 'snow'  // or 'bubble'
    });
    $("#word-count").html(quill.getLength() + "/1000");
    quill.on('editor-change', function (eventName, ...args) {
      if (quill.getLength() <= limit) {
        $("#word-count").html(quill.getLength() + "/1000");
        $('input[name=description]').val(quill.root.innerHTML);
      }else{
        if (eventName === 'text-change') {
          // args[0] will be delta
          const { ops } = args[0];
          let updatedOps;
         if (ops.length === 1) {
           // text is inserted at the beginning
           updatedOps = [{ delete: ops[0].insert.length }];
         } else {
           console.log("meeeee", ops[1]);
           // ops[0] == {retain: numberOfCharsBeforeInsertedText}
           // ops[1] == {insert: "example"}
           updatedOps = [ops[0], { delete: ops[1].insert.length }];
         }
          quill.updateContents({ ops: updatedOps });
        } else if (eventName === 'selection-change') {
          // args[0] will be old range
        }
      }
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
