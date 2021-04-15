<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PricingModel;
use App\Models\Category;
use App\Models\Brand;
use App\Models\BrandCategory;
//use App\Forms\Fields\MultiCheckboxType;

class CategoryForm extends Form
{

  public function __construct()
  {

  }

    public function buildForm()
    {
        $this->addCustomField('multicheckbox', 'App\Forms\Fields\MultiCheckboxType');
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);

        $this->add('order', 'number', [
            'rules' => 'integer'
        ]);

		  $pricing_models = PricingModel::pluck('seller_label','id');
		  if($pricing_models)
			  $pricing_models = $pricing_models->toArray();
		  else
			  $pricing_models = [];

		  $this->add('pricing_models', 'select', [
			'label' => "Seller listing type",
			'choices' => $pricing_models,
			'attr' => [
                'class' => 'form-control',
                'style' => 'height: 160px',
                'multiple' => 'multiple'
            ],
			 'selected' => function ($data) {
				if($data)
          //dd($data);
					return array_pluck($data, 'id');
				return [];
		  	},
            'rules' => '',
        ]);
        $selected_brands = null;
        if($this->model){
           $selected_brands = BrandCategory::where('category_id', $this->model->id)->get()->pluck('brand_id');
        }
        if($selected_brands)
  			  $selected_brands = $selected_brands->toArray();
  		  else
  			  $selected_brands = [];
        $this->add('brands', 'multicheckbox', [
          "modeldata" => Brand::orderBy('name', 'DESC')->get(),
          "selecteddata" => $selected_brands
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
