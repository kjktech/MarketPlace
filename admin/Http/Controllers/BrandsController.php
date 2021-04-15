<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Brand;
use Kris\LaravelFormBuilder\FormBuilder;
use Image;
use Storage;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $brands = Brand::orderBy('name', 'DESC')->get();
        $data['brands'] = $brands;
        return view('panel::brands.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Modules\Panel\Forms\BrandForm', [
           'method' => 'POST',
           'url' => route('panel.brands.store')
        ]);
        return view('panel::brands.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = [];
        $data['name'] = $request->get('name');
        $data['slug'] = str_slug($request->get('name'));
        $brand = Brand::create( $data );

	    	alert()->success('Successfully saved');
          return redirect()->route('panel.brands.index');
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
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $brand = Brand::findOrFail($id);

        $form = $formBuilder->create('Modules\Panel\Forms\BrandForm', [
            'method' => 'PUT',
            'enctype' => 'multipart/form-data',
            'url' => route('panel.brands.update', $id),
            'model' => $brand
        ]);
        return view('panel::brands.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        if($request->file('image')) {
          //dd('Test');
            $image = Image::make($request->file('image'))
                    ->resize(91, null, function ($constraint) {
                        $constraint->aspectRatio();
                        //$constraint->upsize();
                    });
            Storage::cloud()->put('brands/'.$brand->path, (string) $image->encode());
            $brand->logo = Storage::cloud()->url("brands/".$brand->path);
            $brand->save();
        }
        $brand->fill($request->all());
        $brand->save();
		    alert()->success('Successfully saved');
           return redirect()->route('panel.brands.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        alert()->success('Successfully deleted');
        return redirect()->route('panel.brands.index');
    }
}
