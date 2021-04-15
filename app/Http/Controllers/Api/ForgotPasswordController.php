<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
class ForgotPasswordController extends Controller
{
	use SendsPasswordResetEmails;
	/**
	 * Create a new controller instance.
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}
	public function __invoke(Request $request)
	{
		$this->validateEmail($request);
		// We will send the password reset link to this user. Once we have attempted
		// to send the link, we will examine the response then see the message we
		// need to show to the user. Finally, we'll send out a proper response.
		$response = $this->broker()->sendResetLink(
			$request->only('email')
		);
		/*
		return $response == Password::RESET_LINK_SENT
			? response()->json(['msg' => 'Reset link sent to your email.', 'status' => true], 201)
			: response()->json(['msg' => 'Unable to send reset link', 'status' => false], 401);
    */
			switch ($response)
	            {
	                case Password::RESET_LINK_SENT:
	                   return response()->json([
	                       'status'=> true,
	                       'msg'=>'A password link has been sent to your email address'
	                   ], 201);

	                case Password::INVALID_USER:
	                   return response()->json([
	                       'status'=> false,
	                       'msg'=>"We can't find a user with that email address"
	                   ], 401);

									default:
									  return response()->json([
											'status'=> false,
											'msg'=>"Unable to send email link"
										], 401);
	      }
	}
}
