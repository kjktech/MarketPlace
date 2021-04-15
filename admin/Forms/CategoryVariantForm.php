<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Variant;

class CategoryVariantForm extends Form
{

  public function __construct()
  {

  }

  public function buildForm()
  {

		 $variant_models = Variant::pluck('attribute','id');
		 if($variant_models)
			  $variant_models = $variant_models->toArray();
		 else
			 $variant_models = [];

		 $this->add('variants', 'select', [
			'label' => "Variant type",
			'choices' => $variant_models,
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

      $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
