<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\StoreCategory as Category;
use Kris\LaravelFormBuilder\FormBuilder;

class StoreCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('order', 'ASC')->nested()->get();
        $data['categories'] = flatten($categories, 0);
        return view('panel::storecategories.index', $data);
    }

    public function save_languages()
    {
        $categories = Category::orderBy('order', 'ASC')->get();
        save_language_file('storecategories', $categories->pluck('name')->toArray());
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $dropdown = Category::attr(['name' => 'parent_id', 'class'=>  'form-control'])->placeholder(0, '--SELECT--')->nested()->renderAsDropdown();
        $form = $formBuilder->create('Modules\Panel\Forms\StoreCategoryForm', [
           'method' => 'POST',
           'url' => route('panel.storecategories.store')
        ]);
        return view('panel::storecategories.create', compact('form', 'dropdown'));
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
        return redirect()->route('panel.storecategories.index');
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

        $form = $formBuilder->create('Modules\Panel\Forms\StoreCategoryForm', [
            'method' => 'PUT',
            'url' => route('panel.storecategories.update', $id),
            'model' => $category
        ]);
        return view('panel::storecategories.create', compact('form', 'dropdown'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->fill($request->all());
        $category->save();


		$this->save_languages();
		alert()->success('Successfully saved');
        return redirect()->route('panel.storecategories.index');
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
        return redirect()->route('panel.storecategories.index');
    }
}
