@extends('panel::layouts.master-market')

@section('content')
<div class="container">
    <a href="#" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            <h2  class="mt-xxs">{{ $store->name}} (Ownership Ledger)</h2>
        </div>
        <div class="col-sm-12">
          <p>
            Current owner: {{ $store->user->name}}: {{ $store->user->email}}
          </p>
        </div>

    </div>

    <div class="row">

        <div class="col-sm-12">

          <div class="panel panel-default">
              <div class="panel-body">
                  @include('alert::bootstrap')
                  {!! form_start($form)  !!}
                  {!! form_rest($form)   !!}
                  {!! form_end($form, false)   !!}
</div>
</div>
</div>
<script>
 $(document).ready(function(){
   /*
   var engine = new Bloodhound({
    remote: {
        url: "{{ route('panel.search.user')}}?type=reg&q=%QUERY%",
        wildcard: '%QUERY%'
    },
    datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
    queryTokenizer: Bloodhound.tokenizers.whitespace
  });
  */
  var engine2 = new Bloodhound({
   remote: {
       url: "{{ route('panel.search.user')}}?q=%QUERY%",
       wildcard: '%QUERY%'
   },
   datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
   queryTokenizer: Bloodhound.tokenizers.whitespace
 });
 /*
 var typeobject = $(".search-owner").typeahead({
        hint: true,
        highlight: true,
        minLength: 1,
    }, {
        source: engine.ttAdapter(),
        displayKey: 'email',

        // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
        name: 'usersList',
        // the key from the array we want to display (name,id,email,etc...)
        templates: {
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function (data) {
                return '<span class="list-group-item">' + data.name + " : " + data.email + '</span>'
            }
        }
    });
    typeobject.bind('typeahead:select', function(ev, suggestion) {
     console.log('Selection: ' + suggestion.name);
     $("#owner_id").val(suggestion.id);
    });
   */
    var typeobject2 = $(".search-officer").typeahead({
           hint: true,
           highlight: true,
           minLength: 1,
       }, {
           source: engine2.ttAdapter(),
           displayKey: 'email',

           // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
           name: 'usersList',
           // the key from the array we want to display (name,id,email,etc...)
           templates: {
               empty: [
                   '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
               ],
               header: [
                   '<div class="list-group search-results-dropdown">'
               ],
               suggestion: function (data) {
                   return '<span class="list-group-item">' + data.name + " : " + data.email + '</span>'
               }
           }
       });
       typeobject2.bind('typeahead:select', function(ev, suggestion) {
        //console.log('Selection: ' + suggestion.name);
        $("#officer_id").val(suggestion.id);
       });
});
</script>
@endsection
