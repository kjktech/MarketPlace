<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\DirectoryCategory as Category;
use Kris\LaravelFormBuilder\FormBuilder;
use Image;
use Storage;

class DirectoryCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('order', 'ASC')->nested()->get();
        $data['categories'] = flatten($categories, 0);
        return view('panel::directorycategories.index', $data);
    }

    public function save_languages()
    {
        $categories = Category::orderBy('order', 'ASC')->get();
        save_language_file('directorycategories', $categories->pluck('name')->toArray());
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $dropdown = Category::attr(['name' => 'parent_id', 'class'=>  'form-control'])->placeholder(0, '--SELECT--')->nested()->renderAsDropdown();
        $form = $formBuilder->create('Modules\Panel\Forms\DirectoryCategoryForm', [
           'method' => 'POST',
           'url' => route('panel.directorycategories.store')
        ]);
        return view('panel::directorycategories.create', compact('form', 'dropdown'));
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

		  $this->save_languages();
		  alert()->success('Successfully saved');
        return redirect()->route('panel.directorycategories.index');
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

        $form = $formBuilder->create('Modules\Panel\Forms\DirectoryCategoryForm', [
            'method' => 'PUT',
            'enctype' => 'multipart/form-data',
            'url' => route('panel.directorycategories.update', $id),
            'model' => $category
        ]);
        return view('panel::directorycategories.create', compact('form', 'dropdown'));
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
            $image = Image::make($request->file('image'))
                    ->fit(400, 475, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->resizeCanvas(400, 475);
            Storage::cloud()->put('brandcategories/'.$category->path, (string) $image->encode());
            $category->banner = Storage::cloud()->url("brandcategories/".$category->path);
            $category->save();
        }
        if($request->file('pagebannerimage')) {
            $image = Image::make($request->file('pagebannerimage'))
                    ->fit(1439, 195, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->resizeCanvas(1439, 195);
            Storage::cloud()->put('brandcatpgbanner/'.$category->path, (string) $image->encode());
            $category->pagebanner = Storage::cloud()->url("brandcatpgbanner/".$category->path);
            $category->save();
        }
        if($request->file('icon')) {
            $image = Image::make($request->file('icon'))
                    ->fit(32, 28, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->resizeCanvas(32, 28);
            Storage::cloud()->put('brandcaticon/'.$category->png, (string) $image->encode());
            $category->icon = Storage::cloud()->url("brandcaticon/".$category->png);
            $category->save();
        }
        $category->fill($request->all());
        $category->save();
	    	$this->save_languages();
		    alert()->success('Successfully saved');
        return redirect()->route('panel.directorycategories.index');
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
        return redirect()->route('panel.directorycategories.index');
    }
}
