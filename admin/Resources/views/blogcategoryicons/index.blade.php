@extends('panel::layouts.master')

@section('content')

    <h2>Blog Category Icons </h2>

    <br />
    @include('alert::bootstrap')
<form id="icon-form" method="post" action="{{ action('Admin\BlogCategoryIconsController@update') }}">
  @csrf
<table class="table table-sm table-striped table-hover">

                   <thead>
                       <tr>
                           <th class="w-25">Name</th>
                           <th class="w-75">Icons</th>
                       </tr>
                   </thead>
                   <tbody>

                    <input id="hidden-input" name="input-val" type="hidden" />
                    @foreach($categories as $category)
                    <tr>
                     <td>
                     {{$category['name'] }}:
                     </td>
                     <td>
                      <input class="input-class" data-id={{$category['blog_etc_category_id']}} type="text" value="{{$category['icon']}}" />
                     </td>
                    </tr>
                    @endforeach
                    <tr>
                      <td>

                      </td>
                      <td>
                        <br/>

                      </td>
                    </tr>

                   </tbody>
                 </table>
                 <button id="icon-form-btn" class="btn btn-primary">Submit</button>
</form>
  <script>
    $(document).ready(function(){

       $("#icon-form-btn").on('click', function(e){
        var input_vals = '';
         e.preventDefault();
         $('.input-class').each(function(index){

           var field_data = $.trim($(this).val());
           var field_id = $(this).data('id');
           if(field_data != ""){
            if(input_vals == ''){
              input_vals = field_id + "_" + field_data;
            }else{
              input_vals = input_vals + "," + field_id + "_" + field_data;
            }
          }
         });
         $("#hidden-input").val(input_vals);
         console.log(input_vals);
         $("#icon-form").submit();
       })
    });
  </script>
  @stop
