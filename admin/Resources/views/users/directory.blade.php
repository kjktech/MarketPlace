@extends('panel::layouts.master-users')

@section('content')

    <h2>Brands <!--<a href="{{ route('panel.directories.create')}}" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add new</a>--></h2>
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
      <th scope="col"  class="w- border-0"></th>
      <th scope="col" class="w-50 border-0">Name</th>
      <th scope="col" class="w-25 border-0">User</th>
      <th scope="col"  class="w-25 border-0"></th>
    </tr>
  </thead>
  <tbody>
      @foreach($directories as $item)
      <tr>
        <th scope="row">{{ $loop->iteration }}</th>
        <td>{{str_limit($item->name, 40)}}
          @if($item->is_verified)
             (Verified)
          @endif
        </td>
        <td>{{$item->user->email}}</td>
        <td>
            <a href="#" ic-target="#main" ic-select-from-response="#main" ic-delete-from="{{ route('panel.directories.destroy', $item) }}" ic-confirm="Are you sure?" class="text-muted float-right edit-icon-space-left"><i class="fa fa-remove"></i></a>
            <!--<a href="{{$item->edit_url}}" class="text-muted float-right"><i class="fa fa-pencil"></i></a>-->
            <a href="{{route('panel.directories.edit', $item)}}" class="text-muted float-right"><i class="fa fa-pencil"></i></a>
            <a href="{{$item->url}}" class="text-muted float-right edit-icon-space"><i class="fa fa-eye"></i></a>
            <a href="{{ route('panel.ledger.index', $item) }}" class="text-muted float-right edit-icon-space"><i class="fa fa-address-book"></i></a>
            @if(count($item->stores) > 0)
             <a href="{{route('panel.stores.edit', $item->stores[0])}}" class="text-muted float-right edit-icon-space"><i class="fa fa-shopping-cart"></i></a>
            @else
             <a href="{{ route('panel.stores.create') }}?directory_id={{$item->id}}" class="text-muted float-right edit-icon-space"><i class="fa fa-shopping-cart"></i></a>
            @endif
        </td>
      </tr>
      @endforeach

  </tbody>
</table>

{{ $directories->appends(app('request')->except('page'))->links() }}

@stop
