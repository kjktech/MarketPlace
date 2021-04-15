@extends('layouts.admin') @section('content')
<div class="right_col">
    <h2>Edit Category</h2>

    <br />

    <div class="row">
    <div class="col-lg-6">
      Add New Category
      <form class="form-horizontal" method="post">
        @csrf
         <div class="row">
            <div class="col-lg-12">
              <label>Name</label>
              <div class="form-group">
                 <input required class="form-control" value="{{$dircategory->name}}" name="name" type="text" />
                 The name is how it appears on your site.
              </div>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-12">
              <label>Parent Category</label>
              <div class="form-group">
                 <select class="form-control" name="category">
                   @if ("0" == $dircategory->parent_id)
                     <option selected=selected value="0">None</option>
                   @else
                     <option value="0">None</option>
                   @endif

                    @foreach($categories as $category )
                     @if ($category["id"] != $dircategory->id)
                      @if ($category["id"] == $dircategory->parent_id)
                      <option selected=selected value="{{$category['id']}}">{!! str_repeat("&mdash;", $category['depth']) !!} {{$category['name']}}</option>
                     @else
                      <option value="{{$category['id']}}">{!! str_repeat("&mdash;", $category['depth']) !!} {{$category['name']}}</option>
                     @endif
                     @endif
                   @endforeach
                 </select>
              </div>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-12">
              <button class="btn btn-primary">Add New Category</button>
            </div>
         </div>
      </form>
    </div>
  </div>
</div>
@stop
