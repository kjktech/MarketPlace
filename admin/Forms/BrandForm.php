<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Brand;

class BrandForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);
	
        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
