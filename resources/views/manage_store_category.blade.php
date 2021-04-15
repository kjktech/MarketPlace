@extends('layouts.admin') @section('content')
<div class="right_col">
    <h2>Categories</h2>

    <br />

    <div class="row">
    <div class="col-lg-4">
      Add New Category
      <form class="form-horizontal" method="post">
        @csrf
         <div class="row">
            <div class="col-lg-12">
              <label>Name</label>
              <div class="form-group">
                 <input required class="form-control" name="name" type="text" />
                 The name is how it appears on your site.
              </div>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-12">
              <label>Parent Category</label>
              <div class="form-group">
                 <select class="form-control" name="category">
                   <option value="0">None</option>
                   @foreach($categories as $category )
                      <option value="{{$category['id']}}">{!! str_repeat("&mdash;", $category['depth']) !!} {{$category['name']}}</option>
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
    <div class="col-lg-8">
    <table class="table table-sm table-striped table-hover">

        <thead>
            <tr>
                <th class="w-75">Name</th>
                <th class="w-25"></th>
            </tr>
        </thead>
        <tbody>

            @foreach($categories as $category )
            <tr>
                <td>{!! str_repeat("&mdash;", $category['depth']) !!} {{$category['name']}}</td>
                <td>
                    <a href="#" ic-target="#main" ic-select-from-response="#main" ic-delete-from="" ic-confirm="Are you sure?" class="text-muted float-right ml-2"><i class="fa fa-remove"></i></a>
                    <a href="/panel/categories/<?= $category['id'] ?>/edit" class=" text-muted float-right"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
  </div>
  </div>
</div>
@stop
