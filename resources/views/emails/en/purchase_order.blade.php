@component('mail::message')
# Hello {{ $order->last_name }}
<br>
Thank you for shopping with us, we are already processing your order shippment.
<br>
<table style="width: 100%;">
  <thead>
      <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
          <th class="column-spacer"></th>
      </tr>
  </thead>
  <tbody>
    @foreach($order->items as $item)
      <tr class="spaceTop">
          <td>
           <a style="font-size: 20px;" href="#">{{ $item->listing->title }}</a>
           <br>
           <img src="{{ cart_content($item->listing_id) }}" class="item__img" style="width: 120px;" alt="item picture">
          </td>
         <td>

           {{ $item->quantity }}
         </td>
         <td>{{ $item->price }}</td>
         <td class=""></td>
      </tr>
     @endforeach
      <tr class="spaceTop">
        <td class="table-image"></td>
        <td class="small-caps table-bg" style="font-weight: 600; text-align: right;">Subtotal:</td>
        <td>{{ $order->amount }}</td>
        <td></td>
     </tr>
    <tr>
         <td></td>
        <td class="small-caps table-bg" style="font-weight: 600; text-align: right;">Tax:</td>
        <td></td>
        <td></td>
     </tr>

     <tr class="border-bottom">
        <td class="table-image"></td>
        <td class="small-caps table-bg" style="font-weight: 600; text-align: right;">Total:</td>
        <td class="table-bg">{{ $order->amount }}</td>
        <td></td>

     </tr>
  </tbody>
</table>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
