<div class="form-group checkbox" style="max-height: 160px">
@foreach($options['modeldata'] as $item)
<div class="checkbox">
  @if(in_array($item->id, $options['selecteddata']))
    <label><input checked type="checkbox" value="{{ $item->id }}" name="brands[]">{{ $item->name }}</label>
   @else
    <label><input type="checkbox" value="{{ $item->id }}" name="brands[]">{{ $item->name }}</label>
   @endif
</div>
@endforeach
</div>
