@extends('panel::layouts.master')

@section('content')

    <h2>Payments Directory</h2>

    <br/>

    @include('alert::bootstrap')

<table class="table table-sm table-striped">
  <thead class="thead- border-0">
    <tr>
      <th scope="col" class="w-20 border-0">Payment</th>
      <th scope="col"  class="w-10 border-0">Amount</th>
      <th scope="col"  class="w-20 border-0">Time</th>
      <th scope="col"  class="w-20 border-0">Business</th>
      <th scope="col"  class="w-20 border-0">User</th>
      <th scope="col"  class="w-10 border-0">Status</th>
    </tr>
  </thead>
  <tbody>
      @foreach($payments as $item)
      <tr>
        <td>
          @switch($item->payment_type)
            @case(1)
              Individual business
              @break
            @case(2)
              Enterprise Business
              @break
            @case(3)
              Limited Liability
              @break
          @endswitch
        </td>
        <td>
         {{ $item->amount}}
        </td>
        <td>
         {{ $item->updated_at}}
        </td>
        <td>
          @if($item->directory != null)
          {{ $item->directory->name }}
          @endif
        </td>
        <td>
          {{ $item->user->email}}
        </td>
        <td>
          {{ $item->status}}
        </td>
      </tr>
      @endforeach

  </tbody>
</table>

<?php echo $payments->render(); ?>
@stop
