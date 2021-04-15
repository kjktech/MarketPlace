@extends('panel::layouts.master-market')

@section('content')

    <h2>Product Brands <a href="{{ route('panel.brands.create')}}" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add new</a></h2>

    <br />

    @include('alert::bootstrap')


{!! Form::open(['url' => url()->current(), 'method' => 'GET']) !!}
    <div class="input-group mb-3">
      {{Form::text('q', request('q'), ['class' => 'form-control', 'placeholder' => "Search..."])}}
      <div class="input-group-append">
        <button class="btn btn-secondary" type="submit">Search</button>
      </div>
    </div>
{!! Form::close() !!}

<table class="table table-sm table-striped">
  <thead class="thead- border-0">
    <tr>
      <th scope="col" class="w-50 border-0">Name</th>
      <th scope="col"  class="w-25 border-0"></th>
    </tr>
  </thead>
  <tbody>
      @foreach($brands as $item)
      <tr>
        <td>{{ $item->name }}</td>
        <td>
            <a href="#" ic-target="#main" ic-select-from-response="#main" ic-delete-from="{{ route('panel.brands.destroy', $item) }}" ic-confirm="Are you sure?" class="text-muted float-right edit-icon-space-left"><i class="fa fa-remove"></i></a>
            <a href="{{ route('panel.brands.edit', $item['id']) }}" class="text-muted float-right"><i class="fa fa-pencil"></i></a>
        </td>
      </tr>
      @endforeach

  </tbody>
</table>
@stop
