@extends('panel::layouts.master-market')

@section('content')

    <h2>Orders</h2>
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
          <!--
            <th scope="col" class="border-0">#</th>
            <th scope="col" class="border-0">Listing</th>
            <th scope="col" class="border-0">Seller</th>
            <th scope="col" class="border-0">Buyer</th>
            <th scope="col" class="border-0">Amount</th>
            <th scope="col"  class="border-0"></th>
          -->
            <th scope="col">{{ __('Reference') }}</th>
            <th scope="col">{{ __('Email') }}</th>
            <th scope="col" class="d-none d-sm-block">{{ __('Date Created') }}</th>
            <th scope="col">{{ __('Payment status') }}</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $item)
            <tr>
              {{--
                <td>{{ $item->hash }}</td>
                <td>{{str_limit($item->listing->title, 40)}}</td>
                <td>{{$item->listing->user->email}}</td>
                <td>{{$item->user->email}}</td>
                <td>{{$item->amount}} {{$item->currency}}</td>
                <td>{{$item->created_at->toFormattedDateString()}}</td>
                <td>
                    <a href="{{route('panel.orders.show', $item)}}" class="text-muted float-right"><i class="fa fa-eye"></i></a>
                </td>
              --}}

                <td><a href="#" class="text-dark">{{ $item->reference }}</a></td>
                <td><a href="#">{{ $item->email }}</a></td>
                <td class="d-none d-sm-block">{{ $item->created_at->toFormattedDateString() }}</td>
                <td>
                  @if ($item->status == "paid")
                    Paid
                  @else
                    Pending
                  @endif
                </td>
                <td class="text-right"><a href="{{ route('checkoutb')}}?id={{$item->id}}&reference={{$item->reference}}">{{ __('View') }}&nbsp;<i class="mdi mdi-chevron-double-right"></i></a></td>
            </tr>
        @endforeach

        </tbody>
    </table>

    {{ $orders->appends(app('request')->except('page'))->links() }}

@stop
