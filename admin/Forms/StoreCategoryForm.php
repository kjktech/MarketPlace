<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PricingModel;
use App\Models\Category;

class StoreCategoryForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);

        $this->add('order', 'number', [
            'rules' => 'integer'
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
