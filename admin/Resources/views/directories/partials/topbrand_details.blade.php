<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Topbrand details") }}</h6>
    <div class="card-body">
    <div class="form-group">
        <label>{{ __("About us") }}</label>
        <div id="about" style="height: 200px">
            {!! $directory->about !!}
        </div>
        {!! Form::hidden('about', null, ['class' => 'form-control']) !!}
     </div>
    </div>
</div>
<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Topbrand Sevices") }}</h6>
    <div class="card-body">
    <div class="form-group">
        <label></label>
        <div id="services" style="height: 200px">
            {!!  $directory->services !!}
        </div>
        {!! Form::hidden('services', null, ['class' => 'form-control']) !!}
     </div>
    </div>
</div>



        <div class="card mb-4">
            <h6 class="card-header bg-white">{{ __("Teams") }}</h6>
            <div class="card-body">
               <a data-toggle="modal" data-target="#teamsModal" href="" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add new</a><br/>
              <table>
               @foreach($directory->teams as $team)
                 <tr>
                  <td>
                   <img height="60" src="{{ $team->photo }}?timestamp="/>
                  </td>
                  <td>
                   <a class="team-edit" data-id="{{ $team->id }}" data-name="{{ $team->name }}" data-position="{{ $team->position }}">Edit</a><br/>
                   {{ $team->name }}<br/>
                   {{ $team->position }}
                   </td>
                 </tr>
               @endforeach
               </table>
           </div>
        </div>

    <div data-focus="false" id="teamsModal" class="modal" tabindex="-1" role="dialog">
     <div class="modal-dialog" role="document">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add team</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
       </div>
       <div class="modal-body">

        <div class="form-group">
        <label for="name">Name</label>
         <input name="teamname" type="text" class="form-control" id="inputName" aria-describedby="nameHelp" placeholder="Enter name"/>
         <input value="0" name="teamid" type="hidden" id="inputId">
       </div>
      <div class="form-group">
      <label for="inputPosition">Position</label>
      <input name="teamposition" type="text" class="form-control" id="inputPosition" placeholder="Position">
      </div>
     <div class="form-group">
     <label for="inputFile">Input File</label>
     <input name="teamimage" type="file" class="form-control-file" id="inputFile">
      </div>
        <button onclick="form_submit()" id="save-btn" type="submit" class="btn btn-primary">Save team</button>

       </div>
       <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
     </div>
     </div>
    </div>

  <script>



  </script>
<script>
    if($('#about').length) {
        var quills = new Quill('#about', {
            placeholder: '',
            theme: 'snow'  // or 'bubble'
        });
        quills.on('editor-change', function (eventName, args) {
            $('input[name=about]').val(quills.root.innerHTML);
        });
    }
    if($('#services').length) {
        var quills_ser = new Quill('#services', {
            placeholder: '',
            theme: 'snow'  // or 'bubble'
        });
        quills_ser.on('editor-change', function (eventName, args) {
            $('input[name=services]').val(quills_ser.root.innerHTML);
        });
    }
    $(document).ready(function(){
    $('.team-edit').on('click', function(e){
     $('.modal-title').html('Edit team');
      var name = $(this).data('name');
      var position = $(this).data('position');
      var id = $(this).data('id');
      $("#inputId").val(id);
      $("#inputPosition").val(position);
      $("#inputName").val(name);
      $("#teamsModal").modal(
       {
        backdrop: false
       }
      );
    });
    $('#teamsModal').on('hidden.bs.modal', function (e) {
     // do something...
     $("#inputId").val(0);
    })
  });
</script>
