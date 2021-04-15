<?php

namespace App\Exceptions;

use Exception;
use Request;
use Illuminate\Auth\AuthenticationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($exception instanceof TokenMismatchException){
            // Redirect to a form. Here is an example of how I handle mine
            //return redirect($request->fullUrl())->with('csrf_error',"Oops! Seems you couldn't submit form for a long time. Please try again.");
            return back();
        }
        if ($exception instanceof UnauthorizedException) {
         $request->session()->flash($exception->getMessage(), 'danger');
         //return redirect('/');
         return back();
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
            return $request->expectsJson()
                    ? response()->json(['message' => 'Unauthenticated.'], 401)
                    : redirect()->guest(route('home'));
    }
}
