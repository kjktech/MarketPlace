<?php

 namespace App\Http\Controllers\Admin;

 use Illuminate\Http\Request;
 use Illuminate\Http\Response;
 use Illuminate\Routing\Controller;

 use App\Models\DirectoryPayment;

 class PaymentController extends Controller{
   /**
    * Display a listing of the resource.
    * @return Response
    */
   public function directory(Request $request)
   {
       $payments = DirectoryPayment::orderBy('created_at', 'DESC')->paginate(50);
       //dd($payments[1]->directory->name);
       return view('panel::payments.directory', compact('payments'));
   }
 }
