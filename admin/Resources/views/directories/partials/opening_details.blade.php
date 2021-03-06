<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <style>
    .input-group {
    position: relative;
    display: table;
    border-collapse: separate;
}
.input-group {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: stretch;
    -ms-flex-align: stretch;
    align-items: stretch;
    width: 100%;
}
.input-group-addon, .input-group-btn, .input-group .form-control {
    display: table-cell;
}
    .input-group-addon {
   padding: 6px 12px;
   font-size: 14px;
   font-weight: normal;
   line-height: 1;
   color: #555;
   text-align: center;
   background-color: #eee;
   border: 1px solid #ccc;
       border-right-color: rgb(204, 204, 204);
       border-right-style: solid;
       border-right-width: 1px;
   border-radius: 4px;
       border-top-right-radius: 4px;
       border-bottom-right-radius: 4px;
}
.input-group .form-control:last-child, .input-group-addon:last-child, .input-group-btn:last-child > .btn, .input-group-btn:last-child > .dropdown-toggle, .input-group-btn:first-child > .btn:not(:first-child) {
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
}
.input-group-addon:first-child {
    border-right: 0;
}
.input-group .form-control:first-child, .input-group-addon:first-child, .input-group-btn:first-child > .btn, .input-group-btn:first-child > .dropdown-toggle, .input-group-btn:last-child > .btn:not(:last-child):not(.dropdown-toggle) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.428571429;
    color: #555;
    vertical-align: middle;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
    </style>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Opening details") }}</h6>
    <div class="card-body">
      {!! Form::hidden('opening_hidden', $openings, ['class' => 'form-control', 'id' => 'opening_hidden']) !!}
      <div id="hours-container" style="display: flex; flex-direction: row;">

      </div>
   </div>
</div>
<script>
  $(document).ready(function(){
    var opening_arr_str = '{!! $openings !!}';
    var time_init =  JSON.parse(opening_arr_str);
    var businessHoursManager = $("#hours-container").businessHours({
      postInit:function(){
              $('.operationTimeFrom, .operationTimeTill').timepicker({
                  'timeFormat': 'H:i',
                  'step': 15
                  });
              },
             operationTime: time_init,
             dayTmpl:'<div class="dayContainer" style="width: 80px;">' +
                        '<div data-original-title="" class="colorBox"><input type="checkbox" class="invisible operationState"></div>' +
                        '<div class="weekday"></div>' +
                        '<div class="operationDayTimeContainer">' +
                        '<div class="operationTime input-group"><span class="input-group-addon"><i class="fa fa-sun-o"></i></span><input type="text" name="startTime" class="mini-time form-control operationTimeFrom" value=""></div>' +
                        '<div class="operationTime input-group"><span class="input-group-addon"><i class="fa fa-moon-o"></i></span><input type="text" name="endTime" class="mini-time form-control operationTimeTill" value=""></div>' +
                        '</div></div>'
           });
      $(".dayContainer").on('change', function(){
        $("#opening_hidden").val(JSON.stringify(businessHoursManager.serialize()));
        //alert(JSON.stringify(businessHoursManager.serialize()));
      });
      $(".form").on('submit', function(){
        $("#opening_hidden").val(JSON.stringify(businessHoursManager.serialize()));
        //alert(JSON.stringify(businessHoursManager.serialize()));
      });
    });
</script>
