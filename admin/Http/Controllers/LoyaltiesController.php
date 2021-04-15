<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Loyalty;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Models\Directory;

use Grimzy\LaravelMysqlSpatial\Types\Point;

class LoyaltiesController extends Controller
{
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index(Request $request)
  {
      $loyalties = new Loyalty();
      if($request->get('q')) {
          $loyalties = $loyalties->search($request->get('q'));
      }
      $loyalties = $loyalties->orderBy('created_at', 'desc');
      $data['loyalties'] = $loyalties->paginate(10);

      //Dirty script
      /*
      if(env('RUN_ENV_SITE', "undefined") == "test"){
        $all_directory = Directory::all();
        foreach($all_directory as $directory){
          $directory->photo=null;
          if($directory->lat && $directory->lng) {
              $point= new Point($directory->lat, $directory->lng);
              $directory->location = \DB::raw("GeomFromText('POINT(".$point->getLng()." ".$point->getLat().")')");
          }
          $directory->save();
        }
      }
      */
      // end script

      return view('panel::loyalties.index', $data);
  }

  /**
   * Show the form for creating a new resource.
   * @return Response
   */
  public function create(FormBuilder $formBuilder)
  {
      $form = $formBuilder->create('Modules\Panel\Forms\LoyaltyForm', [
         'method' => 'POST',
         'url' => route('panel.loyalties.store')
      ]);
      return view('panel::loyalties.create', compact('form'));
  }

  /**
   * Store a newly created resource in storage.
   * @param  Request $request
   * @return Response
   */
  public function store(Request $request)
  {
      //$data['slug'] = str_slug($request->get('name'));
      $loyalty = new Loyalty();
      $loyalty->fill($request->all());
      $loyalty->save();
      alert()->success('Successfully saved');
        return redirect()->route('panel.loyalties.index');
  }

  public function edit($id, FormBuilder $formBuilder)
  {
      $loyalty = Loyalty::findOrFail($id);

      $form = $formBuilder->create('Modules\Panel\Forms\LoyaltyForm', [
          'method' => 'PUT',
          'url' => route('panel.loyalties.update', $id),
          'model' => $loyalty
      ]);
      return view('panel::loyalties.create', compact('form'));
  }

  /**
   * Update the specified resource in storage.
   * @param  Request $request
   * @return Response
   */
  public function update(Request $request, $id)
  {
      $loyalty = Loyalty::findOrFail($id);
      $loyalty->fill($request->all());
      $loyalty->save();
      alert()->success('Successfully saved');
         return redirect()->route('panel.loyalties.index');
  }

}
