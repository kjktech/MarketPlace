<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Store;
use App\Models\Directory;
use App\Models\DirectoryPayment;
use App\Models\StorePayment;
use App\Models\State;
use App\Models\City;
use App\Models\Bank;
use App\Models\StoreSetup;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreLedger;
use App\Models\User;
use App\Models\Category;

use Illuminate\Support\Facades\Validator;
use Paystack;

use Kris\LaravelFormBuilder\FormBuilder;

class StoresController extends Controller
{
    public function teststore(){
      $categories_nested = Category::orderBy('order', 'ASC')->nested()->get();
      $flatten_home = flattenhome($categories_nested, 0, 4);
      var_export($flatten_home);
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $stores = new Store();
        if($request->get('q')) {
            $stores = $stores->search($request->get('q'));
        }
        if(auth()->user()->can('basic approval')){
          $stores = $stores->orderBy('created_at', 'desc');
        }else{
          $stores = $stores->where('setup_id', auth()->user()->id)->orderBy('created_at', 'desc');
        }
        $data['stores'] = $stores->paginate(50);

        return view('panel::stores.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
      $data = [];
      if($request->has('directory_id')){
        $directory = Directory::find($request->get('directory_id'));
        if($directory){
          // restrict dir to one store
          if(count($directory->stores) > 0){
            return redirect(route('panel.stores.edit', $directory->stores[0]));
          }
        }else{
          return redirect(route('panel.'));
        }

      }else{
        return redirect(route('panel.'));
      }

      if(!auth()->user()->can('edit listing')){
        return redirect(route('panel.'));
      }

      $data['directory_id'] = $directory->id;
      return view('panel::stores.create', $data);
     }

     /**
      * Store a newly created resource in storage.
      * @param  Request $request
      * @return Response
      */
     public function store(Request $request)
     {
       $params = $request->all();
       #return response('OK', 200)->header('X-IC-Redirect', '/create/r4W0J7ObQJ/edit#images_section');
       $validator = Validator::make($request->all(), [
           'name' => 'required|min:5|max:255',
           'description' => 'required|min:5',
       ]);

       if ($validator->fails()) {
           return redirect(route('panel.stores.create', ["directory_id" => $request->get('directory_id')]))
                       ->withErrors($validator)
                       ->withInput();
       }
       $directory = Directory::find($request->get('directory_id'));
       $params['directory_id'] = $request->get('directory_id');
       // user id should be tied to directory
       $params['user_id'] = $directory->user->id;//auth()->user()->id;
       $params['name'] = $request->get('name');
       $params['description'] = $request->get('description');
       $params['setup_id'] = auth()->user()->id;

       $params['slug'] =  str_slug($request->get('name'));

       $store = Store::create($params);

       $payment_obj = DirectoryPayment::where('directory_id', $request->get('directory_id'))->first();
       $payment_type = 1;
       if($payment_obj){
         $payment_type = $payment_obj->payment_type;
       }

       switch ($payment_type) {
         case 1:
           // code...
           $dir_pay = new StorePayment();
           $dir_pay->user_id = auth()->user()->id;
           $dir_pay->paystack_reference = Paystack::genTranxRef();
           $dir_pay->amount = 10000 - 1000;
           $dir_pay->directory_payment_type = 1;
           $dir_pay->store_id = $store->id;
           $dir_pay->directory_id = $directory->id;
           $dir_pay->save();
           break;
         case 2:
             // code...
             $dir_pay = new StorePayment();
             $dir_pay->user_id = auth()->user()->id;
             $dir_pay->paystack_reference = Paystack::genTranxRef();
             $dir_pay->amount = 10000 - 2500;
             $dir_pay->directory_payment_type = 2;
             $dir_pay->store_id = $store->id;
             $dir_pay->directory_id = $directory->id;
             $dir_pay->save();
           break;
         case 3:
               // code...
           $dir_pay = new StorePayment();
           $dir_pay->user_id = auth()->user()->id;
           $dir_pay->paystack_reference = Paystack::genTranxRef();
           $dir_pay->amount = 10000 - 5000;
           $dir_pay->directory_payment_type = 3;
           $dir_pay->store_id = $store->id;
           $dir_pay->directory_id = $directory->id;
           $dir_pay->save();
           break;
         default:
           // code...
           break;
       }

       //redirect to success page
       return redirect(route('panel.stores.edit', $store));
     }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('panel::show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setup($id)
    {
        $setup_obj = StoreSetup::where('store_id', $id)->first();
        $banks_arr = Bank::orderBy('name', 'ASC')->get();
        $data = [];
        $data['banks'] = $banks_arr;
        $store = Store::findOrFail($id);
        $data['store'] = $store;
        $data['store_id'] = $id;
        $data['setup'] = null;
        if($setup_obj){
          $data['setup'] = $setup_obj;
        }
        return view('panel::stores.store_get_started', $data);

    }


      /**
       * Show the form for editing the specified resource.
       * @return Response
       */
      public function edit($store)
      {
        //Cities array
        $cities_array = array("Abia"=>"Abia","Adamawa"=>"Adamawa",
        "Akwa Ibom"=>"Akwa Ibom","Anambra"=>"Anambra","Bauchi"=>"Bauchi",
        "Bayelsa"=>"Bayelsa","Benue"=>"Benue","Borno"=>"Borno","Cross River"=>"Cross River",
        "Delta"=>"Delta","Ebonyi"=>"Ebonyi",
        "Edo"=>"Edo","Ekiti"=>"Ekiti","Enugu"=>"Enugu",
        "Gombe"=>"Gombe","Imo"=>"Imo","Jigawa"=>"Jigawa",
        "Kaduna"=>"Kaduna","Kano"=>"Kano","Katsina"=>"Katsina",
        "Kebbi"=>"Kebbi","Kogi"=>"Kogi","Kwara"=>"Kwara",
        "Lagos"=>"Lagos","Nasarawa"=>"Nasarawa","Niger"=>"Niger",
        "Ogun"=>"Ogun","Ondo"=>"Ondo","Osun"=>"Osun","Oyo"=>"Oyo",
        "Plateau"=>"Plateau","Rivers"=>"Rivers","Sokoto"=>"Sokoto","Taraba"=>"Taraba",
        "Yobe"=>"Yobe","Zamfara"=>"Zamfara","FCT"=>"FCT");
          //return redirect($directory->url);
          $data = [];
          if($store->city){
            $data['city'] = $store->city;
          }else{
            $data['city'] = "Abia";
          }
          $data['store'] = $store;
          $data['city_array'] = $cities_array;

          $lga_array = [];
          if($store->city){
            $state_obj = State::where('name', 'LIKE', $store->city)->first();
            $city_obj = City::where('state_id', $state_obj->id)->get();
            foreach ($city_obj as $key => $value) {
              // code...
              $lga_array[$value["id"]] = $value["name"];
            }
            $data['lga_array'] = $lga_array;
          }else{
            $state_obj = State::where('name', 'LIKE', 'Abia')->first();
            $city_obj = City::where('state_id', $state_obj->id)->get();
            foreach ($city_obj as $key => $value) {
              // code...
              $lga_array[$value["id"]] = $value["name"];
            }
            $data['lga_array'] = $lga_array;
           }

          //return redirect($store->url);
          return view('panel::stores.edit', $data);
      }



    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($store, Request $request)
    {
      $params = $request->all();

      //$filters = Filter::orderBy('position', 'ASC')->where('is_hidden', 0)->where('is_default', 0)->get();
      if($request->input('tags_string')) {
          $store->tags = explode(",", $request->input('tags_string'));
          $store->tags_string = $request->input('tags_string');
      }

      $store->fill($request->only(['name', 'description', 'city', 'city_id', 'country']));
      if($request->input('photos') && is_array($request->input('photos'))) {
          $store->photos = $request->input('photos');
      }


      $store->save();

      alert()->success( __('Successfully saved.') );
      return back();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($store)
    {
        $store->delete();

        alert()->success('Successfully deleted');
        return redirect()->route('panel.stores.index');
    }

    //
    public  function  transaction(Request $request, $store_id){
        $user = auth()->user();
        $toDate = date("Y-m-d H:m:s");
        $fromDate = date('Y-m') . "-01 00:00:00";
        $store = Store::findOrFail($store_id);
        $store_id = $store->id;//\Session::get('store_id');
        $orders = OrderItem::select('order_items.*')->where('order_items.store_id', $store_id)/*->whereDate('created_at', ">=", $fromDate)*/->whereDate('order_items.created_at', "<=", $toDate)
        ->join('orders as orders_one', 'orders_one.id', "order_items.order_id")
        ->where('orders_one.status', '=', 'paid')
        ->get();
        $orders_payout = OrderItem::select('order_items.*', \DB::raw('SUM(order_items.quantity * order_items.price) as total_pay'))->where('order_items.store_id', $store_id)/*->whereDate('created_at', ">=", $fromDate)*/->whereDate('order_items.created_at', "<=", $toDate)
        ->join('orders as orders_one', 'orders_one.id', "order_items.order_id")
        ->where('orders_one.status', '=', 'paid')
        ->get();
        if(count($orders_payout) > 0){
          $orders_payout = $orders_payout[0]['total_pay'];
        }else{
          $orders_payout = "0.00";
        }
        return view('panel::stores.transaction', compact('orders', 'orders_payout'));

    }

    public function ledgerindex($store, FormBuilder $formBuilder)
    {
      //dd( $user->getRoleNames()->first() );
      $this->genLeder($store);
      $storeLedger = $store->ledger;
      $store = $store;
      $form = $formBuilder->create('Modules\Panel\Forms\StoreLegderForm', [
          'method' => 'POST',
          'url' => route('panel.storeledger.update', $store),
          'model' => $storeLedger
      ]);
      return view('panel::stores.ledger', compact('form', 'store'));
    }
    public function ledgerupdate($store, Request $request)
    {
      $ledger = StoreLedger::where('store_id', $store->id)->first();
      if(!$ledger){
        $ledger = $this->newLeder($store);
      }
      if($request->has('officer_id')){
         $officer = User::find($request->get('officer_id'));
         if($officer){
           if($ledger){
             if((int)$ledger->officer_id != (int)$request->get('officer_id')){
               $ledger->officer_id = $request->get('officer_id');
               $ledger->save();
             }
           }
         }
      }
      alert()->success('Successfully deleted');
      return redirect()->route('panel.stores.edit', $store);
    }

    private function genLeder(Store $store){
      $storeLedger = StoreLedger::where('store_id', $store->id)->first();
      if(!$storeLedger){
        $storeLedger = new StoreLedger();
        $storeLedger->store_id = $store->id;
        $storeLedger->owner_id = $store->directory->user->id;
        $storeLedger->save();
      }
      if($store->user_id != $store->directory->user->id){
        $store->user_id = $store->directory->user->id;
        $store->save();
      }
    }

    private function newLeder(Store $store){
        $storeLedger = new StoreLedger();
        $storeLedger->store_id = $store->id;
        $storeLedger->owner_id = $store->directory->user->id;
        $storeLedger->save();
        return $storeLedger;
    }
}
