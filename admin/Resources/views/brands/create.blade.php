@extends('panel::layouts.master-market')

@section('content')
<div class="container">
    <a href="{{ route('panel.brands.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            @if(!$form->getModel())
            <h2  class="mt-xxs">Adding new brand</h2>
            @else
            <h2  class="mt-xxs">Editing brand</h2>
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
                  {!! form_until($form, 'name')  !!}
                  @if($form->getModel())
                  <label for="Brand Image" class="control-label">Brand logo</label>
                  @if($form->getModel()->logo)
                       <br>
                      <img width=91 src="{{ $form->getModel()->logo }}?time=time()" />
                  @endif
                  <div class="form-group">
                     <input name="image" type="file">
                  </div>
                  @endif
                  {!! form_rest($form)   !!}
                  {!! form_end($form, false) !!}
</div>
</div>
</div>
@endsection
