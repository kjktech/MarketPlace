@extends('panel::layouts.master-market')

@section('content')
<h2>Categories <a href="{{ route('panel.categories.create')}}" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add new</a></h2>
 <br />
 @include('alert::bootstrap')
 <table class="table table-sm table-striped table-hover">
  <thead>
    <tr>
      <th class="w-50">Name</th>
      <th class="w-25"></th>
      <th class="w-25"></th>
    </tr>
    </thead>
      <tbody>
        @foreach($categories as $category )
          <tr>
            <td>{!!  str_repeat("&mdash;", $category['depth']) !!} {{$category['name']}}</td>
            <td>
              @if( $category['parent_id'] == 0)
                <a href="{{ route('panel.categories.variant', $category['id']) }}"> Manage Variant</a>
              @endif
            </td>
            <td>
                <a href="#" ic-target="#main" ic-select-from-response="#main" ic-delete-from="{{ route('panel.categories.destroy', $category['id']) }}" ic-confirm="Are you sure?" class="text-muted float-right edit-icon-space-left"><i class="fa fa-remove"></i></a>
                <a href="{{ route('panel.categories.edit', $category['id']) }}" class=" text-muted float-right"><i class="fa fa-pencil"></i></a>
            </td>
         </tr>
        @endforeach
      </tbody>
  </table>
@stop
