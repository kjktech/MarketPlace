<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Setting;
use App\Models\Variant;

class VariantsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, FormBuilder $formBuilder)
    {
        $variants = Variant::orderBy('attribute', 'DESC')->get();
        return view('panel::variants.index', compact('variants'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        //

        //php artisan make:form Forms/FilterForm --fields="name:text, field:text, form_ui:select, categories:select, hidden:checkbox, default:checkbox"
        $form = $formBuilder->create('Modules\Panel\Forms\VariantForm', [
            'method' => 'POST',
            'url' => route('panel.variants.store')
        ]);
        return view('panel::variants.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        #dd($params);
        #$params['form_input_meta'] = json_encode(json_decode($params['form_input_meta'], true)[0]);

        $variant = new Variant();
        $variant->attribute = $request->input('attribute');
        $variant->values = explode(",", $request->input('values_string'));
        $variant->values_string = $request->input('values_string');
        $variant->save();
		    alert()->success('Successfully saved');
        return redirect(route('panel.variants.index'));
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

		$variant = Variant::findOrFail($id);
        $form = $formBuilder->create('Modules\Panel\Forms\VariantForm', [
            'method' => 'PUT',
            'url' => route('panel.variants.update', $id),
            'model' => $variant
        ]);

        return view('panel::variants.create', compact('form', 'variant'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {

        $variant = Variant::findOrFail($id);
        $variant->attribute = $request->input('attribute');
        $variant->values = explode(",", $request->input('values_string'));
        $variant->values_string = $request->input('values_string');
        $variant->save();


		alert()->success('Successfully saved');
        return redirect(route('panel.variants.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
		$filter = Filter::findOrFail($id);
		$filter->delete();
		alert()->success('Successfully deleted');
        return redirect(route('panel.fields.index'));
    }
}
