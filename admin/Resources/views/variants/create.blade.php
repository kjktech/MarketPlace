@extends('panel::layouts.master')

@section('content')
<div class="container">
    <a href="{{ route('panel.brands.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            @if(!$form->getModel())
            <h2  class="mt-xxs">Adding new variant</h2>
            @else
            <h2  class="mt-xxs">Editing Variant</h2>
            @endif
        </div>
        <div class="col-sm-4">

        </div>
    </div>

    <div class="row">

        <div class="col-sm-12">

          <div class="panel panel-default">
              <div class="panel-body">

                  {!! form_start($form)  !!}

                  {!! form_rest($form)   !!}
                  {!! form_end($form, false)   !!}
</div>
</div>
</div>
<script>

//$('.values_string').val('Best,West');
function show_tags() {
    $('.values_string').each(function (i, obj) {
        if ($(obj)[0].selectize !== undefined) {
            $(obj)[0].selectize.destroy();
        }
    });

  var $select =   $('.values_string').selectize({
        delimiter: ',',
        persist: true,
        create: function (input) {
            return {
                value: input,
                text: input
            }
        },
    });

    neighborhood_autocomplete = $select[0].selectize;
    //$select.addOption({text: "My Default Value", value: "My Default Value"});
    //$select.setValue("My Default Value");
    console.log(neighborhood_autocomplete);

    neighborhood_autocomplete.addOption({
      value: "Option",
      text: "Option",
    });

}
show_tags();


</script>
@endsection
