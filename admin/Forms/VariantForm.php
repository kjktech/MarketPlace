<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Variant;

class VariantForm extends Form
{
    public function buildForm()
    {
        $this->add('attribute', 'text', [
            'rules' => 'required|min:3'
        ]);

        $this->add('values_string', 'text', [
            'rules' => 'required|min:3',
            'attr' => ['class' => 'values_string'],
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
