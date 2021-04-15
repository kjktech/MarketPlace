@extends('panel::layouts.master-users')

@section('content')

    <h2>Newsletter Subscribers</h2>
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
            <th scope="col" class="w-25 border-0">Name</th>
            <th scope="col" class="w-25 border-0">Email</th>
            <th scope="col" class="w-25 border-0">Date registered</th>
            <th scope="col"  class="w-25 border-0">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($subscribers as $item)
            <tr>
                <td>
                    {{str_limit($item->name, 40)}}
                    {!!  ($item->banned_at)?'<i class="text-muted">(banned)</i>':'' !!}
                </td>
                <td>{{$item->email}}</td>
                <td>{{$item->created_at}}</td>
                <td>
                    @if($item->subscribed)
                      Subscribed
                    @else
                      Not subscribed
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    {{ $subscribers->appends(app('request')->except('page'))->links() }}

@stop
