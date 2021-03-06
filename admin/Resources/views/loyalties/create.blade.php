@extends('panel::layouts.master')

@section('content')
<div class="container">
    <a href="{{ route('panel.loyalties.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            @if(!$form->getModel())
            <h2  class="mt-xxs">Adding new loyalty</h2>
            @else
            <h2  class="mt-xxs">Editing loyalty</h2>
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
                  {!! form_end($form, false) !!}
</div>
</div>
</div>

@endsection
