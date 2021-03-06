@extends('panel::layouts.master-market')

@section('content')
<div class="container">
    <a href="{{ route('panel.categories.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            @if(!$form->getModel())
            <h2  class="mt-xxs">Adding new category</h2>
            @else
            <h2  class="mt-xxs">Editing category</h2>
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
                  {!! form_until($form, 'order')  !!}
                  <label for="Parent category" class="control-label">Parent category</label>
                  <div class="form-group">
                      {!! $dropdown  !!}
                  </div>
                  @if($form->getModel())
                  <label for="Category Image" class="control-label">Category image (400 X 475)</label>
                  @if($form->getModel()->banner)
                       <br>
                      <img width=200 src="{{ $form->getModel()->banner }}?time={{time()}}" />
                  @endif
                  <div class="form-group">
                         <input name="image" type="file">
                  </div>
                  <br>
                  <label for="Category Icon" class="control-label">Category icon </label>
                  @if($form->getModel()->icon)
                      <br>
                      <img style="filter: invert(100%);" width=32 src="{{ $form->getModel()->icon }}?time={{time()}}" />
                      <br>
                  @endif
                  <div class="form-group">
                         <input name="icon" type="file">
                  </div>
                  @endif
                  {!! form_rest($form)   !!}
                  {!! form_end($form, false)   !!}
</div>
</div>
</div>
@endsection
