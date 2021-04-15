<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Category;
use Kris\LaravelFormBuilder\FormBuilder;
use Image;
use Storage;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('order', 'ASC')->nested()->get();
        $data['categories'] = flatten($categories, 0);

        return view('panel::categories.index', $data);
    }

    public function save_languages()
    {
        $categories = Category::orderBy('order', 'ASC')->get();
        save_language_file('categories', $categories->pluck('name')->toArray());
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $dropdown = Category::attr(['name' => 'parent_id', 'class'=>  'form-control'])->placeholder(0, '--SELECT--')->nested()->renderAsDropdown();
        $form = $formBuilder->create('Modules\Panel\Forms\CategoryForm', [
           'method' => 'POST',
           'url' => route('panel.categories.store')
        ]);
        return view('panel::categories.create', compact('form', 'dropdown'));
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
        $data['order'] = $request->get('order', 1);
        $data['parent_id'] = $request->get('parent_id');
        $category = Category::create( $data );

		$pricing_models = $request->input('pricing_models');
		if($pricing_models && is_array($pricing_models)) {
			$category->pricing_models()->sync($pricing_models);
		}
		$this->save_languages();
		alert()->success('Successfully saved');
        return redirect()->route('panel.categories.index');
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
        $category = Category::findOrFail($id);

        $dropdown = Category::attr(['name' => 'parent_id', 'class'=>  'form-control'])->placeholder(0, '--SELECT--')->orderBy('order', 'ASC')->nested()->selected($category->parent_id)->renderAsDropdown();

        $form = $formBuilder->create('Modules\Panel\Forms\CategoryForm', [
            'method' => 'PUT',
            'enctype' => 'multipart/form-data',
            'url' => route('panel.categories.update', $id),
            'model' => $category
        ]);
        return view('panel::categories.create', compact('form', 'dropdown'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if($request->file('image')) {
          //dd('Test');
            $image = Image::make($request->file('image'))
                    ->fit(389, 504, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->resizeCanvas(389, 504);
            Storage::cloud()->put('categories/'.$category->path, (string) $image->encode());
            $category->banner = Storage::cloud()->url("categories/".$category->path);
            $category->save();
        }
        if($request->file('icon')) {

            $image = Image::make($request->file('icon'))
                    ->fit(32, 28, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->resizeCanvas(32, 28);
            Storage::cloud()->put('categoriesicon/'.$category->png, (string) $image->encode());
            $storage_url = Storage::cloud()->url("categoriesicon/".$category->png);
            //dd($storage_url);
            $category->icon = $storage_url;
            $category->save();
        }
        $category->fill($request->all());
        $category->save();

		$pricing_models = $request->input('pricing_models');
		if($pricing_models && is_array($pricing_models)) {
			$category->pricing_models()->sync($pricing_models);
		}

    $product_brands = $request->input('brands');
		if($product_brands && is_array($product_brands)) {
			$category->product_brands()->sync($product_brands);
		}
		$this->save_languages();
		alert()->success('Successfully saved');
        return redirect()->route('panel.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        alert()->success('Successfully deleted');
        return redirect()->route('panel.categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function variant($id, FormBuilder $formBuilder)
    {
        $category = Category::findOrFail($id);

        $form = $formBuilder->create('Modules\Panel\Forms\CategoryVariantForm', [
            'method' => 'PUT',
            'enctype' => 'multipart/form-data',
            'url' => route('panel.categories.updatevariant', $id),
            'model' => $category
        ]);
        return view('panel::categories.variant', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updatevariant(Request $request, $id)
    {
      $category = Category::findOrFail($id);
		  $variant_models = $request->input('variants');
		  if($variant_models && is_array($variant_models)) {
			 $category->variants()->sync($variant_models);
		  }
		 alert()->success('Successfully saved');
        return redirect()->route('panel.categories.index');
     }
}
