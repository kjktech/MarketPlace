@extends('panel::layouts.master')

@section('content')

    <h2>Variants <a href="{{ route('panel.variants.create')}}" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add new</a></h2>

    <br />
    @include('alert::bootstrap')

<table class="table table-sm table-striped table-hover">

                   <thead>
                       <tr>
                           <th class="w-75">Name</th>
                           <th class="w-25"></th>
                       </tr>
                   </thead>
                   <tbody>

                       @foreach($variants as $variant )
                       <tr>
                           <td>{{ $variant->attribute }}</td>
                           <td>
                               <a href="{{ route('panel.variants.edit', $variant) }}" class=" text-muted float-right"><i class="fa fa-pencil"></i></a>
                           </td>
                       </tr>
                       @endforeach
                   </tbody>
                 </table>


@stop
