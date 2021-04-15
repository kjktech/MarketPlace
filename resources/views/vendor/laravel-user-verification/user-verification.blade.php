@extends('layouts.verifymail')
@section('content')
<div class="container verifymsg">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>{!! trans('laravel-user-verification::user-verification.verification_error_header') !!}</h1></div>
                <br>
                <div class="panel-body">
                    <span class="help-block">
                        <strong><h3>{!! trans('laravel-user-verification::user-verification.verification_error_message') !!}</h3></strong>
                    </span>
                    <br>
                    <div class="">
                      <a href="{{url('/')}}" class="btn">
                          {!! trans('laravel-user-verification::user-verification.verification_error_back_button') !!}
                      </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
