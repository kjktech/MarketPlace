@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            {{ $error }}<br/>
        @endforeach
    </div>
@else
    @if(session()->has('alert.message'))
    <div class="alert alert-{{ session()->get('alert.style') }} alert-dismissible fade in show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button><!-- /close -->

        {{ session()->get('alert.message') }}
    </div><!-- /alert -->
    @endif

@endif
